<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameManagementController extends Controller
{
    public function index()
    {
        $games = Game::latest()->paginate(10);

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'category' => 'required|string|max:100',

            'difficulty' => 'required|string|max:50',

            'game_type' => 'required|string|max:100',
        ]);

        Game::create($validated);

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game added successfully.');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'category' => 'required|string|max:100',

            'difficulty' => 'required|string|max:50',

            'game_type' => 'required|string|max:100',
        ]);

        $game->update($validated);

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game updated successfully.');
    }

    public function show(Game $game)
    {
        $game->load('learningTasks');

        return view('admin.games.show', compact('game'));
    }

    public function destroy(Game $game)
    {
        if ($game->learningTasks()->exists()) {
            return redirect()
                ->route('admin.games.index')
                ->with(
                    'error',
                    'This game cannot be deleted because learning tasks are connected to it.'
                );
        }

        $game->delete();

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game deleted successfully.');
    }
}
