<?php

namespace App\Http\Controllers;

use App\Models\LearningMaterial;
use App\Models\LearningMaterialProgress;
use App\Models\Topic;
use App\Services\GeminiService;
use App\Services\LearningMaterialProgressionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLearningMaterialController extends Controller
{
    public function index(LearningMaterialProgressionService $progression)
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

                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere(
                        'description',
                        'like',
                        '%'.$search.'%'
                    )
                    ->orWhere(
                        'topic',
                        'like',
                        '%'.$search.'%'
                    );

            });
        }

        $materials = $query
            ->latest()
            ->paginate(9)
            ->withQueryString();
        $unlockedMaterialIds = $progression->unlockedIds(
            $materials->getCollection(),
            $student->id
        );
        $materialProgress = LearningMaterialProgress::query()
            ->where('user_id', $student->id)
            ->whereIn('learning_material_id', $materials->getCollection()->modelKeys())
            ->get()
            ->keyBy('learning_material_id');

        return view(
            'student.learning-materials.index',
            compact(
                'student',
                'topics',
                'materials',
                'unlockedMaterialIds',
                'materialProgress'
            )
        );
    }

    public function show(
        LearningMaterial $learningMaterial,
        LearningMaterialProgressionService $progression
    ) {
        if (! $learningMaterial->is_published) {
            abort(404);
        }

        $student = Auth::guard('student')->user();
        if (! $progression->isUnlocked($learningMaterial, $student->id)) {
            $requiredLesson = $progression->blockedBy($learningMaterial, $student->id);

            return redirect()
                ->route('student.learning-materials.show', $requiredLesson)
                ->with('info', 'Complete the earlier lesson and pass its quiz to unlock this level.');
        }

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

        if (! $progress->started_at) {

            $progress->update([
                'started_at' => now(),
            ]);

        }

        $topic = $learningMaterial->topicRelation;
        $levelNumber = $progression->levelNumber($learningMaterial);
        $nextLearningMaterial = null;

        if ($progress->quiz_completed_at) {
            $nextLearningMaterial = $progression->nextLesson($learningMaterial);
        }

        return view(
            'student.learning-materials.show',
            compact(
                'learningMaterial',
                'topic',
                'progress',
                'levelNumber',
                'nextLearningMaterial'
            )
        );
    }

    public function complete(
        LearningMaterial $learningMaterial,
        LearningMaterialProgressionService $progression,
        GeminiService $gemini
    ) {
        $student = Auth::guard('student')->user();

        if (! $learningMaterial->is_published) {
            abort(404);
        }
        abort_unless($progression->isUnlocked($learningMaterial, $student->id), 403);

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

        if (! $progress->completed_at) {
            $progress->update(['completed_at' => now()]);
        }

        if (! $progress->quiz_data) {
            $questions = $gemini->generateLearningMaterialQuiz(
                $learningMaterial->title,
                $learningMaterial->topic,
                $learningMaterial->content
            );

            if ($questions === null) {
                return redirect()
                    ->route('student.learning-materials.show', $learningMaterial)
                    ->with('error', 'Your lesson progress was saved, but the quiz could not be generated. Please retry.');
            }

            $progress->update(['quiz_data' => ['questions' => $questions]]);
        }

        return redirect()
            ->route(
                'student.learning-materials.show',
                $learningMaterial
            )
            ->with(
                'success',
                'Lesson complete. Your quiz is ready!'
            );
    }

    public function generateQuiz(
        LearningMaterial $learningMaterial,
        LearningMaterialProgressionService $progression,
        GeminiService $gemini
    ) {
        $student = Auth::guard('student')->user();
        abort_unless($learningMaterial->is_published, 404);
        abort_unless($progression->isUnlocked($learningMaterial, $student->id), 403);

        $progress = LearningMaterialProgress::query()
            ->where('user_id', $student->id)
            ->where('learning_material_id', $learningMaterial->id)
            ->firstOrFail();

        if (! $progress->completed_at) {
            return redirect()
                ->route('student.learning-materials.show', $learningMaterial)
                ->with('error', 'Complete the lesson before generating its quiz.');
        }

        if (! $progress->quiz_data) {
            $questions = $gemini->generateLearningMaterialQuiz(
                $learningMaterial->title,
                $learningMaterial->topic,
                $learningMaterial->content
            );

            if ($questions === null) {
                return redirect()
                    ->route('student.learning-materials.show', $learningMaterial)
                    ->with('error', 'The quiz could not be generated. Please try again.');
            }

            $progress->update(['quiz_data' => ['questions' => $questions]]);
        }

        return redirect()
            ->route('student.learning-materials.show', $learningMaterial)
            ->with('success', 'Your lesson quiz is ready.');
    }

    public function submitQuiz(
        Request $request,
        LearningMaterial $learningMaterial,
        LearningMaterialProgressionService $progression
    ) {
        $student = Auth::guard('student')->user();
        abort_unless($learningMaterial->is_published, 404);
        abort_unless($progression->isUnlocked($learningMaterial, $student->id), 403);

        $validated = $request->validate([
            'answers' => ['required', 'array'],
            'answers.*' => ['required', 'string', 'max:500'],
        ]);

        $progress = LearningMaterialProgress::query()
            ->where('user_id', $student->id)
            ->where('learning_material_id', $learningMaterial->id)
            ->firstOrFail();
        abort_unless($progress->completed_at, 403);
        abort_if($progress->quiz_completed_at, 409, 'This lesson quiz has already been passed.');
        abort_unless($progress->quiz_data && is_array($progress->quiz_data['questions'] ?? null), 409);

        $questions = $progress->quiz_data['questions'];
        abort_unless($questions !== [], 409);
        abort_unless(count($validated['answers']) === count($questions), 422);

        $correct = 0;
        foreach ($questions as $index => $question) {
            if (
                ! is_array($question)
                || ! isset($question['answer'], $validated['answers'][$index])
            ) {
                abort(409, 'The saved lesson quiz is invalid. Please contact support.');
            }

            if (trim($validated['answers'][$index]) === trim((string) $question['answer'])) {
                $correct++;
            }
        }

        $score = (int) round($correct / count($questions) * 100);
        $passed = $score >= 70;
        $progress->update([
            'quiz_score' => $score,
            'quiz_completed_at' => $progress->quiz_completed_at ?? ($passed ? now() : null),
        ]);

        return redirect()
            ->route('student.learning-materials.show', $learningMaterial)
            ->with('quiz_result', [
                'score' => $score,
                'passed' => $passed,
                'correct' => $correct,
                'total' => count($questions),
            ]);
    }
}
