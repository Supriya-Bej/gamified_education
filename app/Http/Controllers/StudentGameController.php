<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\LearningTask;
use App\Models\StudentProgress;
use Illuminate\Support\Facades\Auth;
use App\Services\BadgeService;
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


    public function complete(LearningTask $task, BadgeService $badgeService)
    {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first.'
            ], 401);
        }

        if ($task->user_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 403);
        }

        if ($task->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'This quest is already completed.'
            ], 409);
        }


        /*
    |--------------------------------------------------------------------------
    | Update Student Progress
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
    | Add Quest XP
    |--------------------------------------------------------------------------
    */

        $taskXp = (int) ($task->xp ?? 0);

        $progress->total_xp += $taskXp;

        $progress->completed_tasks += 1;


        /*
    |--------------------------------------------------------------------------
    | Calculate Level
    |--------------------------------------------------------------------------
    */

        $progress->level = max(
            1,
            (int) floor($progress->total_xp / 100) + 1
        );


        $progress->save();


        /*
    |--------------------------------------------------------------------------
    | Mark Quest Completed
    |--------------------------------------------------------------------------
    */

        $task->update([
            'status' => 'completed',
        ]);


        /*
    |--------------------------------------------------------------------------
    | Check Eligible Badges
    |--------------------------------------------------------------------------
    */

        $awardedBadges = $badgeService->checkAndAward($student);


        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'success' => true,

            'message' => 'Quest completed successfully!',

            'xp_earned' => $taskXp,

            'total_xp' => $progress->total_xp,

            'completed_tasks' => $progress->completed_tasks,

            'level' => $progress->level,

            'badges' => collect($awardedBadges)->map(function ($badge) {
                return [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon' => $badge->icon,
                ];
            })->values(),
        ]);
    }
}
