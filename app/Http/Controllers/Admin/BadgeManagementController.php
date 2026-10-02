<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Illuminate\Http\Request;

class BadgeManagementController extends Controller
{
    public function index()
    {
        $badges = Badge::latest()->paginate(10);

        return view('admin.badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin.badges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'icon' => 'nullable|string|max:100',

            'requirement_type' => [
                'required',
                'in:total_xp,completed_tasks',
            ],

            'requirement_value' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Badge::create($validated);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge created successfully.');
    }

    public function edit(Badge $badge)
    {
        return view('admin.badges.edit', compact('badge'));
    }

    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'description' => 'nullable|string',

            'icon' => 'nullable|string|max:100',

            'requirement_type' => [
                'required',
                'in:total_xp,completed_tasks',
            ],

            'requirement_value' => [
                'required',
                'integer',
                'min:1',
            ],

            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $badge->update($validated);

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge updated successfully.');
    }

    public function destroy(Badge $badge)
    {
        $badge->delete();

        return redirect()
            ->route('admin.badges.index')
            ->with('success', 'Badge deleted successfully.');
    }
}