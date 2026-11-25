<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningTask;
use App\Models\ProjectChallenge;
use App\Services\AIVerificationRules;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AIManagementController extends Controller
{
    public function reports(AIVerificationRules $verificationRules): View
    {
        return view('admin.ai.reports', [
            'rules' => $verificationRules->current(),
            'tasks' => LearningTask::query()
                ->with('user:id,name')
                ->latest()
                ->paginate(10, ['*'], 'tasks_page'),
            'challenges' => ProjectChallenge::query()
                ->with('student:id,name')
                ->latest()
                ->paginate(10, ['*'], 'challenges_page'),
            'taskCounts' => [
                'total' => LearningTask::query()->count(),
                'pending' => LearningTask::query()->where('review_status', 'pending_review')->count(),
                'approved' => LearningTask::query()->where('review_status', 'approved')->count(),
                'rejected' => LearningTask::query()->where('review_status', 'rejected')->count(),
            ],
            'challengeCounts' => [
                'total' => ProjectChallenge::query()->count(),
                'pending' => ProjectChallenge::query()->where('review_status', 'pending_review')->count(),
                'approved' => ProjectChallenge::query()->where('review_status', 'approved')->count(),
                'rejected' => ProjectChallenge::query()->where('review_status', 'rejected')->count(),
            ],
        ]);
    }

    public function updateRules(Request $request, AIVerificationRules $verificationRules): RedirectResponse
    {
        $rules = $request->validate([
            'minimum_objectives' => ['required', 'integer', 'min:3', 'max:5'],
            'minimum_brief_characters' => ['required', 'integer', 'min:40', 'max:1000'],
            'maximum_xp' => ['required', 'integer', 'min:20', 'max:100'],
        ]);

        $verificationRules->save($rules);

        return redirect()
            ->route('admin.ai.reports')
            ->with('success', 'AI verification rules updated.');
    }
}
