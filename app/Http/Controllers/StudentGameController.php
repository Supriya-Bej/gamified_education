<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameEngine;
use App\Models\LearningTask;
use App\Models\StudentProgress;
use App\Services\BadgeService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentGameController extends Controller
{
    public function play(LearningTask $task, GeminiService $gemini)
    {
        $student = Auth::guard('student')->user();

        if (! $student) {
            return redirect()
                ->route('login')
                ->with('error', 'Please login first.');
        }

        // Check task ownership
        if ((int) $task->user_id !== (int) $student->id) {
            abort(403, 'You are not allowed to access this quest.');
        }

        if ($task->review_status !== 'approved') {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This game quest is not approved yet.');
        }

        if ($task->status !== 'pending') {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This quest is no longer available.');
        }

        if (empty($task->game_type)) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This quest does not have a game type yet.');
        }

        $interests = $student->preferences?->interests ?? [];
        if (is_string($interests) && trim($interests) !== '') {
            $interests = [trim($interests)];
        }
        if (! is_array($interests)) {
            $interests = [];
        }

        $game = $gemini->getPlayableGamesForInterests($interests)
            ->first(fn (Game $availableGame) => (int) $availableGame->id === (int) $task->game_id
                && strtolower(trim($availableGame->game_type)) === strtolower(trim($task->game_type))
            );

        if (! $game) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This game is not available for your interests.');
        }

        // Find the registered engine
        $engine = GameEngine::where(
            'engine_key',
            strtolower(trim($game->game_type))
        )->first();

        if (! $engine || ! $engine->is_enabled || empty($engine->view_name)) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'This game engine is not available yet.');
        }

        // Verify the configured Blade view exists
        if (! view()->exists($engine->view_name)) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'The game view is not configured correctly.');
        }

        return view($engine->view_name, compact('game', 'task'));
    }

    public function complete(
        Request $request,
        LearningTask $task,
        BadgeService $badgeService,
        GeminiService $gemini
    ) {
        $student = Auth::guard('student')->user();

        if (! $student) {
            return response()->json([
                'success' => false,
                'message' => 'Please login first.',
            ], 401);
        }

        if ($task->user_id !== $student->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 403);
        }

        if ($task->review_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'This game quest is not approved yet.',
            ], 403);
        }

        $validated = $request->validate([
            'answers' => ['sometimes', 'required', 'array', 'max:100'],
            'answers.*' => ['nullable', 'string', 'max:500'],
            'code' => ['sometimes', 'required', 'string', 'max:12000'],
        ]);

        if ($task->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => $task->status === 'completed'
                    ? 'This quest is already completed.'
                    : 'This quest is no longer available.',
            ], 409);
        }

        $interests = $student->preferences?->interests ?? [];
        if (is_string($interests) && trim($interests) !== '') {
            $interests = [trim($interests)];
        }
        if (! is_array($interests)) {
            $interests = [];
        }

        $game = $gemini->getPlayableGamesForInterests($interests)
            ->first(fn (Game $availableGame) => (int) $availableGame->id === (int) $task->game_id
                && strtolower(trim($availableGame->game_type)) === strtolower(trim((string) $task->game_type))
            );

        if (! $game) {
            return response()->json([
                'success' => false,
                'message' => 'This quest is not playable for your registered interests.',
            ], 403);
        }

        [$score, $correct, $total] = $this->gradeSubmission($task, $validated);
        $passed = $total > 0 && $correct === $total;

        $result = DB::transaction(function () use (
            $task,
            $student,
            $score,
            $passed
        ): array {
            $lockedTask = LearningTask::query()
                ->whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedTask->status === 'completed') {
                return [
                    'already_completed' => true,
                    'xp_earned' => 0,
                    'progress' => null,
                ];
            }

            if ($lockedTask->review_status !== 'approved') {
                return [
                    'not_approved' => true,
                    'already_completed' => false,
                    'xp_earned' => 0,
                    'progress' => null,
                ];
            }

            if (! $passed) {
                return [
                    'already_completed' => false,
                    'xp_earned' => 0,
                    'progress' => null,
                ];
            }

            StudentProgress::firstOrCreate(
                ['user_id' => $student->id],
                [
                    'total_xp' => 0,
                    'level' => 1,
                    'completed_tasks' => 0,
                    'subject_xp' => [],
                ]
            );

            $progress = StudentProgress::query()
                ->where('user_id', $student->id)
                ->lockForUpdate()
                ->firstOrFail();

            $taskXp = (int) ($lockedTask->xp ?? 0);
            $earnedXp = (int) round($taskXp * $score / 100);

            $progress->total_xp += $earnedXp;
            $progress->completed_tasks += 1;
            $progress->level = max(
                1,
                (int) floor($progress->total_xp / 100) + 1
            );
            $progress->save();

            $lockedTask->update(['status' => 'completed']);

            return [
                'already_completed' => false,
                'xp_earned' => $earnedXp,
                'progress' => $progress,
            ];
        });

        if ($result['already_completed']) {
            return response()->json([
                'success' => false,
                'message' => 'This quest is already completed.',
            ], 409);
        }

        if ($result['not_approved'] ?? false) {
            return response()->json([
                'success' => false,
                'message' => 'This challenge is not approved yet.',
            ], 403);
        }

        if (! $passed) {
            return response()->json([
                'success' => true,
                'passed' => false,
                'message' => 'Not quite yet. Review your answers and try again. No XP was awarded.',
                'score' => $score,
                'correct_answers' => $correct,
                'total_questions' => $total,
                'xp_earned' => 0,
            ]);
        }

        $awardedBadges = $badgeService->checkAndAward($student);
        /** @var StudentProgress $progress */
        $progress = $result['progress'];

        return response()->json([
            'success' => true,
            'passed' => true,
            'message' => 'Quest completed successfully!',
            'score' => $score,
            'correct_answers' => $correct,
            'total_questions' => $total,
            'xp_earned' => $result['xp_earned'],
            'total_xp' => $progress->total_xp,
            'completed_tasks' => $progress->completed_tasks,
            'level' => $progress->level,
            'badges' => collect($awardedBadges)->map(function ($badge) {
                return [
                    'id' => $badge->id,
                    'name' => $badge->name,
                    'description' => $badge->description,
                    'icon' => $badge->icon,
                ];
            })->values(),
        ]);
    }

    /**
     * Grade submissions against the answer data stored on the learning task.
     *
     * @return array{0: int, 1: int, 2: int}
     */
    private function gradeSubmission(LearningTask $task, array $submission): array
    {
        $gameData = $task->game_data;
        $answers = $submission['answers'] ?? [];

        if (! is_array($gameData)) {
            abort(422, 'This quest has no valid answer data.');
        }

        if ($task->game_type === 'arithmetic_speed') {
            $questions = $gameData['questions'] ?? [];
            abort_unless(is_array($questions) && $questions !== [], 422, 'This math quest has no questions.');
            abort_unless(count($answers) === count($questions), 422, 'Submit an answer for every math question.');

            $correct = 0;
            foreach ($questions as $index => $question) {
                if (
                    is_array($question)
                    && isset($answers[$index], $question['answer'])
                    && trim((string) $answers[$index]) !== ''
                    && trim((string) $answers[$index]) === trim((string) $question['answer'])
                ) {
                    $correct++;
                }
            }

            return [(int) round($correct / count($questions) * 100), $correct, count($questions)];
        }

        if ($task->game_type === 'sorting_challenge') {
            $items = $gameData['items'] ?? [];
            abort_unless(is_array($items) && $items !== [], 422, 'This sorting quest has no items.');
            abort_unless(count($answers) === count($items), 422, 'Submit a category for every item.');

            $correct = 0;
            foreach ($items as $index => $item) {
                if (
                    is_array($item)
                    && isset($answers[$index], $item['correct_category'])
                    && trim((string) $answers[$index]) === trim((string) $item['correct_category'])
                ) {
                    $correct++;
                }
            }

            return [(int) round($correct / count($items) * 100), $correct, count($items)];
        }

        if ($task->game_type === 'chess_tactics') {
            $puzzles = $gameData['puzzles'] ?? [];
            abort_unless(is_array($puzzles) && $puzzles !== [], 422, 'This chess quest has no puzzles.');
            abort_unless(count($answers) === count($puzzles), 422, 'Submit an answer for every chess puzzle.');

            $correct = 0;
            foreach ($puzzles as $index => $puzzle) {
                if (
                    is_array($puzzle)
                    && isset($answers[$index], $puzzle['answer'])
                    && trim((string) $answers[$index]) === trim((string) $puzzle['answer'])
                ) {
                    $correct++;
                }
            }

            return [(int) round($correct / count($puzzles) * 100), $correct, count($puzzles)];
        }

        if (in_array($task->game_type, [
            'art_color_theory',
            'music_note_rhythm',
            'science_quiz',
            'sports_quiz',
            'reading_quiz',
            'puzzles_quiz',
            'technology_quiz',
            'general_knowledge_quiz',
        ], true)) {
            $questions = $gameData['questions'] ?? [];
            abort_unless(is_array($questions) && $questions !== [], 422, 'This quest has no questions.');
            abort_unless(count($answers) === count($questions), 422, 'Submit an answer for every question.');

            $correct = 0;
            foreach ($questions as $index => $question) {
                if (
                    is_array($question)
                    && isset($answers[$index], $question['answer'])
                    && trim((string) $answers[$index]) !== ''
                    && trim((string) $answers[$index]) === trim((string) $question['answer'])
                ) {
                    $correct++;
                }
            }

            return [(int) round($correct / count($questions) * 100), $correct, count($questions)];
        }

        if ($task->game_type === 'creative_coding') {
            $code = trim((string) ($submission['code'] ?? ''));
            $codeWithoutComments = preg_replace(
                '/\/\*.*?\*\/|(?<!:)\/\/[^\r\n]*/s',
                '',
                $code
            ) ?? '';
            $hasCanvasDrawing = preg_match(
                '/\b(?:fillRect|strokeRect|fillText|strokeText|beginPath|arc|lineTo|moveTo)\s*\(/',
                $codeWithoutComments
            ) === 1;

            return [$hasCanvasDrawing ? 100 : 0, $hasCanvasDrawing ? 1 : 0, 1];
        }

        abort(422, 'This game type does not support submissions yet.');
    }
}
