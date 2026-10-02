<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\LearningTask;
use App\Models\StudentProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentGameController extends Controller
{
    public function play(LearningTask $task)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        // Make sure this task belongs to logged-in student
        if ($task->user_id !== $student->id) {
            abort(403, 'You are not allowed to access this quest.');
        }

        // Task must have a game type
        if (empty($task->game_type)) {
            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'This quest does not have a game type yet.'
                );
        }

        // Find exact game using game_type
        $game = Game::whereRaw(
            'LOWER(game_type) = ?',
            [strtolower(trim($task->game_type))]
        )->first();

        // Game is not available
        if (!$game) {
            return redirect()
                ->route('student.dashboard')
                ->with(
                    'error',
                    'The required game "' . $task->game_type . '" is not available yet.'
                );
        }

        // Keep task connected with the correct game
        if ($task->game_id !== $game->id) {
            $task->update([
                'game_id' => $game->id,
            ]);
        }

        // Open actual game view
        $view = match (strtolower(trim($game->game_type))) {

            'arithmetic_speed'
                => 'student.games.math-speed',

            'creative_coding'
                => 'student.games.creative-coding',

            'sorting_challenge'
                => 'student.games.sorting-challenge',

            default
                => 'student.game',
        };

        return view($view, compact('game', 'task'));
    }


    public function complete(LearningTask $task)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        // Make sure task belongs to logged-in student
        if ($task->user_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        // Prevent completing the same quest twice
        if ($task->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'This quest is already completed.'
            ], 409);
        }


        /*
        |--------------------------------------------------------------------------
        | XP CALCULATION
        |--------------------------------------------------------------------------
        */

        $xp = (int) ($task->xp ?? 20);

        if ($xp <= 0) {
            $xp = 20;
        }


        /*
        |--------------------------------------------------------------------------
        | INTEREST / CATEGORY
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | task category = coding
        |
        | subject_xp:
        | {
        |     "coding": 40,
        |     "chess": 20,
        |     "music": 10
        | }
        |
        | After completing coding task:
        |
        | {
        |     "coding": 60,
        |     "chess": 20,
        |     "music": 10
        | }
        |
        |--------------------------------------------------------------------------
        */

        $interest = strtolower(
            trim($task->category ?? '')
        );

        if ($interest === '') {
            $interest = 'general';
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress = StudentProgress::firstOrCreate(
            [
                'user_id' => $student->id,
            ],
            [
                'total_xp' => 0,
                'level' => 1,
                'completed_tasks' => 0,
                'subject_xp' => [],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | EXISTING INTEREST XP
        |--------------------------------------------------------------------------
        */

        $subjectXp = $progress->subject_xp;

        if (!is_array($subjectXp)) {
            $subjectXp = [];
        }


        $currentInterestXp = (int) (
            $subjectXp[$interest] ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | ADD XP ONLY TO THIS INTEREST
        |--------------------------------------------------------------------------
        */

        $subjectXp[$interest] = $currentInterestXp + $xp;


        /*
        |--------------------------------------------------------------------------
        | UPDATE PROGRESS
        |--------------------------------------------------------------------------
        */

        $progress->total_xp = (int) $progress->total_xp + $xp;

        $progress->completed_tasks =
            (int) $progress->completed_tasks + 1;

        $progress->subject_xp = $subjectXp;


        /*
        |--------------------------------------------------------------------------
        | SIMPLE LEVEL CALCULATION
        |--------------------------------------------------------------------------
        |
        | Every 100 total XP = 1 level
        |
        | 0-99   => Level 1
        | 100-199 => Level 2
        | 200-299 => Level 3
        |
        |--------------------------------------------------------------------------
        */

        $progress->level =
            max(
                1,
                (int) floor($progress->total_xp / 100) + 1
            );


        $progress->save();


        /*
        |--------------------------------------------------------------------------
        | MARK TASK COMPLETED
        |--------------------------------------------------------------------------
        */

        $task->update([
            'status' => 'completed',
        ]);


        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'Quest completed successfully!',

            'xp_earned' => $xp,

            'interest' => $interest,

            'interest_xp' => $subjectXp[$interest],

            'total_xp' => $progress->total_xp,

            'completed_tasks' => $progress->completed_tasks,

            'level' => $progress->level,
        ]);
    }
}