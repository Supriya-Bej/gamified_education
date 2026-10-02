<?php

namespace App\Http\Controllers;

use App\Models\LearningTask;
use App\Models\QuestAttempt;
use App\Models\QuestContent;
use App\Services\GeminiService;
use App\Services\QuestRewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestController extends Controller
{
    private const PASS_MARK = 70;

    private function myTask($id): LearningTask
    {
        return LearningTask::where('id', $id)
            ->where('user_id', Auth::guard('student')->id())
            ->firstOrFail();
    }

    private function contentFor(LearningTask $task, GeminiService $gemini): ?QuestContent
    {
        $existing = QuestContent::where('task_id', $task->id)->first();
        if ($existing) {
            return $existing;
        }

        set_time_limit(120);

        $preference = Auth::guard('student')->user()->preferences;
        $interests = is_array($preference?->interests) ? $preference->interests : [];

        $data = $gemini->generateQuestContent(
            $task->title,
            (string) $task->description,
            (string) $task->difficulty,
            $interests
        );

        if (!$data) {
            return null;
        }

        return QuestContent::create([
            'task_id' => $task->id,
            'lesson' => $data['lesson'],
            'quiz' => $data['quiz'],
        ]);
    }

    /* Learn page */
    public function show($id, GeminiService $gemini)
    {
        $task = $this->myTask($id);

        if ($task->status === 'completed') {
            return redirect()->route('student.dashboard')
                ->with('info', 'This quest is already completed.');
        }

        $content = $this->contentFor($task, $gemini);

        if (!$content) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Could not prepare this quest right now. Please try again in a moment.');
        }

        return view('quest.learn', compact('task', 'content'));
    }

    /* Quiz page (correct answer browser e pathano hoy na) */
    public function quiz($id)
    {
        $task = $this->myTask($id);

        if ($task->status === 'completed') {
            return redirect()->route('student.dashboard');
        }

        $content = QuestContent::where('task_id', $task->id)->first();

        if (!$content) {
            return redirect()->route('student.quest.show', $task->id);
        }

        $questions = collect($content->quiz)
            ->map(fn ($q) => ['question' => $q['question'], 'options' => $q['options']])
            ->values()
            ->all();

        return view('quest.quiz', compact('task', 'questions'));
    }

    /* Grade + XP */
    public function submit(Request $request, $id, QuestRewardService $rewards)
    {
        $task = $this->myTask($id);
        $content = QuestContent::where('task_id', $task->id)->firstOrFail();
        $user = Auth::guard('student')->user();

        $quiz = $content->quiz;
        $answers = (array) $request->input('answers', []);

        $correct = 0;
        foreach ($quiz as $i => $q) {
            if (isset($answers[$i]) && (int) $answers[$i] === (int) $q['answer']) {
                $correct++;
            }
        }

        $total = count($quiz);
        $score = (int) round($correct / $total * 100);
        $passed = $score >= self::PASS_MARK;

        $attempt = DB::transaction(function () use ($task, $user, $rewards, $passed, $score, $correct, $total, $answers) {
            $fresh = LearningTask::where('id', $task->id)->lockForUpdate()->first();

            $xp = 0;
            if ($passed && $fresh->status !== 'completed') {
                $xp = (int) round($fresh->xp * $score / 100);
                $rewards->award($user, $fresh, $xp);
            }

            return QuestAttempt::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'score' => $score,
                'correct_count' => $correct,
                'total' => $total,
                'passed' => $passed,
                'xp_awarded' => $xp,
                'answers' => $answers,
            ]);
        });

        return redirect()->route('student.quest.result', $attempt->id);
    }

    /* Result page */
    public function result($attemptId)
    {
        $attempt = QuestAttempt::where('id', $attemptId)
            ->where('user_id', Auth::guard('student')->id())
            ->firstOrFail();

        $task = LearningTask::findOrFail($attempt->task_id);
        $content = QuestContent::where('task_id', $task->id)->firstOrFail();

        return view('quest.result', compact('attempt', 'task', 'content'));
    }
}