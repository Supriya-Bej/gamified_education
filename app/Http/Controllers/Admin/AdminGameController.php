<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class AdminGameController extends Controller
{
    /**
     * Show all games.
     */
    // public function index()
    // {
    //     $games = Game::latest()->get();

    //     return view('admin.games.index', compact('games'));
    // }

    /**
     * Show create game form.
     */
    // public function create()
    // {
    //     return view('admin.games.create');
    // }

    /**
     * Store a new game.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
        ]);

        Game::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'category' => strtolower(trim($validated['category'])),
            'difficulty' => $validated['difficulty'],
        ]);

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game created successfully.');
    }
}