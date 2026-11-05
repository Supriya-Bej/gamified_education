<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\QuestAttempt;
use App\Services\InterestGameProgressionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CertificateManagementController extends Controller
{
    public function index()
    {
        $attempts = QuestAttempt::with(['user', 'task', 'certificate'])
            ->where('score', 100)
            ->where('passed', true)
            ->whereHas('user', fn ($query) => $query->where('role', 'student'))
            ->latest()
            ->paginate(15);
        $courseCertificates = Certificate::with(['user', 'task', 'issuer'])
            ->whereNotNull('interest')
            ->latest()
            ->get();

        return view('admin.certificates.index', compact('attempts', 'courseCertificates'));
    }

    public function approve(
        Certificate $certificate,
        InterestGameProgressionService $interestProgression
    ) {
        if (! $certificate->interest || ! in_array($certificate->status, ['pending_review', 'revoked'], true)) {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'This course certificate is not awaiting approval.');
        }

        DB::transaction(function () use ($certificate, $interestProgression): void {
            $certificate = Certificate::query()
                ->whereKey($certificate->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                ! $certificate->interest
                || ! in_array($certificate->status, ['pending_review', 'revoked'], true)
                || $certificate->user?->role !== 'student'
            ) {
                abort(403, 'This course certificate is not eligible for approval.');
            }

            if (! $interestProgression->stateFor(
                (int) $certificate->user_id,
                (string) $certificate->interest
            )['course_complete']) {
                abort(403, 'The student has not completed all games in this interest course.');
            }

            $certificate->update([
                'issued_by' => Auth::guard('admin')->id(),
                'status' => 'issued',
                'issued_at' => now(),
                'revoked_at' => null,
            ]);
        });

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Course certificate approved and issued to the student.');
    }

    public function issue(QuestAttempt $attempt)
    {
        if (! $attempt->passed || (int) $attempt->score !== 100 || $attempt->user?->role !== 'student') {
            return redirect()->route('admin.certificates.index')
                ->with('error', 'Only a passed quest with a 100% score is eligible for a certificate.');
        }

        DB::transaction(function () use ($attempt): void {
            $attempt = QuestAttempt::query()
                ->whereKey($attempt->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (! $attempt->passed || (int) $attempt->score !== 100 || $attempt->user?->role !== 'student') {
                abort(403, 'This quest attempt is not eligible for a certificate.');
            }

            $certificate = Certificate::firstOrNew([
                'quest_attempt_id' => $attempt->id,
            ]);

            if (! $certificate->exists) {
                // Generate the number of the certificate 
                $certificate->certificate_number = 'EQ-'.Str::upper(Str::random(12));
                $certificate->title = 'Certificate of Achievement';
            }

            $certificate->user_id = $attempt->user_id;
            $certificate->task_id = $attempt->task_id;
            $certificate->issued_by = Auth::guard('admin')->id();
            $certificate->status = 'issued';
            $certificate->issued_at = now();
            $certificate->revoked_at = null;
            $certificate->save();
        });

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate issued to the student.');
    }

    public function revoke(Certificate $certificate)
    {
        if ($certificate->status === 'issued') {
            $certificate->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', 'Certificate access has been revoked.');
    }
}
