<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\LearningTask;
use App\Models\StudentProgress;
use App\Models\Game;

use App\Services\GeminiService;
use App\Services\StudentThemeService;

class StudentDashboardController extends Controller
{
    /**
     * Student Dashboard
     */
    public function index(
        Request $request,
        GeminiService $gemini,
        StudentThemeService $themeService
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

        if (!$user) {
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

        if (!$preference) {
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

        if (!$progress) {

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
                trim($preference->interests)
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | AI PERSONALIZED PROFILE
        |--------------------------------------------------------------------------
        |
        | Gemini creates the student's personalized profile.
        |
        | We are NOT removing this.
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
            !isset($aiProfile['visual_theme']) ||
            !isset($aiProfile['rank_title'])
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

            if (!is_array($aiProfile)) {
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

        /*
        |--------------------------------------------------------------------------
        | GET PENDING LEARNING TASKS
        |--------------------------------------------------------------------------
        */

        $tasks = LearningTask::where(
            'user_id',
            $user->id
        )
            ->where(
                'status',
                'pending'
            )
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GENERATE AI TASKS IF NONE EXIST
        |--------------------------------------------------------------------------
        */

        if ($tasks->isEmpty()) {

            $generatedData = $gemini->generateTasks(
                $interests,
                $preference->learning_goal
                    ?? 'Practice and learn',
                $preference->experience_level
                    ?? 'beginner',
                4
            );

            /*
            |--------------------------------------------------------------------------
            | CHECK GEMINI RESPONSE
            |--------------------------------------------------------------------------
            */

            if (
                is_array($generatedData) &&
                isset($generatedData['tasks']) &&
                is_array($generatedData['tasks'])
            ) {

                foreach (
                    $generatedData['tasks']
                    as $task
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | GAME TYPE
                    |--------------------------------------------------------------------------
                    */

                    $gameType = strtolower(
                        trim(
                            $task['game_type'] ?? ''
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | FIND PLAYABLE GAME
                    |--------------------------------------------------------------------------
                    */

                    $game = null;

                    if ($gameType !== '') {

                        $game = Game::whereRaw(
                            'LOWER(game_type) = ?',
                            [$gameType]
                        )->first();
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CREATE LEARNING TASK
                    |--------------------------------------------------------------------------
                    */

                    LearningTask::create([

                        'user_id' => $user->id,

                        /*
                        | Exact game ID if available
                        */

                        'game_id' => $game?->id,

                        /*
                        | Store AI requested game type
                        */

                        'game_type' => $gameType !== ''
                            ? $gameType
                            : null,

                        /*
                        | Task title
                        */

                        'title' => $task['title']
                            ?? 'Learning Quest',

                        /*
                        | Description
                        */

                        'description' =>
                        $task['description']
                            ?? null,

                        /*
                        | Category
                        */

                        'category' =>
                        $task['category']
                            ?? 'Quest',

                        /*
                        | Difficulty
                        */

                        'difficulty' =>
                        $task['difficulty']
                            ?? 'beginner',

                        /*
                        | XP
                        */

                        'xp' =>
                        $task['xp']
                            ?? 20,

                        /*
                        | New tasks are pending
                        */

                        'status' => 'pending',
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD TASKS AGAIN
        |--------------------------------------------------------------------------
        |
        | After AI task generation we fetch the latest tasks again.
        |
        */

        $tasks = LearningTask::where(
            'user_id',
            $user->id
        )
            ->where(
                'status',
                'pending'
            )
            ->latest()
            ->get();

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

        return view(
            'student.dashboard',
            compact(
                'user',
                'preference',
                'aiProfile',

                'tasks',

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
                'recommendedTopics',
                'learningStyle'
            )
        );
    }
}
