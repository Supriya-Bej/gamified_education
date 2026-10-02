<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;

class GameContentManagementController extends Controller
{
    public function index()
    {
        $games = Game::withCount('learningTasks')
            ->latest()
            ->paginate(10);

        return view('admin.game-content.index', compact('games'));
    }

    public function show(Game $game)
    {
        $game->load('learningTasks');

        return view('admin.game-content.show', compact('game'));
    }
}