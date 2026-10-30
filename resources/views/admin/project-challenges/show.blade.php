@extends('admin.layouts.app')

@section('title', 'Review Project Challenge')
@section('page-heading', 'Project Challenge Review')

@section('content')
<div class="container-fluid py-3">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <a href="{{ route('admin.project-challenges.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left me-1"></i> Back to project challenges
    </a>

    <div class="row g-4">
        <div class="col-xl-7">
            <section class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <div class="d-flex justify-content-between gap-3">
                        <div>
                            <h2 class="h4 mb-1">{{ $projectChallenge->title }}</h2>
                            <p class="text-muted mb-0">{{ $projectChallenge->student?->name }} · {{ $projectChallenge->interest }}</p>
                        </div>
                        <span class="badge text-bg-{{ $projectChallenge->review_status === 'approved' ? 'success' : ($projectChallenge->review_status === 'rejected' ? 'danger' : 'warning') }} align-self-start">
                            {{ str_replace('_', ' ', ucfirst($projectChallenge->review_status)) }}
                        </span>
                    </div>
                </div>
                @if (in_array($projectChallenge->review_status, ['pending_review', 'rejected'], true))
                    <div class="card-body p-4">
                        <h3 class="h5">Edit and review project brief</h3>
                        <form method="POST" action="{{ route('admin.project-challenges.review', $projectChallenge) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="interest">Interest</label>
                                <input class="form-control" id="interest" name="interest" value="{{ old('interest', $projectChallenge->interest) }}" required maxlength="100">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Project title</label>
                                <input class="form-control" id="title" name="title" value="{{ old('title', $projectChallenge->title) }}" required maxlength="255">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="project_brief">Project brief</label>
                                <textarea class="form-control" id="project_brief" name="project_brief" rows="5" required maxlength="5000">{{ old('project_brief', $projectChallenge->project_brief) }}</textarea>
                            </div>
                            @foreach (['objectives' => ['Objectives', $projectChallenge->objectives, 5], 'deliverables' => ['Deliverables', $projectChallenge->deliverables, 4]] as $field => [$label, $items, $maximum])
                                <fieldset class="mb-3">
                                    <legend class="form-label">{{ $label }}</legend>
                                    @for ($index = 0; $index < $maximum; $index++)
                                        <input class="form-control mb-2" name="{{ $field }}[]" value="{{ old($field.'.'.$index, $items[$index] ?? '') }}" maxlength="500" @required($index < ($field === 'objectives' ? 3 : 2))>
                                    @endfor
                                </fieldset>
                            @endforeach
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="difficulty">Difficulty</label>
                                    <select class="form-select" id="difficulty" name="difficulty" required>
                                        @foreach (['beginner', 'intermediate', 'advanced'] as $difficulty)
                                            <option value="{{ $difficulty }}" @selected(old('difficulty', $projectChallenge->difficulty) === $difficulty)>{{ ucfirst($difficulty) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="xp">XP reward</label>
                                    <input class="form-control" id="xp" name="xp" type="number" min="20" max="100" value="{{ old('xp', $projectChallenge->xp) }}" required>
                                </div>
                            </div>
                            <div class="mt-3 mb-3">
                                <label class="form-label" for="review_note">Review note</label>
                                <textarea class="form-control" id="review_note" name="review_note" rows="2" maxlength="1000">{{ old('review_note', $projectChallenge->review_note) }}</textarea>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-outline-secondary" name="decision" value="save">Save edits</button>
                                <button class="btn btn-success" name="decision" value="approve">Approve and publish</button>
                                <button class="btn btn-danger" name="decision" value="reject">Reject</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="card-body p-4">
                        <h3 class="h5">Project brief</h3>
                        <p>{{ $projectChallenge->project_brief }}</p>
                        <h4 class="h6">Objectives</h4>
                        <ul>@foreach ($projectChallenge->objectives as $objective)<li>{{ $objective }}</li>@endforeach</ul>
                        <h4 class="h6">Deliverables</h4>
                        <ul>@foreach ($projectChallenge->deliverables as $deliverable)<li>{{ $deliverable }}</li>@endforeach</ul>
                        <p class="mb-0">Difficulty: {{ ucfirst($projectChallenge->difficulty) }} · Reward: {{ $projectChallenge->xp }} XP</p>
                    </div>
                @endif
            </section>
        </div>

        <div class="col-xl-5">
            <section class="card border-0 shadow-sm">
                <div class="card-header bg-white p-4">
                    <h3 class="h5 mb-0">Student project submissions</h3>
                </div>
                <div class="card-body p-4">
                    @forelse ($projectChallenge->submissions as $submission)
                        <article class="border rounded-3 p-3 mb-3">
                            <div class="d-flex justify-content-between gap-2">
                                <strong>Submission {{ $submission->id }}</strong>
                                <span class="badge text-bg-{{ $submission->status === 'accepted' ? 'success' : ($submission->status === 'revision_requested' ? 'warning' : 'info') }}">
                                    {{ str_replace('_', ' ', ucfirst($submission->status)) }}
                                </span>
                            </div>
                            <p class="mt-3 mb-2"><a href="{{ $submission->project_url }}" target="_blank" rel="noopener noreferrer">{{ $submission->project_url }}</a></p>
                            <p>{{ $submission->reflection }}</p>
                            @if ($submission->review_note)
                                <p class="small text-muted">Review note: {{ $submission->review_note }}</p>
                            @endif
                            @if ($submission->status === 'submitted' && $projectChallenge->xp_awarded_at === null)
                                <form method="POST" action="{{ route('admin.project-challenges.submissions.review', [$projectChallenge, $submission]) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-2">
                                        <label class="form-label" for="review_note_{{ $submission->id }}">Feedback (required)</label>
                                        <textarea class="form-control" id="review_note_{{ $submission->id }}" name="review_note" rows="2" required maxlength="1000"></textarea>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-success btn-sm" name="decision" value="accept">Accept and award XP</button>
                                        <button class="btn btn-outline-warning btn-sm" name="decision" value="request_revision">Request changes</button>
                                    </div>
                                </form>
                            @endif
                        </article>
                    @empty
                        <p class="text-muted mb-0">The student has not submitted a project yet.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
