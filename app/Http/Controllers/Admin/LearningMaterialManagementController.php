<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Topic;

class LearningMaterialManagementController extends Controller
{
    /**
     * Show all learning materials.
     */
    public function index()
    {
        $materials = LearningMaterial::latest()->paginate(10);

        return view(
            'admin.learning-materials.index',
            compact('materials')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $topics = Topic::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.learning-materials.create',
            compact('topics')
        );
    }

    /**
     * Store new learning material.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'topic_id' => [
                'required',
                'exists:topics,id',
            ],

            'description' => 'nullable|string',

            'content' => 'required|string',

            'difficulty' => [
                'required',
                'in:beginner,intermediate,advanced',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_published' => 'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Get Topic Name
        |--------------------------------------------------------------------------
        | We are keeping the old "topic" column for now.
        | topic_id is the new database relationship.
        */

        $topic = Topic::findOrFail($validated['topic_id']);

        $validated['topic'] = $topic->name;

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            $validated['image'] = $request
                ->file('image')
                ->store('learning-materials', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Published Status
        |--------------------------------------------------------------------------
        */

        $validated['is_published'] = $request->boolean('is_published');

        /*
        |--------------------------------------------------------------------------
        | Create Learning Material
        |--------------------------------------------------------------------------
        */

        LearningMaterial::create($validated);

        return redirect()
            ->route('admin.learning-materials.index')
            ->with(
                'success',
                'Learning material created successfully.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(LearningMaterial $learningMaterial)
    {
        $topics = Topic::where('is_active', true)
            ->orderBy('name')
            ->get();

        // If the material's current topic is inactive,
        // include it so it remains visible in the edit dropdown.
        if ($learningMaterial->topic_id) {

            $currentTopic = Topic::find($learningMaterial->topic_id);

            if (
                $currentTopic &&
                !$topics->contains('id', $currentTopic->id)
            ) {
                $topics->push($currentTopic);
            }
        }

        return view(
            'admin.learning-materials.edit',
            compact('learningMaterial', 'topics')
        );
    }

    /**
     * Update learning material.
     */
    public function update(Request $request, LearningMaterial $learningMaterial)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'topic_id' => 'required|exists:topics,id',

            'description' => 'nullable|string',

            'content' => 'required|string',

            'difficulty' => 'required|in:beginner,intermediate,advanced',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'is_published' => 'nullable|boolean',
        ]);

        // Get selected topic
        $topic = Topic::findOrFail($validated['topic_id']);

        /*
        |--------------------------------------------------------------------------
        | Prepare data
        |--------------------------------------------------------------------------
        */

        $learningMaterial->title = $validated['title'];

        $learningMaterial->topic_id = $validated['topic_id'];

        // Keep old topic column synchronized
        $learningMaterial->topic = $topic->name;

        $learningMaterial->description = $validated['description'] ?? null;

        $learningMaterial->content = $validated['content'];

        $learningMaterial->difficulty = $validated['difficulty'];

        $learningMaterial->is_published = $request->boolean('is_published');

        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if ($learningMaterial->image) {

                Storage::disk('public')
                    ->delete($learningMaterial->image);
            }

            $learningMaterial->image = $request
                ->file('image')
                ->store('learning-materials', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Save
        |--------------------------------------------------------------------------
        */

        $learningMaterial->save();

        return redirect()
            ->route('admin.learning-materials.index')
            ->with(
                'success',
                'Learning material updated successfully.'
            );
    }

    /**
     * Delete learning material.
     */
    public function destroy(
        LearningMaterial $learningMaterial
    ) {
        if ($learningMaterial->image) {
            Storage::disk('public')
                ->delete($learningMaterial->image);
        }

        $learningMaterial->delete();

        return redirect()
            ->route('admin.learning-materials.index')
            ->with(
                'success',
                'Learning material deleted successfully.'
            );
    }
}
