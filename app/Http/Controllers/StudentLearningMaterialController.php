<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\LearningMaterialProgress;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLearningMaterialController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $topics = Topic::where('is_active', true)
            ->orderBy('name')
            ->get();

        $query = LearningMaterial::where('is_published', true);

        // Topic filter
        if (request()->filled('topic')) {
            $query->where('topic_id', request('topic'));
        }

        // Search
        if (request()->filled('search')) {

            $search = request('search');

            $query->where(function ($q) use ($search) {

                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere(
                        'description',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'topic',
                        'like',
                        '%' . $search . '%'
                    );

            });
        }

        $materials = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view(
            'student.learning-materials.index',
            compact(
                'student',
                'topics',
                'materials'
            )
        );
    }


    public function show(LearningMaterial $learningMaterial)
    {
        if (!$learningMaterial->is_published) {
            abort(404);
        }

        $student = Auth::guard('student')->user();

        /*
        |--------------------------------------------------------------------------
        | Create / Get Student Progress
        |--------------------------------------------------------------------------
        */

        $progress = LearningMaterialProgress::firstOrCreate(
            [
                'user_id' => $student->id,
                'learning_material_id' => $learningMaterial->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | If old progress exists but started_at is empty
        |--------------------------------------------------------------------------
        */

        if (!$progress->started_at) {

            $progress->update([
                'started_at' => now(),
            ]);

        }

        $topic = $learningMaterial->topicRelation;

        return view(
            'student.learning-materials.show',
            compact(
                'learningMaterial',
                'topic',
                'progress'
            )
        );
    }


    public function complete(LearningMaterial $learningMaterial)
    {
        $student = Auth::guard('student')->user();

        if (!$learningMaterial->is_published) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Get / Create Progress
        |--------------------------------------------------------------------------
        */

        $progress = LearningMaterialProgress::firstOrCreate(
            [
                'user_id' => $student->id,
                'learning_material_id' => $learningMaterial->id,
            ],
            [
                'started_at' => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Mark Completed
        |--------------------------------------------------------------------------
        */

        if (!$progress->completed_at) {

            $progress->update([
                'completed_at' => now(),
            ]);

        }

        return redirect()
            ->route(
                'student.learning-materials.show',
                $learningMaterial
            )
            ->with(
                'success',
                'Learning material completed successfully!'
            );
    }
}