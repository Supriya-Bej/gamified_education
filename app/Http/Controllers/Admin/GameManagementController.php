<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GameManagementController extends Controller
{
    public function index()
    {
        $games = Game::query()
            ->withCount('learningTasks')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $engines = $this->playableEngines();
        $categories = $engines
            ->flatMap(fn (GameEngine $engine) => $engine->supported_interests ?? [])
            ->unique()
            ->sort()
            ->values();

        return view('admin.games.create', compact('engines', 'categories'));
    }

    public function store(Request $request)
    {
        $engines = $this->playableEngines();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', 'string', 'max:100'],
            'difficulty' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'game_type' => ['required', Rule::in($engines->pluck('engine_key')->all())],
        ]);

        $engine = $engines->firstWhere('engine_key', $validated['game_type']);
        if (! $engine || ! in_array($validated['category'], $engine->supported_interests ?? [], true)) {
            throw ValidationException::withMessages([
                'category' => 'Choose an interest supported by the selected playable game engine.',
            ]);
        }

        $category = strtolower(trim($validated['category']));
        $subjectName = Str::title(str_replace('_', ' ', $category));
        $subjectId = DB::table('subjects')
            ->whereRaw('LOWER(name) = ?', [strtolower($subjectName)])
            ->value('id');

        if (! $subjectId) {
            $subjectId = DB::table('subjects')->insertGetId([
                'name' => $subjectName,
                'description' => $subjectName.' learning activities.',
                'category' => $subjectName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Game::create([
            'subject_id' => $subjectId,
            'name' => trim($validated['name']),
            'description' => $validated['description'] ?? null,
            'category' => $category,
            'difficulty' => $validated['difficulty'],
            'game_type' => $validated['game_type'],
        ]);

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Game added successfully.');
    }

    public function edit(Game $game)
    {
        $engines = $this->playableEngines();
        $categories = $engines
            ->flatMap(fn (GameEngine $engine) => $engine->supported_interests ?? [])
            ->unique()
            ->sort()
            ->values();

        return view('admin.games.edit', compact('game', 'engines', 'categories'));
    }

    public function update(Request $request, Game $game)
    {
        $engines = $this->playableEngines();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', 'string', 'max:100'],
            'difficulty' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'game_type' => ['required', Rule::in($engines->pluck('engine_key')->all())],
        ]);

        $engine = $engines->firstWhere('engine_key', $validated['game_type']);
        if (! $engine || ! in_array($validated['category'], $engine->supported_interests ?? [], true)) {
            throw ValidationException::withMessages([
                'category' => 'Choose an interest supported by the selected playable game engine.',
            ]);
        }

        $category = strtolower(trim($validated['category']));
        $subjectName = Str::title(str_replace('_', ' ', $category));
        $subjectId = DB::table('subjects')
            ->whereRaw('LOWER(name) = ?', [strtolower($subjectName)])
            ->value('id');

        if (! $subjectId) {
            $subjectId = DB::table('subjects')->insertGetId([
                'name' => $subjectName,
                'description' => $subjectName.' learning activities.',
                'category' => $subjectName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $game->update([
            'subject_id' => $subjectId,
            'name' => trim($validated['name']),
            'description' => $validated['description'] ?? null,
            'category' => $category,
            'difficulty' => $validated['difficulty'],
            'game_type' => $validated['game_type'],
        ]);

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

    private function playableEngines()
    {
        return GameEngine::query()
            ->where('is_enabled', true)
            ->whereNotNull('view_name')
            ->where('view_name', '!=', '')
            ->orderBy('name')
            ->get()
            ->filter(fn (GameEngine $engine) => view()->exists($engine->view_name))
            ->values();
    }
}
