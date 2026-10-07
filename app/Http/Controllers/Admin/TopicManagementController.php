<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicManagementController extends Controller
{
    public function index()
    {
        $topics = Topic::latest()->paginate(10);

        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        return view('admin.topics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:topics,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Topic::create($validated);

        return redirect()
            ->route('admin.topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:topics,name,' . $topic->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $topic->update($validated);

        return redirect()
            ->route('admin.topics.index')
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()
            ->route('admin.topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}