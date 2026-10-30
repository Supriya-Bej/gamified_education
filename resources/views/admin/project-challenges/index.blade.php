@extends('admin.layouts.app')

@section('title', 'Project Challenges')
@section('page-heading', 'Project Challenge Management')

@section('content')
<div class="container-fluid py-3">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 p-4">
            <h2 class="h5 fw-bold">AI-generated project challenges and submissions</h2>
            <p class="text-muted mb-3">Review project briefs before publishing, then review student work and award XP.</p>
            <form method="GET" class="row g-2">
                <div class="col-sm-5 col-lg-4">
                    <label class="form-label" for="review_status">Challenge review</label>
                    <select class="form-select" name="review_status" id="review_status">
                        @foreach (['pending_review' => 'Needs review', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $value => $label)
                            <option value="{{ $value }}" @selected($reviewStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-5 col-lg-4">
                    <label class="form-label" for="submission_status">Submission</label>
                    <select class="form-select" name="submission_status" id="submission_status">
                        @foreach (['all' => 'All submissions', 'none' => 'Not submitted', 'submitted' => 'Waiting for review', 'revision_requested' => 'Revision requested', 'accepted' => 'Accepted'] as $value => $label)
                            <option value="{{ $value }}" @selected($submissionStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100" type="submit">Filter</button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th class="ps-4">Project</th><th>Student</th><th>Interest</th><th>Review status</th><th>Submission</th><th class="text-end pe-4">Action</th></tr></thead>
                <tbody>
                    @forelse ($challenges as $challenge)
                        @php($submission = $challenge->latestSubmission)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-semibold">{{ $challenge->title }}</div>
                                <small class="text-muted">{{ $challenge->xp }} XP · {{ ucfirst($challenge->difficulty) }}</small>
                            </td>
                            <td>{{ $challenge->student?->name ?? 'Student removed' }}</td>
                            <td>{{ $challenge->interest }}</td>
                            <td><span class="badge text-bg-{{ $challenge->review_status === 'approved' ? 'success' : ($challenge->review_status === 'rejected' ? 'danger' : 'warning') }}">{{ $challenge->review_status === 'pending_review' ? 'Needs review' : ucfirst($challenge->review_status) }}</span></td>
                            <td>{{ $submission ? str_replace('_', ' ', ucfirst($submission->status)) : 'Not submitted' }}</td>
                            <td class="text-end pe-4"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.project-challenges.show', $challenge) }}">Review</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">No project challenges match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($challenges->hasPages())
            <div class="card-footer bg-white">{{ $challenges->links() }}</div>
        @endif
    </div>
</div>
@endsection
