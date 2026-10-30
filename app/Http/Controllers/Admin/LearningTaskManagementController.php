<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningTask;
use App\Services\GeminiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LearningTaskManagementController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'review_status' => ['nullable', Rule::in(['pending_review', 'approved', 'rejected', 'all'])],
        ]);
        $reviewStatus = $validated['review_status'] ?? 'pending_review';

        $tasks = LearningTask::with([
            'user',
            'game',
        ])
            ->when($reviewStatus !== 'all', fn ($query) => $query->where('review_status', $reviewStatus))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.tasks.index', compact('tasks', 'reviewStatus'));
    }

    public function show(LearningTask $task): View
    {
        $task->load([
            'user',
            'game',
            'reviewer',
        ]);

        return view('admin.tasks.show', compact('task'));
    }

    public function review(
        Request $request,
        LearningTask $task,
        GeminiService $gemini
    ): RedirectResponse {
        abort_unless(in_array($task->review_status, ['pending_review', 'rejected'], true), 409);

        $validated = $request->validate([
            'decision' => ['required', Rule::in(['save', 'approve'])],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'difficulty' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'xp' => ['required', 'integer', 'min:10', 'max:50'],
            'game_data' => ['required', 'json'],
            'review_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $gameData = json_decode($validated['game_data'], true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($gameData) || ! $gemini->isValidGameData((string) $task->game_type, $gameData)) {
            throw ValidationException::withMessages([
                'game_data' => 'The game quest data does not match the selected game format.',
            ]);
        }

        DB::transaction(function () use ($request, $task, $validated, $gameData): void {
            $lockedTask = LearningTask::query()
                ->whereKey($task->id)
                ->lockForUpdate()
                ->firstOrFail();
            abort_unless(in_array($lockedTask->review_status, ['pending_review', 'rejected'], true), 409);

            $lockedTask->fill([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'difficulty' => $validated['difficulty'],
                'xp' => $validated['xp'],
                'game_data' => $gameData,
                'review_note' => $validated['review_note'] ?? null,
            ]);

            if ($validated['decision'] === 'approve') {
                $lockedTask->review_status = 'approved';
                $lockedTask->reviewed_by = $request->user('admin')->id;
                $lockedTask->reviewed_at = now();
            } else {
                $lockedTask->review_status = 'pending_review';
                $lockedTask->reviewed_by = null;
                $lockedTask->reviewed_at = null;
            }

            $lockedTask->save();
        });

        $message = $validated['decision'] === 'approve'
            ? 'Game quest approved and is now available to the student.'
            : 'Game quest changes saved and returned to the review queue.';

        return redirect()
            ->route('admin.tasks.show', $task)
            ->with('success', $message);
    }

    public function reject(Request $request, LearningTask $task): RedirectResponse
    {
        abort_unless(in_array($task->review_status, ['pending_review', 'rejected'], true), 409);

        $validated = $request->validate([
            'review_note' => ['required', 'string', 'max:1000'],
        ]);

        $task->update([
            'review_status' => 'rejected',
            'review_note' => $validated['review_note'],
            'reviewed_by' => $request->user('admin')->id,
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route('admin.tasks.show', $task)
            ->with('success', 'Game quest rejected.');
    }
}
