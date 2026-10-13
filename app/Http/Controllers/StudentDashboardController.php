<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\LearningMaterialProgress;
use App\Models\LearningTask;
use App\Models\StudentProgress;
use App\Models\UserRegister;
use App\Services\GeminiService;
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

        $tasks = $this->pendingPlayableTasks(
            $user->id,
            $playableGameIds,
            $playableGamesById
        );

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

        $coveredCategories = $tasks
            ->pluck('category')
            ->map(fn ($category) => strtolower(trim((string) $category)))
            ->unique()
            ->all();
        $gamesNeedingTasks = $playableGames
            ->reject(fn (Game $game) => in_array(
                strtolower(trim((string) $game->category)),
                $coveredCategories,
                true
            ))
            ->values();

        $generationAttempted = $gamesNeedingTasks->isNotEmpty();

        if ($generationAttempted) {
            $generationGameIds = $gamesNeedingTasks->modelKeys();
            $generationGamesById = $gamesNeedingTasks->keyBy('id');
            $missingInterests = $gamesNeedingTasks
                ->pluck('category')
                ->unique()
                ->values()
                ->all();
            $previousTaskTitles = LearningTask::query()
                ->where('user_id', $user->id)
                ->whereIn('game_id', $generationGameIds)
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

            $generatedData = $gemini->generateTasks(
                $missingInterests,
                $preference->learning_goal
                    ?? 'Practice and learn',
                $preference->experience_level
                    ?? 'beginner',
                4,
                $previousTaskTitles
            );

            Log::info('EcoQuest Gemini task response', [
                'user_id' => $user->id,
                'generated_count' => count($generatedData['tasks'] ?? []),
            ]);

            $tasksToSave = [];
            $seenTaskKeys = [];

            foreach (($generatedData['tasks'] ?? []) as $generatedTask) {
                if (! is_array($generatedTask)) {
                    continue;
                }

                $gameType = strtolower(trim((string) ($generatedTask['game_type'] ?? '')));
                $gameId = filter_var($generatedTask['game_id'] ?? null, FILTER_VALIDATE_INT);
                $game = $gameId !== false ? $generationGamesById->get($gameId) : null;
                $title = trim((string) ($generatedTask['title'] ?? ''));
                $difficultyValue = strtolower(trim((string) ($generatedTask['difficulty'] ?? '')));
                $xp = filter_var($generatedTask['xp'] ?? null, FILTER_VALIDATE_INT);
                $gameData = $generatedTask['game_data'] ?? null;

                if (
                    ! $game
                    || strtolower(trim($game->game_type)) !== $gameType
                    || $title === ''
                    || ! in_array($difficultyValue, ['beginner', 'intermediate', 'advanced'], true)
                    || $xp === false
                    || $xp < 10
                    || $xp > 50
                    || ! is_array($gameData)
                ) {
                    Log::warning('Skipping invalid generated learning task.', [
                        'user_id' => $user->id,
                        'game_type' => $gameType,
                    ]);

                    continue;
                }

                $taskKey = $gameType.':'.strtolower($title);
                if (isset($seenTaskKeys[$taskKey])) {
                    continue;
                }
                $seenTaskKeys[$taskKey] = true;

                $tasksToSave[] = [
                    'user_id' => $user->id,
                    'game_id' => $game->id,
                    'game_type' => $gameType,
                    'title' => $title,
                    'description' => trim((string) ($generatedTask['description'] ?? '')),
                    'category' => trim((string) $game->category),
                    'difficulty' => $difficultyValue,
                    'xp' => $xp,
                    'game_data' => $gameData,
                    'status' => 'pending',
                ];
            }

            $savedCount = DB::transaction(function () use (
                $user,
                $generationGameIds,
                $generationGamesById,
                $tasksToSave
            ): int {
                UserRegister::query()
                    ->whereKey($user->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $coveredGameIds = LearningTask::query()
                    ->where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->whereIn('game_id', $generationGameIds)
                    ->get(['game_id', 'game_type', 'category'])
                    ->filter(function (LearningTask $task) use ($generationGamesById): bool {
                        $game = $generationGamesById->get((int) $task->game_id);

                        return $game instanceof Game
                            && strtolower(trim((string) $task->game_type))
                                === strtolower(trim((string) $game->game_type))
                            && strtolower(trim((string) $task->category))
                                === strtolower(trim((string) $game->category));
                    })
                    ->pluck('game_id')
                    ->map(fn ($gameId) => (int) $gameId)
                    ->all();

                $saved = 0;

                foreach ($tasksToSave as $taskData) {
                    if (in_array((int) $taskData['game_id'], $coveredGameIds, true)) {
                        continue;
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
                    $saved++;
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

        $tasks = $this->pendingPlayableTasks(
            $user->id,
            $playableGameIds,
            $playableGamesById
        );
        $questGenerationFailed = $playableGames->isNotEmpty()
            && $tasks->isEmpty()
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
            ?? 'Complete one learning challenge today.';

        $dailyMissionMaterial = $materialProgression->nextForInterests(
            $user->id,
            $interests
        );
        $dailyMissionProgress = $dailyMissionMaterial
            ? LearningMaterialProgress::query()
                ->where('user_id', $user->id)
                ->where('learning_material_id', $dailyMissionMaterial->id)
                ->first()
            : null;
        if ($dailyMissionMaterial) {
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
                'dailyMissionTitle',
                'dailyMissionDescription',
                'dailyMissionUrl',
                'dailyMissionAction',
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
