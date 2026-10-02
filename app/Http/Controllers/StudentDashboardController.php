<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GeminiService;
use App\Models\LearningTask;
use App\Models\StudentProgress;
use App\Models\Game;

class StudentDashboardController extends Controller
{
    public function index(Request $request, GeminiService $gemini)
    {
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
        | GET STUDENT PREFERENCES & PROGRESS
        |--------------------------------------------------------------------------
        */
        $preference = $user->preferences;

        if (!$preference) {
            return redirect()
                ->route('login')
                ->with('error', 'Student preferences not found.');
        }

        $progress = $user->progress;

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
        | GET INTERESTS
        |--------------------------------------------------------------------------
        */
        $interests = is_array($preference->interests)
            ? $preference->interests
            : [];

        if (empty($interests) && !empty($preference->interests)) {
            $interests = [$preference->interests];
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK CHESS INTEREST
        |--------------------------------------------------------------------------
        */
        $hasChessInterest = false;

        foreach ($interests as $item) {
            if (stripos($item, 'chess') !== false) {
                $hasChessInterest = true;
                break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | AI PERSONALIZED PROFILE
        |--------------------------------------------------------------------------
        */
        $aiProfile = $preference->ai_profile;

        if (
            !$aiProfile ||
            !isset($aiProfile['visual_theme']) ||
            !isset($aiProfile['rank_title'])
        ) {
            $aiProfile = $gemini->generateProfile(
                $interests,
                $preference->learning_goal ?? 'Master key skills',
                $preference->experience_level ?? 'beginner'
            );

            $preference->update([
                'ai_profile' => $aiProfile,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | THEME RESOLUTION
        |--------------------------------------------------------------------------
        */
        $themes = config('learning_themes');

        $themeAliases = [
            'chess' => 'chess',

            'coding' => 'technology',
            'programming' => 'technology',
            'computer' => 'technology',
            'computers' => 'technology',
            'software' => 'technology',
            'technology' => 'technology',
            'web development' => 'technology',

            'singing' => 'music',
            'song' => 'music',
            'songs' => 'music',
            'music' => 'music',
            'guitar' => 'music',
            'piano' => 'music',

            'football' => 'sports',
            'cricket' => 'sports',
            'basketball' => 'sports',
            'sports' => 'sports',

            'math' => 'mathematics',
            'mathematics' => 'mathematics',

            'science' => 'science',

            'drawing' => 'creative',
            'painting' => 'creative',
            'art' => 'creative',
            'art & design' => 'creative',

            'reading' => 'reading',
            'books' => 'reading',

            'puzzles' => 'chess',

            'business' => 'business',
            'gaming' => 'gaming',

            'gk' => 'general',
            'general knowledge' => 'general',

            'environment' => 'science',
        ];

        $mapInterestToTheme = function ($interestName) use (
            $themeAliases,
            $themes
        ) {
            $clean = strtolower(trim($interestName));

            if (isset($themeAliases[$clean])) {
                $clean = $themeAliases[$clean];
            }

            return isset($themes[$clean])
                ? $clean
                : 'general';
        };

        /*
        |--------------------------------------------------------------------------
        | DETERMINE ACTIVE THEME
        |--------------------------------------------------------------------------
        */
        $activeFocus = $request->query('focus');

        if ($activeFocus) {

            $themeKey = $mapInterestToTheme($activeFocus);
        } elseif (count($interests) === 1) {

            $themeKey = $mapInterestToTheme($interests[0]);
        } else {

            if ($hasChessInterest) {

                $themeKey = 'chess';
            } else {

                $themeKey = $mapInterestToTheme(
                    $aiProfile['visual_theme'] ?? 'general'
                );
            }
        }

        if (!isset($themes[$themeKey])) {
            $themeKey = 'general';
        }

        $themeConfig = config(
            'learning_themes.' . $themeKey
        );

        /*
        |--------------------------------------------------------------------------
        | GET EXISTING PENDING TASKS
        |--------------------------------------------------------------------------
        */
        $tasks = LearningTask::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GENERATE AI TASKS IF NONE EXIST
        |--------------------------------------------------------------------------
        */
        if ($tasks->isEmpty()) {

            $generatedData = $gemini->generateTasks(
                $interests,
                $preference->learning_goal ?? 'Practice and learn',
                $preference->experience_level ?? 'beginner',
                4
            );

            /*
            |--------------------------------------------------------------------------
            | CHECK GEMINI RESPONSE
            |--------------------------------------------------------------------------
            */
            if (
                $generatedData &&
                isset($generatedData['tasks']) &&
                is_array($generatedData['tasks'])
            ) {

                foreach ($generatedData['tasks'] as $task) {

                    /*
                    |--------------------------------------------------------------------------
                    | GET GAME TYPE FROM AI
                    |--------------------------------------------------------------------------
                    */
                    $gameType = strtolower(
                        trim($task['game_type'] ?? '')
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | FIND EXACT PLAYABLE GAME
                    |--------------------------------------------------------------------------
                    */
                    $game = null;

                    if (!empty($gameType)) {

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

                        // Exact matching game if available
                        'game_id' => $game?->id,

                        // Always store the AI requested game type
                        'game_type' => !empty($gameType)
                            ? $gameType
                            : null,

                        'title' => $task['title']
                            ?? 'Learning Quest',

                        'description' => $task['description']
                            ?? null,

                        'category' => $task['category']
                            ?? 'Quest',

                        'difficulty' => $task['difficulty']
                            ?? 'beginner',

                        'xp' => $task['xp']
                            ?? 20,

                        'status' => 'pending',
                    ]);
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD TASKS AGAIN
        |--------------------------------------------------------------------------
        */
        $tasks = LearningTask::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RENDER DASHBOARD
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
                'progress',
                'interests'
            )
        );
    }
}
