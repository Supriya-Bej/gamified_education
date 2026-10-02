<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\LearningTask;
use Illuminate\Support\Facades\DB;

class GameEngineManagementController extends Controller
{
    public function index()
    {
        $engines = Game::query()
            ->whereNotNull('game_type')
            ->where('game_type', '!=', '')
            ->select('game_type')
            ->selectRaw('COUNT(*) as game_count')
            ->groupBy('game_type')
            ->orderBy('game_type')
            ->get();

        foreach ($engines as $engine) {

            $engine->task_count = LearningTask::where(
                'game_type',
                $engine->game_type
            )->count();

        }

        return view('admin.engines.index', compact('engines'));
    }

    public function show($gameType)
    {
        $games = Game::where('game_type', $gameType)
            ->latest()
            ->get();

        $tasks = LearningTask::with('user')
            ->where('game_type', $gameType)
            ->latest()
            ->get();

        return view('admin.engines.show', compact(
            'gameType',
            'games',
            'tasks'
        ));
    }
}