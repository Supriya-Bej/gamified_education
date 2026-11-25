<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\LearningMaterialProgress;
use App\Models\LearningTask;
use App\Models\ProjectChallenge;
use App\Models\StudentProgress;
use App\Models\UserRegister;
use App\Services\GeminiService;
use App\Services\InterestGameProgressionService;
use App\Services\LearningMaterialProgressionService;
use App\Services\StudentThemeService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentDashboardController extends Controller
{
    public function chat(Request $request, GeminiService $gemini)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
            'history' => ['sometimes', 'array', 'max:8'],
            'history.*.role' => ['required', Rule::in(['user', 'assistant'])],
            'history.*.content' => ['required', 'string', 'max:1000'],
        ]);

        $student = Auth::guard('student')->user();
        $preference = $student?->preferences;
        $interests = is_array($preference?->interests)
            ? $preference->interests
            : [];
        $topics = is_array($preference?->ai_profile)
            && is_array($preference->ai_profile['recommended_topics'] ?? null)
            ? $preference->ai_profile['recommended_topics']
            : [];

        $assistantResponse = $gemini->chatAboutRecommendations(
            $interests,
            $topics,
            $validated['history'] ?? [],
            trim($validated['message'])
        );

        return response()->json([
            'success' => true,
            'reply' => $assistantResponse['reply'],
            'mode' => $assistantResponse['mode'],
        ]);
    }

    /**
     * Student Dashboard
     */
    public function index(
        Request $request,
        GeminiService $gemini,
        InterestGameProgressionService $interestProgression,
        StudentThemeService $themeService,
        LearningMaterialProgressionService $materialProgression
    ) {
        /*
        |--------------------------------------------------------------------------
        | GET LOGGED-IN STUDENT
        |--------------------------------------------------------------------------
        */

        $user = Auth::guard('student')->user();

        /*
        |--------------------------------------------------------------------------
        | CHECK LOGIN
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        /*
        |--------------------------------------------------------------------------
        | GET STUDENT PREFERENCE
        |--------------------------------------------------------------------------
        */

        $preference = $user->preferences;

        /*
        |--------------------------------------------------------------------------
        | PREFERENCE NOT FOUND
        |--------------------------------------------------------------------------
        */

        if (! $preference) {
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Student preferences not found.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | GET STUDENT PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress = $user->progress;

        /*
        |--------------------------------------------------------------------------
        | CREATE PROGRESS IF NOT EXISTS
        |--------------------------------------------------------------------------
        */

        if (! $progress) {

            $progress = StudentProgress::create([
                'user_id' => $user->id,
                'total_xp' => 0,
                'level' => 1,
                'completed_tasks' => 0,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | GET STUDENT INTERESTS
        |--------------------------------------------------------------------------
        */

        $interests = [];

        /*
        |--------------------------------------------------------------------------
        | INTERESTS STORED AS ARRAY
        |--------------------------------------------------------------------------
        */

        if (is_array($preference->interests)) {

            $interests = array_values(
                array_filter(
                    $preference->interests,
                    function ($interest) {
                        return is_string($interest)
                            && trim($interest) !== '';
                    }
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INTEREST STORED AS STRING
        |--------------------------------------------------------------------------
        */

        if (
            empty($interests) &&
            is_string($preference->interests) &&
            trim($preference->interests) !== ''
        ) {

            $interests = [
                trim($preference->interests),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | AI PERSONALIZED PROFILE
        |--------------------------------------------------------------------------
        |
        | Gemini creates the student's personalized profile.
        |
        */

        $aiProfile = is_array($preference->ai_profile)
            ? $preference->ai_profile
            : [];

        /*
        |--------------------------------------------------------------------------
        | GENERATE AI PROFILE IF REQUIRED
        |--------------------------------------------------------------------------
        */

        if (
            empty($aiProfile) ||
            ! isset($aiProfile['visual_theme']) ||
            ! isset($aiProfile['rank_title'])
        ) {

            $aiProfile = $gemini->generateProfile(
                $interests,
                $preference->learning_goal
                    ?? 'Master key skills',
                $preference->experience_level
                    ?? 'beginner'
            );

            /*
            |--------------------------------------------------------------------------
            | SAFETY CHECK
            |--------------------------------------------------------------------------
            */

            if (! is_array($aiProfile)) {
                $aiProfile = [];
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE AI PROFILE
            |--------------------------------------------------------------------------
            */

            $preference->update([
                'ai_profile' => $aiProfile,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | RESOLVE STUDENT THEME
        |--------------------------------------------------------------------------
        |
        | StudentThemeService handles:
        |
        | 1. Dashboard focus
        | 2. Session-selected theme
        | 3. Student interest
        | 4. AI visual theme
        | 5. General fallback
        |
        */

        $themeData = $themeService->getTheme(
            $user,
            $request->query('focus')
        );

        /*
        |--------------------------------------------------------------------------
        | THEME DATA
        |--------------------------------------------------------------------------
        */

        $themeKey = $themeData['themeKey']
            ?? 'general';

        $themeConfig = $themeData['themeConfig']
            ?? config(
                'learning_themes.general',
                []
            );

        $theme = $themeData['theme']
            ?? 'General';

        $themeIcon = $themeData['themeIcon']
            ?? '✦';

        $rankTitle = $themeData['rankTitle']
            ?? 'Explorer';

        $difficulty = $themeData['difficulty']
            ?? 'beginner';

        $labels = $themeData['labels']
            ?? [];

        $playableGames = $gemini->getPlayableGamesForInterests($interests);
        $playableGameIds = $playableGames->modelKeys();
        $playableGamesById = $playableGames->keyBy('id');
        $loadCourseProgress = function () use ($playableGames, $interestProgression, $user): array {
            return $playableGames
                ->pluck('category')
                ->filter(fn ($category) => is_string($category) && trim($category) !== '')
                ->map(fn ($category) => strtolower(trim($category)))
                ->unique()
                ->mapWithKeys(fn (string $category) => [
                    $category => $interestProgression->stateFor((int) $user->id, $category),
                ])
                ->all();
        };
        $courseProgress = $loadCourseProgress();
        $filterTasksToActiveStages = function (Collection $candidateTasks) use (&$courseProgress): Collection {
            return $candidateTasks
                ->filter(function (LearningTask $task) use (&$courseProgress): bool {
                    $category = strtolower(trim((string) $task->category));

                    return ($courseProgress[$category]['active_difficulty'] ?? null) === $task->difficulty;
                })
                ->values();
        };

        $this->supersedePendingDuplicateQuests(
            $user->id,
            $playableGameIds,
            $playableGamesById
        );

        /*
        |--------------------------------------------------------------------------
        | GET PENDING LEARNING TASKS
        |--------------------------------------------------------------------------
        */

        $tasks = $filterTasksToActiveStages($this->pendingPlayableTasks(
            $user->id,
            $playableGameIds,
            $playableGamesById
        ));

        /*
        |--------------------------------------------------------------------------
        | GENERATE AI TASKS IF NONE EXIST
        |--------------------------------------------------------------------------
        */

        Log::info('EcoQuest task generation check', [
            'user_id' => $user->id,
            'interests' => $interests,
            'existing_tasks_count' => $tasks->count(),
            'playable_games_count' => $playableGames->count(),
        ]);

        $generationPlans = [];
        foreach ($courseProgress as $category => $state) {
            $difficulty = $state['active_difficulty'];
            $remainingSlots = InterestGameProgressionService::TASKS_PER_LEVEL - $state['active_total'];
            if ($difficulty === null || $remainingSlots < 1) {
                continue;
            }

            $categoryGames = $playableGames
                ->filter(fn (Game $game) => strtolower(trim((string) $game->category)) === $category)
                ->values();
            if ($categoryGames->isEmpty()) {
                continue;
            }

            $hasPendingStageTasks = LearningTask::query()
                ->where('user_id', $user->id)
                ->whereRaw('LOWER(category) = ?', [$category])
                ->where('difficulty', $difficulty)
                ->where('status', 'pending')
                ->whereIn('review_status', ['pending_review', 'approved'])
                ->exists();
            if ($hasPendingStageTasks) {
                continue;
            }

            $generationGamesById = $categoryGames->keyBy('id');
            $previousTaskTitles = LearningTask::query()
                ->where('user_id', $user->id)
                ->whereIn('game_id', $categoryGames->modelKeys())
                ->get(['game_id', 'game_type', 'category', 'title'])
                ->filter(function (LearningTask $task) use ($generationGamesById): bool {
                    $game = $generationGamesById->get((int) $task->game_id);

                    return $game instanceof Game
                        && strtolower(trim((string) $task->game_type))
                            === strtolower(trim((string) $game->game_type))
                        && strtolower(trim((string) $task->category))
                            === strtolower(trim((string) $game->category));
                })
                ->groupBy(fn (LearningTask $task) => strtolower(trim((string) $task->game_type)))
                ->map(fn ($gameTasks) => $gameTasks->pluck('title')->all())
                ->all();

            $generationPlans[$difficulty][$category] = [
                'capacity' => min(4, $remainingSlots),
                'games_by_id' => $generationGamesById,
                'previous_titles' => $previousTaskTitles,
            ];
        }

        $generationBatches = [];
        foreach ($generationPlans as $difficulty => $plansForLevel) {
            $categories = array_keys($plansForLevel);
            $previousTaskTitles = [];
            foreach ($plansForLevel as $plan) {
                foreach ($plan['previous_titles'] as $gameType => $titles) {
                    $previousTaskTitles[$gameType] = array_values(array_unique(array_merge(
                        $previousTaskTitles[$gameType] ?? [],
                        $titles
                    )));
                }
            }

            $batchSize = min(4, array_sum(array_column($plansForLevel, 'capacity')));
            $generatedData = $gemini->generateTasks(
                $categories,
                $preference->learning_goal ?? 'Practice and learn',
                $difficulty,
                $batchSize,
                $previousTaskTitles
            );
            $tasksByCategory = [];
            $seenTaskKeys = [];

            foreach (($generatedData['tasks'] ?? []) as $generatedTask) {
                if (! is_array($generatedTask)) {
                    continue;
                }

                $category = strtolower(trim((string) ($generatedTask['category'] ?? '')));
                $plan = $plansForLevel[$category] ?? null;
                if (! $plan) {
                    continue;
                }

                $gameType = strtolower(trim((string) ($generatedTask['game_type'] ?? '')));
                $gameId = filter_var($generatedTask['game_id'] ?? null, FILTER_VALIDATE_INT);
                $game = $gameId !== false ? $plan['games_by_id']->get($gameId) : null;
                $title = trim((string) ($generatedTask['title'] ?? ''));
                $generatedDifficulty = strtolower(trim((string) ($generatedTask['difficulty'] ?? '')));
                $xp = filter_var($generatedTask['xp'] ?? null, FILTER_VALIDATE_INT);
                $gameData = $generatedTask['game_data'] ?? null;

                if (
                    ! $game
                    || strtolower(trim($game->game_type)) !== $gameType
                    || strtolower(trim((string) $game->category)) !== $category
                    || $title === ''
                    || $generatedDifficulty !== $difficulty
                    || $xp === false
                    || $xp < 10
                    || $xp > 50
                    || ! is_array($gameData)
                ) {
                    Log::warning('Skipping invalid generated learning task.', [
                        'user_id' => $user->id,
                        'game_type' => $gameType,
                        'expected_difficulty' => $difficulty,
                    ]);

                    continue;
                }

                $taskKey = $gameType.':'.strtolower($title);
                if (isset($seenTaskKeys[$taskKey])) {
                    continue;
                }
                $seenTaskKeys[$taskKey] = true;

                $tasksByCategory[$category] ??= [];
                if (count($tasksByCategory[$category]) >= $plan['capacity']) {
                    continue;
                }
                $tasksByCategory[$category][] = [
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'game_type' => $gameType,
                    'title' => $title,
                    'description' => trim((string) ($generatedTask['description'] ?? '')),
                    'category' => $category,
                    'difficulty' => $difficulty,
                    'xp' => $xp,
                    'game_data' => $gameData,
                    'status' => 'pending',
                    'review_status' => 'approved',
                ];
            }

            foreach ($plansForLevel as $category => $plan) {
                $generationBatches[] = [
                    'category' => $category,
                    'difficulty' => $difficulty,
                    'capacity' => $plan['capacity'],
                    'tasks' => $tasksByCategory[$category] ?? [],
                ];
            }
        }

        $generationAttempted = $generationBatches !== [];
        $savedCount = 0;

        if ($generationAttempted) {
            $savedCount = DB::transaction(function () use (
                $user,
                $generationBatches,
                $interestProgression
            ): int {
                UserRegister::query()
                    ->whereKey($user->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $saved = 0;
                foreach ($generationBatches as $batch) {
                    $state = $interestProgression->stateFor((int) $user->id, $batch['category']);
                    if (
                        $state['active_difficulty'] !== $batch['difficulty']
                        || $state['active_total'] >= InterestGameProgressionService::TASKS_PER_LEVEL
                    ) {
                        continue;
                    }

                    $hasPendingStageTasks = LearningTask::query()
                        ->where('user_id', $user->id)
                        ->whereRaw('LOWER(category) = ?', [$batch['category']])
                        ->where('difficulty', $batch['difficulty'])
                        ->where('status', 'pending')
                        ->whereIn('review_status', ['pending_review', 'approved'])
                        ->exists();
                    if ($hasPendingStageTasks) {
                        continue;
                    }

                    $remainingSlots = min(
                        $batch['capacity'],
                        InterestGameProgressionService::TASKS_PER_LEVEL - $state['active_total']
                    );
                    $savedForInterest = 0;
                    foreach ($batch['tasks'] as $taskData) {
                        if ($savedForInterest >= $remainingSlots) {
                            break;
                        }

                        $alreadyExists = LearningTask::query()
                            ->where('user_id', $user->id)
                            ->where('game_type', $taskData['game_type'])
                            ->whereRaw('LOWER(title) = ?', [strtolower($taskData['title'])])
                            ->exists();

                        if ($alreadyExists) {
                            continue;
                        }

                        LearningTask::create($taskData);
                        $savedForInterest++;
                        $saved++;
                    }
                }

                return $saved;
            });

            Log::info('EcoQuest learning tasks saved', [
                'user_id' => $user->id,
                'saved_count' => $savedCount,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD TASKS AGAIN
        |--------------------------------------------------------------------------
        |
        | After AI task generation we fetch the latest tasks again.
        |
        */

        $courseProgress = $loadCourseProgress();
        $tasks = $filterTasksToActiveStages($this->pendingPlayableTasks(
            $user->id,
            $playableGameIds,
            $playableGamesById
        ));
        $pendingReviewCount = LearningTask::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('review_status', 'pending_review')
            ->whereIn('game_id', $playableGameIds)
            ->count();
        $pendingReviewTasks = LearningTask::query()
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->where('review_status', 'pending_review')
            ->whereIn('game_id', $playableGameIds)
            ->latest()
            ->get()
            ->filter(function (LearningTask $task) use ($playableGamesById): bool {
                $game = $playableGamesById->get((int) $task->game_id);

                return $game instanceof Game
                    && strtolower(trim((string) $task->game_type))
                        === strtolower(trim((string) $game->game_type))
                    && strtolower(trim((string) $task->category))
                        === strtolower(trim((string) $game->category));
            })
            ->values();
        $learningQuests = $tasks
            ->concat($pendingReviewTasks)
            ->sortByDesc('created_at')
            ->values();

        $hasActiveProjectChallenge = ProjectChallenge::query()
            ->where('user_id', $user->id)
            ->whereIn('review_status', ['pending_review', 'approved'])
            ->whereDoesntHave('submissions', fn ($query) => $query->where('status', 'accepted'))
            ->exists();
        $today = now()->toDateString();
        $hasProjectChallengeToday = ProjectChallenge::query()
            ->where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->exists();
        $projectChallengeGenerationFailed = $interests === [];
        if (! $hasActiveProjectChallenge && ! $hasProjectChallengeToday && $interests !== []) {
            $previousChallenges = ProjectChallenge::query()
                ->where('user_id', $user->id)
                ->get(['title', 'project_brief'])
                ->map(fn (ProjectChallenge $challenge) => [
                    'title' => $challenge->title,
                    'project_brief' => $challenge->project_brief,
                ])
                ->all();

            $projectChallengeData = $gemini->generateProjectChallenge(
                $interests,
                $preference->learning_goal ?? 'Practice and learn',
                $preference->experience_level ?? 'beginner',
                $previousChallenges
            );

            if ($projectChallengeData === null) {
                $projectChallengeGenerationFailed = true;
            } else {
                DB::transaction(function () use ($user, $projectChallengeData, $today): void {
                    UserRegister::query()
                        ->whereKey($user->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    $alreadyHasActiveChallenge = ProjectChallenge::query()
                        ->where('user_id', $user->id)
                        ->whereIn('review_status', ['pending_review', 'approved'])
                        ->whereDoesntHave('submissions', fn ($query) => $query->where('status', 'accepted'))
                        ->exists();
                    $alreadyCreatedToday = ProjectChallenge::query()
                        ->where('user_id', $user->id)
                        ->whereDate('created_at', $today)
                        ->exists();

                    if (! $alreadyHasActiveChallenge && ! $alreadyCreatedToday) {
                        $alreadyCreated = ProjectChallenge::query()
                            ->where('user_id', $user->id)
                            ->whereRaw('LOWER(title) = ?', [
                                mb_strtolower(trim($projectChallengeData['title'])),
                            ])
                            ->exists();

                        if ($alreadyCreated) {
                            return;
                        }

                        ProjectChallenge::create([
                            ...$projectChallengeData,
                            'user_id' => $user->id,
                            'ai_verification_status' => 'passed',
                            'review_status' => 'pending_review',
                        ]);
                    }
                });
            }

            $hasProjectChallengeToday = ProjectChallenge::query()
                ->where('user_id', $user->id)
                ->whereDate('created_at', $today)
                ->exists();
        }

        $projectChallenge = ProjectChallenge::query()
            ->with(['submissions' => fn ($query) => $query->orderByDesc('id')])
            ->where('user_id', $user->id)
            ->whereIn('review_status', ['pending_review', 'approved'])
            ->whereDoesntHave('submissions', fn ($query) => $query->where('status', 'accepted'))
            ->latest()
            ->first();

        $questGenerationFailed = $playableGames->isNotEmpty()
            && $tasks->isEmpty()
            && $pendingReviewCount === 0
            && $generationAttempted;

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD CONTENT
        |--------------------------------------------------------------------------
        |
        | These values are used by the existing dashboard Blade.
        |
        */

        $userXp = $progress->total_xp ?? 0;

        $userLevel = $progress->level ?? 1;

        $completedTasks =
            $progress->completed_tasks ?? 0;

        /*
        |--------------------------------------------------------------------------
        | AI DASHBOARD CONTENT
        |--------------------------------------------------------------------------
        */

        $dashboardTitle =
            $aiProfile['dashboard_title']
            ?? 'Your Learning Journey';

        $tagline =
            $aiProfile['tagline']
            ?? 'Learn. Play. Grow.';

        $motto =
            $aiProfile['motto']
            ?? 'Keep learning and keep growing.';

        $welcomeMessage =
            $aiProfile['welcome_message']
            ?? 'Welcome back! Ready for your next challenge?';

        $dailyQuest =
            $aiProfile['daily_quest']
            ?? 'Complete one learning quest today.';

        $dailyMissionTask = $tasks->first();
        $dailyMissionMaterial = null;
        $dailyMissionProgress = null;
        $latestProjectSubmission = $projectChallenge?->submissions->first();

        if ($projectChallenge) {
            $dailyMissionTitle = $projectChallenge->title;

            if ($projectChallenge->review_status === 'pending_review') {
                $dailyMissionDescription = 'This project challenge is waiting for admin approval.';
                $dailyMissionUrl = null;
                $dailyMissionAction = null;
            } else {
                $dailyMissionDescription = match ($latestProjectSubmission?->status) {
                    'submitted' => 'Your project submission is waiting for admin review.',
                    'revision_requested' => 'Admin requested changes. Update your project and submit it again.',
                    default => 'Complete this real-world project and submit it to earn XP.',
                };
                $dailyMissionUrl = route('student.project-challenges.show', $projectChallenge);
                $dailyMissionAction = match ($latestProjectSubmission?->status) {
                    'submitted' => 'View submission',
                    'revision_requested' => 'Update challenge',
                    default => 'Start challenge',
                };
            }
        } else {
            $dailyMissionMaterial = $dailyMissionTask
                ? null
                : $materialProgression->nextForInterests($user->id, $interests);
            $dailyMissionProgress = $dailyMissionMaterial
                ? LearningMaterialProgress::query()
                    ->where('user_id', $user->id)
                    ->where('learning_material_id', $dailyMissionMaterial->id)
                    ->first()
                : null;

            if ($dailyMissionTask) {
                $dailyMissionTitle = $dailyMissionTask->title;
                $dailyMissionDescription = $dailyMissionTask->description
                    ?? 'Complete this approved game quest to earn XP.';
                $dailyMissionUrl = route('student.game.play', $dailyMissionTask);
                $dailyMissionAction = 'Start quest';
            } elseif ($dailyMissionMaterial) {
                $dailyMissionTitle = $dailyMissionProgress?->completed_at
                    ? 'Take the quiz: '.$dailyMissionMaterial->title
                    : ($dailyMissionProgress?->started_at
                        ? 'Continue lesson: '.$dailyMissionMaterial->title
                        : 'Start lesson: '.$dailyMissionMaterial->title);
                $dailyMissionDescription = $dailyMissionProgress?->completed_at
                    ? 'Pass the lesson quiz with at least 70% to unlock the next level.'
                    : 'Complete this interest-matched lesson, then pass its quiz to unlock the next level.';
                $dailyMissionUrl = route('student.learning-materials.show', $dailyMissionMaterial);
                $dailyMissionAction = $dailyMissionProgress?->completed_at
                    ? 'Continue to quiz'
                    : 'Start lesson';
            } else {
                $dailyMissionTitle = 'Explore a lesson in your interests';
                $dailyMissionDescription = 'Browse learning materials to find your next lesson and quiz.';
                $dailyMissionUrl = route('student.learning-materials.index');
                $dailyMissionAction = 'Browse lessons';
            }
        }

        $recommendedTopics =
            $aiProfile['recommended_topics']
            ?? [];

        $learningStyle =
            $aiProfile['learning_style']
            ?? 'Interactive';

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD VIEW
        |--------------------------------------------------------------------------
        */

        return response()->view(
            'student.dashboard',
            compact(
                'user',
                'preference',
                'aiProfile',

                'tasks',
                'learningQuests',
                'playableGames',

                'themeKey',
                'themeConfig',
                'theme',
                'themeIcon',

                'rankTitle',
                'difficulty',
                'labels',

                'progress',
                'interests',

                'userXp',
                'userLevel',
                'completedTasks',

                'dashboardTitle',
                'tagline',
                'motto',
                'welcomeMessage',
                'dailyQuest',
                'dailyMissionMaterial',
                'dailyMissionTask',
                'dailyMissionTitle',
                'dailyMissionDescription',
                'dailyMissionUrl',
                'dailyMissionAction',
                'pendingReviewCount',
                'projectChallenge',
                'hasProjectChallengeToday',
                'projectChallengeGenerationFailed',
                'recommendedTopics',
                'learningStyle',
                'questGenerationFailed'
            )
        )->header('Cache-Control', 'private, no-store, no-cache, must-revalidate');
    }

    /**
     * Keep stale or mismatched game associations out of the student dashboard.
     *
     * @param  array<int, int>  $gameIds
     */
    private function pendingPlayableTasks(
        int $userId,
        array $gameIds,
        Collection $gamesById
    ): Collection {
        $completedTitleKeys = LearningTask::query()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereIn('game_id', $gameIds)
            ->get(['game_id', 'game_type', 'category', 'title'])
            ->filter(function (LearningTask $task) use ($gamesById): bool {
                $game = $gamesById->get((int) $task->game_id);

                return $game instanceof Game
                    && strtolower(trim((string) $task->game_type))
                        === strtolower(trim((string) $game->game_type))
                    && strtolower(trim((string) $task->category))
                        === strtolower(trim((string) $game->category));
            })
            ->map(fn (LearningTask $task) => (int) $task->game_id.':'.strtolower(trim($task->title)))
            ->all();

        return LearningTask::query()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->where('review_status', 'approved')
            ->whereIn('game_id', $gameIds)
            ->latest()
            ->get()
            ->filter(function (LearningTask $task) use ($gamesById, $completedTitleKeys): bool {
                $game = $gamesById->get((int) $task->game_id);

                return $game instanceof Game
                    && strtolower(trim((string) $task->game_type))
                        === strtolower(trim($game->game_type))
                    && strtolower(trim((string) $task->category))
                        === strtolower(trim((string) $game->category))
                    && ! in_array(
                        (int) $task->game_id.':'.strtolower(trim($task->title)),
                        $completedTitleKeys,
                        true
                    );
            })
            ->values();
    }

    /**
     * Preserve stale pending rows, but prevent replaying an already completed quest.
     *
     * @param  array<int, int>  $gameIds
     */
    private function supersedePendingDuplicateQuests(
        int $userId,
        array $gameIds,
        Collection $gamesById
    ): void {
        if ($gameIds === []) {
            return;
        }

        $completedTitleKeys = LearningTask::query()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->whereIn('game_id', $gameIds)
            ->get(['game_id', 'game_type', 'category', 'title'])
            ->filter(function (LearningTask $task) use ($gamesById): bool {
                $game = $gamesById->get((int) $task->game_id);

                return $game instanceof Game
                    && strtolower(trim((string) $task->game_type))
                        === strtolower(trim((string) $game->game_type))
                    && strtolower(trim((string) $task->category))
                        === strtolower(trim((string) $game->category));
            })
            ->map(fn (LearningTask $task) => (int) $task->game_id.':'.strtolower(trim($task->title)))
            ->all();

        if ($completedTitleKeys === []) {
            return;
        }

        $duplicatePendingIds = LearningTask::query()
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->whereIn('game_id', $gameIds)
            ->get(['id', 'game_id', 'game_type', 'category', 'title'])
            ->filter(function (LearningTask $task) use ($gamesById, $completedTitleKeys): bool {
                $game = $gamesById->get((int) $task->game_id);

                return $game instanceof Game
                    && strtolower(trim((string) $task->game_type))
                        === strtolower(trim((string) $game->game_type))
                    && strtolower(trim((string) $task->category))
                        === strtolower(trim((string) $game->category))
                    && in_array(
                        (int) $task->game_id.':'.strtolower(trim($task->title)),
                        $completedTitleKeys,
                        true
                    );
            })
            ->pluck('id')
            ->all();

        if ($duplicatePendingIds !== []) {
            LearningTask::query()
                ->where('user_id', $userId)
                ->whereIn('id', $duplicatePendingIds)
                ->update(['status' => 'superseded']);
        }
    }
}
