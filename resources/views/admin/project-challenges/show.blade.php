@extends('admin.layouts.app')

@section('title', 'Review Project Challenge')
@section('page-heading', 'Project Challenge Review')

@section('content')

<style>
    .challenge-review {
        --navy: #182640;
        --navy-light: #293d5d;
        --green: #13865e;
        --green-soft: #e8f6ee;
        --purple: #7765d8;
        --text: #2a3a50;
        --muted: #8390a1;
        --border: #e8edf2;
    }

    .challenge-review .hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        border-radius: 22px;
        color: white;
        background: linear-gradient(115deg, #17233e, #2c4164 60%, #147c61);
        box-shadow: 0 12px 30px rgba(23, 35, 62, .13);
    }

    .challenge-review .hero::after {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        right: -70px;
        top: -125px;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .challenge-review .hero-content {
        position: relative;
        z-index: 1;
    }

    .challenge-review .hero-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border-radius: 20px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .2);
        font-size: 32px;
    }

    .challenge-review .hero-label {
        color: #a9e8d0;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.4px;
        margin-bottom: 7px;
    }

    .challenge-review .hero-title {
        font-size: 29px;
        font-weight: 850;
        margin-bottom: 7px;
    }

    .challenge-review .hero-description {
        font-size: 15px;
        color: rgba(255, 255, 255, .75);
        margin: 0;
    }

    .challenge-review .back-btn {
        color: white;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 10px;
        padding: 11px 16px;
        font-size: 14px;
        font-weight: 700;
        white-space: nowrap;
    }

    .challenge-review .back-btn:hover {
        color: var(--navy);
        background: white;
    }

    .challenge-review .notice {
        display: flex;
        gap: 13px;
        align-items: flex-start;
        padding: 18px 20px;
        border-radius: 14px;
        background: #edf5ff;
        border: 1px solid #d9e8ff;
        color: #365a89;
    }

    .challenge-review .notice-icon {
        font-size: 23px;
        color: #4779c9;
    }

    .challenge-review .notice-title {
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .challenge-review .notice-text {
        font-size: 13px;
        line-height: 1.7;
        margin: 0;
        color: #587397;
    }

    .challenge-review .panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(30, 48, 70, .045);
        height: 100%;
    }

    .challenge-review .panel-header {
        padding: 22px 24px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(110deg, #fbfcfe, #f5f9f7);
    }

    .challenge-review .panel-heading {
        color: var(--navy);
        font-size: 19px;
        font-weight: 850;
        margin-bottom: 5px;
    }

    .challenge-review .panel-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .challenge-review .panel-body {
        padding: 24px;
    }

    .challenge-review .section-label {
        display: flex;
        align-items: center;
        gap: 11px;
        color: var(--navy);
        font-size: 16px;
        font-weight: 850;
        margin-bottom: 17px;
    }

    .challenge-review .section-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        font-size: 18px;
        color: var(--green);
        background: var(--green-soft);
    }

    .challenge-review .form-label {
        font-size: 13px;
        font-weight: 800;
        color: #40516a;
        margin-bottom: 8px;
    }

    .challenge-review .form-control,
    .challenge-review .form-select {
        min-height: 46px;
        border: 1px solid #dfe6ed;
        border-radius: 10px;
        background: #fbfcfe;
        padding: 11px 13px;
        color: #293a50;
        font-size: 14px;
        box-shadow: none;
    }

    .challenge-review textarea.form-control {
        line-height: 1.7;
        resize: vertical;
    }

    .challenge-review .form-control:focus,
    .challenge-review .form-select:focus {
        border-color: #39a981;
        background: white;
        box-shadow: 0 0 0 4px rgba(19, 134, 94, .09);
    }

    .challenge-review .form-control.is-invalid,
    .challenge-review .form-select.is-invalid {
        border-color: #dc6464;
    }

    .challenge-review .field-hint {
        font-size: 12px;
        color: #98a3b1;
        margin-top: 6px;
    }

    .challenge-review .objective-input {
        position: relative;
    }

    .challenge-review .objective-input .form-control {
        padding-left: 43px;
    }

    .challenge-review .objective-number {
        position: absolute;
        left: 13px;
        top: 13px;
        color: #819087;
        font-size: 12px;
        font-weight: 800;
    }

    .challenge-review .soft-box {
        padding: 17px;
        background: #f7faf8;
        border: 1px solid #e7efe9;
        border-radius: 13px;
    }

    .challenge-review .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .challenge-review .status-approved {
        background: #e5f6eb;
        color: #167449;
    }

    .challenge-review .status-rejected {
        background: #fdebea;
        color: #b43d3d;
    }

    .challenge-review .status-pending_review,
    .challenge-review .status-submitted {
        background: #fff3d9;
        color: #9a6b0b;
    }

    .challenge-review .status-accepted {
        background: #e5f6eb;
        color: #167449;
    }

    .challenge-review .status-revision_requested {
        background: #fff3d9;
        color: #9a6b0b;
    }

    .challenge-review .status-default {
        background: #edf1f4;
        color: #647286;
    }

    .challenge-review .submission-card {
        border: 1px solid #e5ebf0;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 15px;
        background: #fff;
        transition: .2s;
    }

    .challenge-review .submission-card:hover {
        border-color: #c8dfd2;
        box-shadow: 0 5px 16px rgba(24, 65, 44, .05);
    }

    .challenge-review .submission-title {
        font-size: 15px;
        color: var(--navy);
        font-weight: 850;
    }

    .challenge-review .submission-meta {
        font-size: 12px;
        color: var(--muted);
        margin-top: 5px;
    }

    .challenge-review .submission-text {
        font-size: 14px;
        color: #536277;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .challenge-review .project-link {
        display: block;
        padding: 12px;
        background: #f1f5ff;
        border: 1px solid #e1e9ff;
        border-radius: 10px;
        color: #456cc2;
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
        text-decoration: none;
    }

    .challenge-review .project-link:hover {
        background: #e8efff;
    }

    .challenge-review .evidence-image {
        max-height: 280px;
        width: 100%;
        object-fit: contain;
        background: #f6f8fa;
        border-radius: 11px;
        border: 1px solid #e7edf1;
    }

    .challenge-review .review-note {
        padding: 12px 14px;
        border-radius: 10px;
        background: #f5f6ff;
        border: 1px solid #e5e6fa;
        color: #625b9a;
        font-size: 13px;
        line-height: 1.7;
    }

    .challenge-review .empty-state {
        text-align: center;
        padding: 42px 15px;
    }

    .challenge-review .empty-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        border-radius: 19px;
        background: #eaf5ef;
        color: #16825a;
        font-size: 29px;
    }

    .challenge-review .empty-title {
        font-size: 17px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 6px;
    }

    .challenge-review .empty-text {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .challenge-review .action-footer {
        padding: 20px 24px;
        background: #f8fafb;
        border-top: 1px solid var(--border);
    }

    .challenge-review .btn-save {
        background: #eef2f7;
        color: #40516a;
        border: 1px solid #dce4ed;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
        padding: 11px 17px;
    }

    .challenge-review .btn-approve {
        background: linear-gradient(110deg, #11835c, #19a477);
        color: white;
        border: 0;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
        padding: 11px 17px;
        box-shadow: 0 4px 12px rgba(19, 134, 94, .17);
    }

    .challenge-review .btn-reject {
        background: #fff0ef;
        color: #bd4842;
        border: 1px solid #f2d4d1;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
        padding: 11px 17px;
    }

    .challenge-review .btn-save:hover {
        background: #e3eaf2;
    }

    .challenge-review .btn-approve:hover {
        background: #0d714f;
        color: white;
    }

    .challenge-review .btn-reject:hover {
        background: #fde2df;
        color: #a73c37;
    }

    .challenge-review .alert {
        border-radius: 12px;
        font-size: 14px;
    }

    .challenge-review .divider {
        border-color: #e9eef2;
        opacity: 1;
        margin: 25px 0;
    }

    @media(max-width: 767px) {
        .challenge-review .hero {
            padding: 23px 19px;
        }

        .challenge-review .hero-title {
            font-size: 24px;
        }

        .challenge-review .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 18px;
        }

        .challenge-review .panel-body {
            padding: 18px;
        }

        .challenge-review .panel-header {
            padding: 19px;
        }

        .challenge-review .action-footer {
            padding: 17px;
        }
    }
</style>


<div class="challenge-review">

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
    @endif


    {{-- VALIDATION ERRORS --}}
    @if ($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please correct the following errors
        </div>

        <ul class="mb-0 ps-4">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif


    {{-- HERO --}}
    <div class="hero mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-kanban"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            ECOQUEST / PROJECT MANAGEMENT
                        </div>

                        <h2 class="hero-title">
                            Project Challenge Review
                        </h2>

                        <p class="hero-description">
                            Manage the project brief and inspect student submissions.
                        </p>
                    </div>

                </div>

                <a href="{{ route('admin.project-challenges.index') }}"
                    class="btn back-btn">

                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Challenges

                </a>

            </div>

        </div>

    </div>


    {{-- AI VERIFICATION NOTICE --}}
    <div class="notice mb-4">

        <div class="notice-icon">
            <i class="bi bi-robot"></i>
        </div>

        <div>
            <div class="notice-title">
                AI-Powered Submission Verification
            </div>

            <p class="notice-text">
                Student project submissions are intended to be verified automatically by the EcoQuest AI verification system. Administrators can inspect submitted evidence and manage challenge briefs, but should not manually accept submissions or award student XP.
            </p>
        </div>

    </div>


    <div class="row g-4">

        {{-- LEFT: PROJECT DETAILS --}}
        <div class="col-xl-7">

            <section class="panel">

                <div class="panel-header">

                    <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

                        <div>
                            <div class="text-uppercase fw-bold mb-2"
                                style="font-size:11px;letter-spacing:1px;color:#8996a6;">
                                PROJECT CHALLENGE
                            </div>

                            <h3 class="panel-heading mb-1">
                                {{ $projectChallenge->title }}
                            </h3>

                            <p class="panel-subtitle">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ $projectChallenge->student?->name ?? 'Unknown Student' }}

                                <span class="mx-1">·</span>

                                <i class="bi bi-lightbulb me-1"></i>
                                {{ $projectChallenge->interest }}
                            </p>
                        </div>

                        @php
                        $reviewStatus = $projectChallenge->review_status;

                        $challengeStatusClass = match($reviewStatus) {
                        'approved' => 'status-approved',
                        'rejected' => 'status-rejected',
                        'pending_review' => 'status-pending_review',
                        default => 'status-default',
                        };
                        @endphp

                        <span class="status-pill {{ $challengeStatusClass }}">
                            <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                            {{ ucwords(str_replace('_', ' ', $reviewStatus)) }}
                        </span>

                    </div>

                </div>


                @if (in_array($projectChallenge->review_status, ['pending_review', 'rejected'], true))

                {{-- EDIT AND REVIEW BRIEF --}}
                <form method="POST"
                    action="{{ route('admin.project-challenges.review', $projectChallenge) }}">

                    @csrf
                    @method('PUT')

                    <div class="panel-body">

                        <div class="section-label">
                            <div class="section-icon">
                                <i class="bi bi-pencil-square"></i>
                            </div>

                            <div>
                                Edit Project Brief
                                <div class="text-muted fw-normal" style="font-size:12px;">
                                    Review and improve the challenge before publishing.
                                </div>
                            </div>
                        </div>


                        {{-- INTEREST --}}
                        <div class="mb-3">

                            <label class="form-label" for="interest">
                                <i class="bi bi-lightbulb me-1"></i>
                                Interest
                            </label>

                            <input class="form-control @error('interest') is-invalid @enderror"
                                id="interest"
                                name="interest"
                                value="{{ old('interest', $projectChallenge->interest) }}"
                                required
                                maxlength="100">

                            @error('interest')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- TITLE --}}
                        <div class="mb-3">

                            <label class="form-label" for="title">
                                <i class="bi bi-type me-1"></i>
                                Project Title
                            </label>

                            <input class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ old('title', $projectChallenge->title) }}"
                                required
                                maxlength="255">

                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- PROJECT BRIEF --}}
                        <div class="mb-4">

                            <label class="form-label" for="project_brief">
                                <i class="bi bi-card-text me-1"></i>
                                Project Brief
                            </label>

                            <textarea class="form-control @error('project_brief') is-invalid @enderror"
                                id="project_brief"
                                name="project_brief"
                                rows="5"
                                required
                                maxlength="5000"
                                placeholder="Explain the project requirements and expected outcome...">{{ old('project_brief', $projectChallenge->project_brief) }}</textarea>

                            @error('project_brief')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        {{-- OBJECTIVES --}}
                        <div class="soft-box mb-4">

                            <div class="section-label mb-3">
                                <div class="section-icon"
                                    style="background:#e9efff;color:#527be8;">
                                    <i class="bi bi-bullseye"></i>
                                </div>

                                <div>
                                    Project Objectives
                                    <div class="text-muted fw-normal" style="font-size:12px;">
                                        Define what students should achieve.
                                    </div>
                                </div>
                            </div>

                            @foreach (['objectives' => ['Objectives', $projectChallenge->objectives, 5], 'deliverables' => ['Deliverables', $projectChallenge->deliverables, 4]] as $field => [$label, $items, $maximum])

                            @if($field === 'deliverables')
                            <hr class="divider">
                            <div class="section-label mb-3">
                                <div class="section-icon"
                                    style="background:#f0ebff;color:#7765d8;">
                                    <i class="bi bi-box-seam"></i>
                                </div>

                                <div>
                                    Deliverables
                                    <div class="text-muted fw-normal" style="font-size:12px;">
                                        Specify what students must submit.
                                    </div>
                                </div>
                            </div>
                            @endif

                            @for ($index = 0; $index < $maximum; $index++)

                                <div class="objective-input mb-2">

                                <span class="objective-number">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                <input class="form-control @error($field.'.'.$index) is-invalid @enderror"
                                    name="{{ $field }}[]"
                                    value="{{ old($field.'.'.$index, $items[$index] ?? '') }}"
                                    maxlength="500"
                                    placeholder="Enter {{ strtolower($label) }} item {{ $index + 1 }}"
                                    @required($index < ($field==='objectives' ? 3 : 2))>

                        </div>

                        @error($field.'.'.$index)
                        <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        @endfor

                        @endforeach

                    </div>


                    {{-- DIFFICULTY AND XP --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">

                            <label class="form-label" for="difficulty">
                                <i class="bi bi-speedometer2 me-1"></i>
                                Difficulty
                            </label>

                            <select class="form-select @error('difficulty') is-invalid @enderror"
                                id="difficulty"
                                name="difficulty"
                                required>

                                @foreach (['beginner', 'intermediate', 'advanced'] as $difficulty)

                                <option value="{{ $difficulty }}"
                                    @selected(old('difficulty', $projectChallenge->difficulty) === $difficulty)>
                                    {{ ucfirst($difficulty) }}
                                </option>

                                @endforeach

                            </select>

                            @error('difficulty')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label class="form-label" for="xp">
                                <i class="bi bi-lightning-charge-fill me-1"></i>
                                XP Reward
                            </label>

                            <input class="form-control @error('xp') is-invalid @enderror"
                                id="xp"
                                name="xp"
                                type="number"
                                min="20"
                                max="100"
                                value="{{ old('xp', $projectChallenge->xp) }}"
                                required>

                            @error('xp')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="field-hint">
                                Reward between 20 and 100 XP.
                            </div>

                        </div>

                    </div>


                    {{-- REVIEW NOTE --}}
                    <div class="mb-2">

                        <label class="form-label" for="review_note">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Admin Review Note
                        </label>

                        <textarea class="form-control @error('review_note') is-invalid @enderror"
                            id="review_note"
                            name="review_note"
                            rows="3"
                            maxlength="1000"
                            placeholder="Write feedback or suggestions about this challenge...">{{ old('review_note', $projectChallenge->review_note) }}</textarea>

                        @error('review_note')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                    </div>

        </div>


        {{-- ACTIONS --}}
        <div class="action-footer">

            <div class="d-flex flex-wrap gap-2 justify-content-end">

                <button class="btn btn-save"
                    name="decision"
                    value="save">

                    <i class="bi bi-save me-1"></i>
                    Save Edits

                </button>

                <button class="btn btn-approve"
                    name="decision"
                    value="approve"
                    onclick="return confirm('Are you sure you want to approve and publish this project challenge?')">

                    <i class="bi bi-check-circle me-1"></i>
                    Approve & Publish

                </button>

                <button class="btn btn-reject"
                    name="decision"
                    value="reject"
                    onclick="return confirm('Are you sure you want to reject this project challenge?')">

                    <i class="bi bi-x-circle me-1"></i>
                    Reject Challenge

                </button>

            </div>

        </div>

        </form>

        @else

        {{-- APPROVED / REJECTED PROJECT BRIEF --}}
        <div class="panel-body">

            <div class="section-label">
                <div class="section-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>

                Project Brief
            </div>

            <p class="submission-text mb-4">
                {{ $projectChallenge->project_brief }}
            </p>

            <div class="soft-box mb-3">

                <h6 class="fw-bold mb-3" style="color:#243752;">
                    <i class="bi bi-bullseye me-2 text-primary"></i>
                    Project Objectives
                </h6>

                <ul class="mb-0 ps-3">
                    @foreach ($projectChallenge->objectives as $objective)
                    <li class="mb-2 submission-text">
                        {{ $objective }}
                    </li>
                    @endforeach
                </ul>

            </div>


            <div class="soft-box mb-4">

                <h6 class="fw-bold mb-3" style="color:#243752;">
                    <i class="bi bi-box-seam me-2" style="color:#7765d8;"></i>
                    Expected Deliverables
                </h6>

                <ul class="mb-0 ps-3">
                    @foreach ($projectChallenge->deliverables as $deliverable)
                    <li class="mb-2 submission-text">
                        {{ $deliverable }}
                    </li>
                    @endforeach
                </ul>

            </div>


            <div class="row g-3">

                <div class="col-md-6">
                    <div class="soft-box">
                        <div class="text-muted small fw-bold mb-1">
                            DIFFICULTY
                        </div>

                        <div class="fw-bold text-dark">
                            {{ ucfirst($projectChallenge->difficulty) }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="soft-box">
                        <div class="text-muted small fw-bold mb-1">
                            XP REWARD
                        </div>

                        <div class="fw-bold" style="color:#13865e;">
                            <i class="bi bi-lightning-charge-fill"></i>
                            {{ $projectChallenge->xp }} XP
                        </div>
                    </div>
                </div>

            </div>


            @if($projectChallenge->review_note)
            <div class="review-note mt-4">
                <strong>
                    <i class="bi bi-chat-left-text me-1"></i>
                    Admin Review Note
                </strong>

                <div class="mt-2">
                    {{ $projectChallenge->review_note }}
                </div>
            </div>
            @endif

        </div>

        @endif

        </section>

    </div>


    {{-- RIGHT: STUDENT SUBMISSIONS --}}
    <div class="col-xl-5">

        <section class="panel">

            <div class="panel-header">

                <div class="d-flex justify-content-between align-items-center gap-2">

                    <div>
                        <h3 class="panel-heading">
                            Student Submissions
                        </h3>

                        <p class="panel-subtitle">
                            Submitted project evidence and reflections.
                        </p>
                    </div>

                    <div class="section-icon"
                        style="width:43px;height:43px;background:#e9efff;color:#527be8;">
                        <i class="bi bi-folder-check"></i>
                    </div>

                </div>

            </div>


            <div class="panel-body">

                @forelse ($projectChallenge->submissions as $submission)

                @php
                $submissionStatus = $submission->status;

                $submissionStatusClass = match($submissionStatus) {
                'accepted' => 'status-accepted',
                'revision_requested' => 'status-revision_requested',
                'submitted' => 'status-submitted',
                default => 'status-default',
                };
                @endphp


                <article class="submission-card">

                    {{-- SUBMISSION HEADER --}}
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">

                        <div>
                            <div class="submission-title">
                                <i class="bi bi-file-earmark-code me-1"
                                    style="color:#527be8;"></i>
                                Submission #{{ $submission->id }}
                            </div>

                            <div class="submission-meta">
                                Student project evidence
                            </div>
                        </div>

                        <span class="status-pill {{ $submissionStatusClass }}">
                            {{ ucwords(str_replace('_', ' ', $submissionStatus)) }}
                        </span>

                    </div>


                    {{-- PROJECT URL --}}
                    @if ($submission->project_url)

                    <div class="mb-3">

                        <div class="small fw-bold text-muted mb-2">
                            <i class="bi bi-link-45deg me-1"></i>
                            PROJECT LINK
                        </div>

                        <a href="{{ $submission->project_url }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="project-link">

                            <i class="bi bi-box-arrow-up-right me-2"></i>
                            {{ $submission->project_url }}

                        </a>

                    </div>

                    @endif


                    {{-- EVIDENCE IMAGE --}}
                    @if ($submission->evidence_image)

                    <div class="mb-3">

                        <div class="small fw-bold text-muted mb-2">
                            <i class="bi bi-image me-1"></i>
                            UPLOADED EVIDENCE
                        </div>

                        <a href="{{ asset('storage/' . $submission->evidence_image) }}"
                            target="_blank"
                            rel="noopener noreferrer">

                            <img src="{{ asset('storage/' . $submission->evidence_image) }}"
                                alt="Uploaded project evidence"
                                class="evidence-image">

                        </a>

                    </div>

                    @endif


                    {{-- REFLECTION --}}
                    <div class="mb-3">

                        <div class="small fw-bold text-muted mb-2">
                            <i class="bi bi-chat-square-text me-1"></i>
                            STUDENT REFLECTION
                        </div>

                        <p class="submission-text mb-0">
                            {{ $submission->reflection ?: 'No reflection provided.' }}
                        </p>

                    </div>


                    {{-- REVIEW NOTE --}}
                    @if ($submission->review_note)

                    <div class="review-note">

                        <strong>
                            <i class="bi bi-chat-left-text me-1"></i>
                            Verification / Review Feedback
                        </strong>

                        <div class="mt-2">
                            {{ $submission->review_note }}
                        </div>

                    </div>

                    @endif


                    {{-- AI VERIFICATION STATUS --}}
                    @if ($submissionStatus === 'submitted')

                    <div class="mt-3 p-3 rounded-3"
                        style="background:#fff7e8;border:1px solid #f5e6c5;">

                        <div class="d-flex align-items-center gap-2"
                            style="color:#9a6b0b;">

                            <i class="bi bi-robot fs-5"></i>

                            <strong style="font-size:13px;">
                                Awaiting Verification
                            </strong>

                        </div>

                        <p class="mb-0 mt-2"
                            style="font-size:12px;color:#927d55;line-height:1.6;">
                            This submission has been submitted. Check the AI verification workflow for its processing result.
                        </p>

                    </div>

                    @elseif ($submissionStatus === 'accepted')

                    <div class="mt-3 p-3 rounded-3"
                        style="background:#eaf7ef;border:1px solid #d6eddf;">

                        <div class="d-flex align-items-center gap-2"
                            style="color:#167449;">

                            <i class="bi bi-check-circle-fill fs-5"></i>

                            <strong style="font-size:13px;">
                                Submission Accepted
                            </strong>

                        </div>

                        <p class="mb-0 mt-2"
                            style="font-size:12px;color:#648674;">
                            This submission is marked as accepted.
                        </p>

                    </div>

                    @elseif ($submissionStatus === 'revision_requested')

                    <div class="mt-3 p-3 rounded-3"
                        style="background:#fff7e8;border:1px solid #f5e6c5;">

                        <div class="d-flex align-items-center gap-2"
                            style="color:#9a6b0b;">

                            <i class="bi bi-arrow-repeat fs-5"></i>

                            <strong style="font-size:13px;">
                                Revision Requested
                            </strong>

                        </div>

                        <p class="mb-0 mt-2"
                            style="font-size:12px;color:#927d55;">
                            Changes have been requested for this submission.
                        </p>

                    </div>

                    @endif

                </article>

                @empty

                <div class="empty-state">

                    <div class="empty-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>

                    <h5 class="empty-title">
                        No Submissions Yet
                    </h5>

                    <p class="empty-text">
                        The student has not submitted a project for this challenge.
                    </p>

                </div>

                @endforelse

            </div>

        </section>


        {{-- XP INFORMATION --}}
        <div class="soft-box mt-4">

            <div class="d-flex align-items-center gap-3">

                <div class="section-icon"
                    style="width:45px;height:45px;background:#e5f5ec;color:#13865e;">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>

                <div>
                    <div class="fw-bold" style="color:#173c2d;font-size:15px;">
                        Challenge Reward
                    </div>

                    <div class="text-muted" style="font-size:12px;">
                        Configured reward for successful completion.
                    </div>
                </div>

                <div class="ms-auto fw-bold"
                    style="color:#13865e;font-size:22px;">
                    {{ $projectChallenge->xp }} XP
                </div>

            </div>

            <hr style="border-color:#dfeae2;">

            <p class="mb-0 text-muted" style="font-size:12px;line-height:1.7;">
                XP should be awarded only after the AI verification workflow confirms successful completion. The server must enforce the reward rules and prevent duplicate XP awards.
            </p>

        </div>

    </div>

</div>

</div>

@endsection