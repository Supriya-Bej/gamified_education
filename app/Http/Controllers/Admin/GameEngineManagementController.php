<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\LearningTask;

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
            ->paginate(10)
            ->withQueryString();

        $taskCounts = LearningTask::query()
            ->whereIn('game_type', $engines->getCollection()->pluck('game_type'))
            ->select('game_type')
            ->selectRaw('COUNT(*) as task_count')
            ->groupBy('game_type')
            ->pluck('task_count', 'game_type');

        $engines->getCollection()->each(function ($engine) use ($taskCounts): void {
            $engine->task_count = $taskCounts[$engine->game_type] ?? 0;
        });

        return view('admin.engines.index', compact('engines'));
    }

    public function show($gameType)
    {
        $games = Game::where('game_type', $gameType)
            ->latest()
            ->paginate(10, ['*'], 'games_page')
            ->withQueryString();

        $tasks = LearningTask::with('user')
            ->where('game_type', $gameType)
            ->latest()
            ->paginate(10, ['*'], 'tasks_page')
            ->withQueryString();

        return view('admin.engines.show', compact(
            'gameType',
            'games',
            'tasks'
        ));
    }
}