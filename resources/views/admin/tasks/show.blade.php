
@extends('admin.layouts.app')

@section('title', 'Task Details')
@section('page-heading', 'Learning Task Details')

@section('content')

<div class="container-fluid py-3">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.tasks.index') }}"
           class="btn btn-white border shadow-sm rounded-3 px-3">
            <i class="bi bi-arrow-left me-2"></i>
            Back to Tasks
        </a>
    </div>

    {{-- Hero Header --}}
    <div class="task-detail-hero rounded-4 p-4 p-lg-5 mb-4 text-white">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-start gap-3">
                    <div class="hero-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>

                    <div class="min-w-0">
                        <span class="small text-white-50 text-uppercase fw-semibold">
                            EcoQuest Administration
                        </span>

                        <h2 class="fw-bold mt-2 mb-2">
                            {{ $task->title }}
                        </h2>

                        <p class="text-white-50 mb-2">
                            Review the game quest and manage its approval.
                        </p>

                        <small class="text-white-50">
                            <i class="bi bi-calendar3 me-1"></i>
                            Created {{ $task->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end">
                @php
                    $reviewStatus = $task->review_status ?? 'pending_review';

                    $reviewClass = match($reviewStatus) {
                        'approved' => 'review-approved',
                        'rejected' => 'review-rejected',
                        default => 'review-pending'
                    };

                    $statusClass = $task->status === 'completed'
                        ? 'task-completed'
                        : 'task-pending';
                @endphp

                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <span class="detail-status {{ $reviewClass }}">
                        <i class="bi bi-shield-check me-1"></i>
                        {{ ucwords(str_replace('_', ' ', $reviewStatus)) }}
                    </span>

                    <span class="detail-status {{ $statusClass }}">
                        <i class="bi {{ $task->status === 'completed' ? 'bi-check-circle-fill' : 'bi-hourglass-split' }} me-1"></i>
                        {{ ucfirst($task->status ?? 'Pending') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        {{-- Main Task Details --}}
        <div class="col-xl-8">

            {{-- Game Quest Overview --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                        Game Quest Overview
                    </h5>
                    <p class="text-muted small mb-0">
                        Details and learning requirements for this task.
                    </p>
                </div>

                <div class="card-body p-4">
                    <div class="description-panel mb-4">
                        <h6 class="fw-bold mb-3">Task Description</h6>
                        <p class="text-secondary mb-0 description-text">{{ $task->description ?? 'No description available.' }}</p>
                    </div>

                    <div class="row g-3">

                        <div class="col-sm-6">
                            <div class="detail-info-card">
                                <div class="detail-info-icon icon-purple">
                                    <i class="bi bi-folder2-open"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Category</small>
                                    <span class="fw-semibold">{{ $task->category ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="detail-info-card">
                                <div class="detail-info-icon icon-orange">
                                    <i class="bi bi-speedometer2"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Difficulty</small>
                                    <span class="fw-semibold">
                                        {{ ucfirst($task->difficulty ?? 'N/A') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="detail-info-card">
                                <div class="detail-info-icon icon-blue">
                                    <i class="bi bi-controller"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">Game Type</small>
                                    <code>{{ $task->game_type ?? 'N/A' }}</code>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="detail-info-card">
                                <div class="detail-info-icon icon-green">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">XP Reward</small>
                                    <span class="fw-bold text-primary">
                                        {{ $task->xp ?? 0 }} XP
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Review & Edit Game Quest --}}
            @if(in_array($task->review_status, ['pending_review', 'rejected'], true))

                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-0 p-4 pb-0">
                        <div class="d-flex align-items-start gap-3">
                            <div class="section-icon">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Review & Edit Game Quest</h5>
                                <p class="text-muted small mb-0">
                                    Update the game quest before approving it.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        <form method="POST"
                              action="{{ route('admin.tasks.review', $task) }}">
                            @csrf
                            @method('PUT')

                            {{-- Title --}}
                            <div class="mb-4">
                                <label for="title" class="form-label fw-semibold">
                                    Quest Title
                                </label>
                                <input
                                    id="title"
                                    name="title"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror"
                                    value="{{ old('title', $task->title) }}"
                                    required
                                    maxlength="255"
                                >
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">
                                    Description
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    class="form-control @error('description') is-invalid @enderror"
                                    required
                                    maxlength="2000"
                                >{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Difficulty & XP --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="difficulty" class="form-label fw-semibold">
                                        <i class="bi bi-bar-chart me-1 text-primary"></i>
                                        Difficulty
                                    </label>

                                    <select
                                        id="difficulty"
                                        name="difficulty"
                                        class="form-select @error('difficulty') is-invalid @enderror"
                                        required
                                    >
                                        @foreach(['beginner', 'intermediate', 'advanced'] as $difficulty)
                                            <option
                                                value="{{ $difficulty }}"
                                                @selected(old('difficulty', $task->difficulty) === $difficulty)
                                            >
                                                {{ ucfirst($difficulty) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('difficulty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="xp" class="form-label fw-semibold">
                                        <i class="bi bi-lightning-charge me-1 text-warning"></i>
                                        XP Reward
                                    </label>

                                    <div class="input-group">
                                        <input
                                            id="xp"
                                            type="number"
                                            name="xp"
                                            min="10"
                                            max="50"
                                            class="form-control @error('xp') is-invalid @enderror"
                                            value="{{ old('xp', $task->xp) }}"
                                            required
                                        >
                                        <span class="input-group-text">XP</span>
                                        @error('xp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-text">Allowed reward: 10–50 XP.</div>
                                </div>
                            </div>

                            {{-- Game Data --}}
                            <div class="mb-4">
                                <label for="game_data" class="form-label fw-semibold">
                                    <i class="bi bi-braces me-1 text-primary"></i>
                                    Game Data (JSON)
                                </label>

                                <textarea
                                    id="game_data"
                                    name="game_data"
                                    rows="10"
                                    class="form-control font-monospace json-editor @error('game_data') is-invalid @enderror"
                                    required
                                >{{ old('game_data', json_encode($task->game_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) }}</textarea>

                                <div class="form-text">
                                    Ensure this JSON matches the selected game type and passes validation.
                                </div>

                                @error('game_data')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Review Note --}}
                            <div class="mb-4">
                                <label for="review_note" class="form-label fw-semibold">
                                    Review Note <span class="text-muted fw-normal">(Optional)</span>
                                </label>

                                <textarea
                                    id="review_note"
                                    name="review_note"
                                    rows="3"
                                    maxlength="1000"
                                    class="form-control"
                                    placeholder="Add feedback or explain any changes..."
                                >{{ old('review_note', $task->review_note) }}</textarea>
                            </div>

                            <div class="review-actions d-flex flex-wrap gap-2">
                                <button
                                    class="btn btn-outline-primary rounded-3 px-4"
                                    type="submit"
                                    name="decision"
                                    value="save"
                                >
                                    <i class="bi bi-save me-2"></i>
                                    Save Edits
                                </button>

                                <button
                                    class="btn btn-success rounded-3 px-4"
                                    type="submit"
                                    name="decision"
                                    value="approve"
                                >
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Approve Game Quest
                                </button>
                            </div>
                        </form>

                        {{-- Reject Game Quest --}}
                        <div class="rejection-panel mt-4">
                            <h6 class="fw-bold text-danger mb-2">
                                <i class="bi bi-x-circle me-2"></i>
                                Reject Game Quest
                            </h6>
                            <p class="text-muted small">
                                Provide a reason for rejecting this game quest.
                            </p>

                            <form
                                method="POST"
                                action="{{ route('admin.tasks.reject', $task) }}"
                            >
                                @csrf

                                <label for="reject-note" class="form-label fw-semibold">
                                    Reason for Rejection
                                </label>

                                <textarea
                                    id="reject-note"
                                    name="review_note"
                                    rows="3"
                                    maxlength="1000"
                                    class="form-control @error('review_note') is-invalid @enderror"
                                    placeholder="Explain why this game quest should be rejected..."
                                    required
                                >{{ old('review_note') }}</textarea>

                                @error('review_note')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror

                                <button class="btn btn-outline-danger rounded-3 mt-3 px-4" type="submit">
                                    <i class="bi bi-x-circle me-2"></i>
                                    Reject Game Quest
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            @elseif($task->review_note)

                {{-- Existing Review Note --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-chat-left-text text-primary me-2"></i>
                            Review Feedback
                        </h5>

                        <div class="review-note-box">
                            {{ $task->review_note }}
                        </div>

                        @if($task->reviewer)
                            <div class="small text-muted mt-3">
                                <i class="bi bi-person-check me-1"></i>
                                {{ $task->review_status === 'approved' ? 'Approved' : 'Reviewed' }}
                                by <strong>{{ $task->reviewer->name }}</strong>

                                @if($task->reviewed_at)
                                    on {{ $task->reviewed_at->format('d M Y, h:i A') }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

            @endif

        </div>

        {{-- Sidebar --}}
        <div class="col-xl-4">

            {{-- Student Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-people text-primary me-2"></i>
                        Assigned Student
                    </h5>
                </div>

                <div class="card-body p-4">
                    @if($task->user)
                        <div class="student-profile mb-3">
                            <div class="student-avatar">
                                {{ strtoupper(substr($task->user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0">
                                <h6 class="fw-bold mb-1 text-break">
                                    {{ $task->user->name }}
                                </h6>
                                <span class="badge bg-primary-subtle text-primary">
                                    Student
                                </span>
                            </div>
                        </div>

                        <div class="sidebar-info">
                            <i class="bi bi-envelope"></i>
                            <span class="text-break">{{ $task->user->email }}</span>
                        </div>
                    @else
                        <div class="empty-sidebar text-center py-3">
                            <i class="bi bi-person-x fs-2 text-muted"></i>
                            <p class="text-muted small mb-0 mt-2">
                                This student no longer exists.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Connected Game Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-controller text-primary me-2"></i>
                        Connected Game
                    </h5>
                </div>

                <div class="card-body p-4">
                    @if($task->game)
                        <div class="game-banner mb-3">
                            <i class="bi bi-joystick"></i>
                        </div>

                        <h6 class="fw-bold mb-2">
                            {{ $task->game->name }}
                        </h6>

                        <p class="text-muted small mb-3">
                            {{ $task->game->description ?? 'No game description available.' }}
                        </p>

                        <div class="game-meta">
                            <small class="text-muted d-block mb-1">Game Type</small>
                            <span class="badge bg-dark rounded-pill px-3 py-2">
                                {{ $task->game->game_type ?? 'N/A' }}
                            </span>
                        </div>
                    @else
                        <div class="empty-sidebar text-center py-3">
                            <i class="bi bi-controller fs-2 text-muted"></i>
                            <p class="text-muted small mb-0 mt-2">
                                No game is connected to this task.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Summary --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Quick Summary
                    </h6>

                    <div class="summary-row">
                        <span class="text-muted">Task ID</span>
                        <strong>#{{ $task->id }}</strong>
                    </div>

                    <div class="summary-row">
                        <span class="text-muted">Category</span>
                        <strong>{{ $task->category ?? 'N/A' }}</strong>
                    </div>

                    <div class="summary-row">
                        <span class="text-muted">Reward</span>
                        <strong class="text-primary">{{ $task->xp ?? 0 }} XP</strong>
                    </div>

                    <div class="summary-row">
                        <span class="text-muted">Created</span>
                        <strong>{{ $task->created_at?->format('d M Y') ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<style>
    .task-detail-hero {
        background: linear-gradient(120deg, #173b8f, #315bd6 58%, #667eea);
        box-shadow: 0 12px 30px rgba(49, 91, 214, .16);
    }

    .hero-icon {
        width: 62px;
        height: 62px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 18px;
        background: rgba(255, 255, 255, .16);
        font-size: 1.8rem;
    }

    .detail-status {
        display: inline-flex;
        align-items: center;
        padding: 9px 13px;
        border-radius: 50px;
        font-size: .78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .review-approved, .task-completed {
        color: #166534;
        background: #dcfce7;
    }

    .review-rejected {
        color: #991b1b;
        background: #fee2e2;
    }

    .review-pending, .task-pending {
        color: #92400e;
        background: #fef3c7;
    }

    .description-panel {
        padding: 20px;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        background: #fafbfe;
    }

    .description-text {
        line-height: 1.8;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .detail-info-card {
        display: flex;
        align-items: center;
        gap: 13px;
        height: 100%;
        padding: 16px;
        border: 1px solid #edf0f5;
        border-radius: 14px;
        background: #fff;
    }

    .detail-info-icon {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 12px;
        font-size: 1.2rem;
    }

    .icon-purple {
        color: #7c3aed;
        background: #f3e8ff;
    }

    .icon-orange {
        color: #c2410c;
        background: #ffedd5;
    }

    .icon-blue {
        color: #1d4ed8;
        background: #dbeafe;
    }

    .icon-green {
        color: #15803d;
        background: #dcfce7;
    }

    .section-icon {
        width: 44px;
        height: 44px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 12px;
        color: #315bd6;
        background: #edf2ff;
        font-size: 1.25rem;
    }

    .form-control, .form-select, .input-group-text {
        border-color: #e2e8f0;
        border-radius: 10px;
    }

    .form-control, .form-select {
        padding: 11px 13px;
    }

    .input-group .form-control {
        border-radius: 10px 0 0 10px;
    }

    .input-group .input-group-text {
        border-radius: 0 10px 10px 0;
        background: #f8fafc;
    }

    .form-control:focus, .form-select:focus {
        border-color: #829cff;
        box-shadow: 0 0 0 .2rem rgba(49, 91, 214, .10);
    }

    .json-editor {
        background: #f8fafc;
        font-size: .85rem;
        line-height: 1.7;
        tab-size: 4;
    }

    .rejection-panel {
        padding: 20px;
        border: 1px solid #fee2e2;
        border-radius: 14px;
        background: #fffafa;
    }

    .review-note-box {
        padding: 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #edf0f5;
        color: #475467;
        line-height: 1.7;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .student-profile {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .student-avatar {
        width: 52px;
        height: 52px;
        flex-shrink: 0;
        display: grid;
        place-items: center;
        border-radius: 16px;
        background: linear-gradient(135deg, #315bd6, #667eea);
        color: #fff;
        font-size: 1.35rem;
        font-weight: 700;
    }

    .sidebar-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding-top: 15px;
        border-top: 1px solid #edf0f5;
        color: #667085;
        font-size: .875rem;
    }

    .sidebar-info i {
        color: #315bd6;
        margin-top: 3px;
    }

    .game-banner {
        height: 110px;
        display: grid;
        place-items: center;
        border-radius: 14px;
        background: linear-gradient(135deg, #edf2ff, #e0e7ff);
        color: #315bd6;
        font-size: 2.7rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #edf0f5;
        font-size: .875rem;
    }

    .summary-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .summary-row strong {
        text-align: right;
        overflow-wrap: anywhere;
    }

    .card {
        transition: box-shadow .2s ease;
    }

    .btn {
        transition: all .2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    @media (max-width: 575.98px) {
        .hero-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            font-size: 1.4rem;
        }

        .task-detail-hero {
            padding: 1.25rem !important;
        }

        .review-actions .btn {
            width: 100%;
        }
    }
</style>

@endsection
