<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectChallenge;
use App\Models\ProjectChallengeSubmission;
use App\Models\StudentProgress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProjectChallengeManagementController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'review_status' => ['nullable', Rule::in(['pending_review', 'approved', 'rejected', 'all'])],
            'submission_status' => ['nullable', Rule::in(['submitted', 'revision_requested', 'accepted', 'none', 'all'])],
        ]);

        $reviewStatus = $validated['review_status'] ?? 'all';
        $submissionStatus = $validated['submission_status'] ?? 'all';
        $challenges = ProjectChallenge::query()
            ->with(['student:id,name,email', 'latestSubmission'])
            ->when($reviewStatus !== 'all', fn ($query) => $query->where('review_status', $reviewStatus))
            ->when($submissionStatus === 'none', fn ($query) => $query->whereDoesntHave('latestSubmission'))
            ->when(in_array($submissionStatus, ['submitted', 'revision_requested', 'accepted'], true), function ($query) use ($submissionStatus): void {
                $query->whereHas('latestSubmission', fn ($submissionQuery) => $submissionQuery->where('status', $submissionStatus));
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.project-challenges.index', compact(
            'challenges',
            'reviewStatus',
            'submissionStatus'
        ));
    }

    public function show(ProjectChallenge $projectChallenge): View
    {
        $projectChallenge->load([
            'student:id,name,email',
            'reviewer:id,name',
            'submissions' => fn ($query) => $query->with('reviewer:id,name')->orderByDesc('id'),
        ]);

        return view('admin.project-challenges.show', compact('projectChallenge'));
    }

    public function review(Request $request, ProjectChallenge $projectChallenge): RedirectResponse
    {
        abort_unless(in_array($projectChallenge->review_status, ['pending_review', 'rejected'], true), 409);

        $validated = $request->validate([
            'decision' => ['required', Rule::in(['save', 'approve', 'reject'])],
            'interest' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'project_brief' => ['required', 'string', 'max:5000'],
            'objectives' => ['required', 'array', 'min:3', 'max:5'],
            'objectives.*' => ['nullable', 'string', 'max:500'],
            'deliverables' => ['required', 'array', 'min:2', 'max:4'],
            'deliverables.*' => ['nullable', 'string', 'max:500'],
            'difficulty' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],
            'xp' => ['required', 'integer', 'min:20', 'max:100'],
            'review_note' => ['nullable', 'required_if:decision,reject', 'string', 'max:1000'],
        ]);
        $validated['objectives'] = array_values(array_filter(
            array_map(fn ($objective) => trim((string) $objective), $validated['objectives']),
            fn (string $objective) => $objective !== ''
        ));
        $validated['deliverables'] = array_values(array_filter(
            array_map(fn ($deliverable) => trim((string) $deliverable), $validated['deliverables']),
            fn (string $deliverable) => $deliverable !== ''
        ));
        if (count($validated['objectives']) < 3 || count($validated['objectives']) > 5) {
            throw ValidationException::withMessages([
                'objectives' => 'Provide between 3 and 5 project objectives.',
            ]);
        }
        if (count($validated['deliverables']) < 2 || count($validated['deliverables']) > 4) {
            throw ValidationException::withMessages([
                'deliverables' => 'Provide between 2 and 4 project deliverables.',
            ]);
        }

        DB::transaction(function () use ($request, $projectChallenge, $validated): void {
            $challenge = ProjectChallenge::query()
                ->whereKey($projectChallenge->id)
                ->lockForUpdate()
                ->firstOrFail();
            abort_unless(in_array($challenge->review_status, ['pending_review', 'rejected'], true), 409);

            $challenge->fill([
                'interest' => trim($validated['interest']),
                'title' => trim($validated['title']),
                'project_brief' => trim($validated['project_brief']),
                'objectives' => array_values(array_map('trim', $validated['objectives'])),
                'deliverables' => array_values(array_map('trim', $validated['deliverables'])),
                'difficulty' => $validated['difficulty'],
                'xp' => $validated['xp'],
                'review_note' => $validated['review_note'] ?? null,
            ]);

            if ($validated['decision'] === 'approve') {
                $challenge->review_status = 'approved';
                $challenge->reviewed_by = $request->user('admin')->id;
                $challenge->reviewed_at = now();
            } elseif ($validated['decision'] === 'reject') {
                $challenge->review_status = 'rejected';
                $challenge->reviewed_by = $request->user('admin')->id;
                $challenge->reviewed_at = now();
            } else {
                $challenge->review_status = 'pending_review';
                $challenge->reviewed_by = null;
                $challenge->reviewed_at = null;
            }

            $challenge->save();
        });

        $message = match ($validated['decision']) {
            'approve' => 'Project challenge approved and published to the student.',
            'reject' => 'Project challenge rejected.',
            default => 'Project challenge edits saved and left in the review queue.',
        };

        return redirect()
            ->route('admin.project-challenges.show', $projectChallenge)
            ->with('success', $message);
    }

    public function reviewSubmission(
        Request $request,
        ProjectChallenge $projectChallenge,
        ProjectChallengeSubmission $submission
    ): RedirectResponse {
        abort_unless((int) $submission->project_challenge_id === (int) $projectChallenge->id, 404);
        abort_unless($submission->status === 'submitted', 409);

        $validated = $request->validate([
            'decision' => ['required', Rule::in(['accept', 'request_revision'])],
            'review_note' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $projectChallenge, $submission, $validated): void {
            $challenge = ProjectChallenge::query()
                ->whereKey($projectChallenge->id)
                ->lockForUpdate()
                ->firstOrFail();
            $lockedSubmission = ProjectChallengeSubmission::query()
                ->whereKey($submission->id)
                ->lockForUpdate()
                ->firstOrFail();
            abort_unless($lockedSubmission->status === 'submitted', 409);
            abort_unless($challenge->review_status === 'approved', 409);
            abort_if($challenge->xp_awarded_at !== null, 409);

            $lockedSubmission->update([
                'status' => $validated['decision'] === 'accept' ? 'accepted' : 'revision_requested',
                'review_note' => trim($validated['review_note']),
                'reviewed_by' => $request->user('admin')->id,
                'reviewed_at' => now(),
            ]);

            if ($validated['decision'] === 'accept') {
                $progress = StudentProgress::query()->firstOrCreate(
                    ['user_id' => $challenge->user_id],
                    ['total_xp' => 0, 'level' => 1, 'completed_tasks' => 0]
                );
                $progress = StudentProgress::query()
                    ->whereKey($progress->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                $progress->total_xp += $challenge->xp;
                $progress->completed_tasks += 1;
                $progress->level = max(1, (int) floor($progress->total_xp / 100) + 1);
                $progress->save();
                $challenge->xp_awarded_at = now();
                $challenge->save();
            }
        });

        $message = $validated['decision'] === 'accept'
            ? 'Submission accepted and XP awarded.'
            : 'Revision requested. The student can submit an updated project.';

        return redirect()
            ->route('admin.project-challenges.show', $projectChallenge)
            ->with('success', $message);
    }
}
