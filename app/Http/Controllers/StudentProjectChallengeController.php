<?php

namespace App\Http\Controllers;

use App\Models\ProjectChallenge;
use App\Models\ProjectChallengeSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentProjectChallengeController extends Controller
{
    public function show(ProjectChallenge $projectChallenge): View
    {
        abort_unless((int) $projectChallenge->user_id === (int) Auth::guard('student')->id(), 404);
        abort_unless($projectChallenge->review_status === 'approved', 404);

        $projectChallenge->load([
            'submissions' => fn ($query) => $query->orderByDesc('id'),
        ]);

        return view('student.project-challenges.show', compact('projectChallenge'));
    }

    public function submit(Request $request, ProjectChallenge $projectChallenge): RedirectResponse
    {
        $student = Auth::guard('student')->user();
        abort_unless((int) $projectChallenge->user_id === (int) $student->id, 404);
        abort_unless($projectChallenge->review_status === 'approved', 404);
        abort_if($projectChallenge->xp_awarded_at !== null, 409);

        $validated = $request->validate([
            'project_url' => ['required_without:evidence_image', 'nullable', 'url', 'max:2048'],
            'evidence_image' => ['required_without:project_url', 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'reflection' => ['required', 'string', 'min:30', 'max:3000'],
        ]);

        DB::transaction(function () use ($projectChallenge, $student, $request, $validated): void {
            $challenge = ProjectChallenge::query()
                ->whereKey($projectChallenge->id)
                ->lockForUpdate()
                ->firstOrFail();
            abort_unless($challenge->review_status === 'approved', 409);
            abort_if($challenge->xp_awarded_at !== null, 409);

            $latestSubmission = $challenge->submissions()->orderByDesc('id')->first();
            abort_if($latestSubmission?->status === 'submitted', 409);
            abort_if($latestSubmission?->status === 'accepted', 409);

            $imagePath = null;
            if ($request->hasFile('evidence_image')) {
                $imagePath = $request->file('evidence_image')->store('project-submissions', 'public');
                if ($imagePath === false) {
                    throw new \RuntimeException('Unable to store project evidence image.');
                }
            }

            ProjectChallengeSubmission::create([
                'project_challenge_id' => $challenge->id,
                'user_id' => $student->id,
                'project_url' => $validated['project_url'] ?? null,
                'evidence_image' => $imagePath,
                'reflection' => trim($validated['reflection']),
                'status' => 'submitted',
            ]);
        });

        return redirect()
            ->route('student.project-challenges.show', $projectChallenge)
            ->with('success', 'Your project has been submitted for admin review.');
    }
}
