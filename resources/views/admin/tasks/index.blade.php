@extends('admin.layouts.app')

@section('title', 'Learning Tasks')
@section('page-heading', 'Learning Task Management')

@section('content')

<div class="container-fluid py-3">

    {{-- Alerts --}}
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

    {{-- Page Header --}}
    <div class="task-hero rounded-4 p-4 p-lg-5 mb-4 text-white">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="task-hero-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <span class="small text-white-50 text-uppercase fw-semibold">
                            EcoQuest Administration
                        </span>
                        <h2 class="fw-bold mb-1 mt-1">Learning Tasks</h2>
                        <p class="mb-0 text-white-50">
                            Track student activities, learning progress and earned XP.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 text-lg-end">
                <span class="hero-total">
                    <i class="bi bi-collection me-2"></i>
                    {{ $tasks->total() }} Total Tasks
                </span>
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card task-stat-card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-list-task"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small">Total Tasks</p>
                        <h3 class="fw-bold mb-0">{{ $tasks->total() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card task-stat-card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small">Completed on This Page</p>
                        <h3 class="fw-bold mb-0">
                            {{ $tasks->getCollection()->where('status', 'completed')->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card task-stat-card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-1 small">Pending on This Page</p>
                        <h3 class="fw-bold mb-0">
                            {{ $tasks->getCollection()->where('status', '!=', 'completed')->count() }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Main Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="card-header bg-white border-0 p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-grid-1x2-fill text-primary me-2"></i>
                        All Learning Tasks
                    </h5>
                    <p class="text-muted small mb-0">
                        Review assigned tasks and check each student's status.
                    </p>
                </div>

                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-2">
                    <form method="GET" action="{{ route('admin.tasks.index') }}" class="d-flex align-items-center gap-2">
                        <label for="review_status" class="small text-muted mb-0">Review status</label>
                        <select id="review_status" name="review_status" class="form-select form-select-sm">
                            <option value="pending_review" @selected($reviewStatus === 'pending_review')>Needs review</option>
                            <option value="approved" @selected($reviewStatus === 'approved')>Approved</option>
                            <option value="rejected" @selected($reviewStatus === 'rejected')>Rejected</option>
                            <option value="all" @selected($reviewStatus === 'all')>All game quests</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    </form>

                    <span class="badge rounded-pill text-bg-light border px-3 py-2">
                        Page {{ $tasks->currentPage() }} of {{ max(1, $tasks->lastPage()) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table task-table align-middle mb-0">

                <thead>
                    <tr>
                        <th class="ps-4">Learning Task</th>
                        <th>Student</th>
                        <th>Game</th>
                        <th>Type</th>
                        <th>Difficulty</th>
                        <th>Reward</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($tasks as $task)

                        <tr>
                            {{-- Task --}}
                            <td class="ps-4 task-title-cell">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="task-row-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="fw-semibold text-dark mb-1">
                                            {{ $task->title }}
                                        </div>
                                        <small class="text-muted">
                                            {{ Str::limit($task->description, 65) }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            {{-- Student --}}
                            <td>
                                @if($task->user)
                                    <div class="fw-semibold text-dark">
                                        {{ $task->user->name }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $task->user->email }}
                                    </small>
                                @else
                                    <span class="text-muted fst-italic">
                                        Student removed
                                    </span>
                                @endif
                            </td>

                            {{-- Game --}}
                            <td>
                                @if($task->game)
                                    <span class="game-label">
                                        <i class="bi bi-controller me-1"></i>
                                        {{ $task->game->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">
                                        <i class="bi bi-dash-circle me-1"></i>
                                        No Game
                                    </span>
                                @endif
                            </td>

                            {{-- Type --}}
                            <td>
                                <code class="task-type">
                                    {{ $task->game_type ?? 'N/A' }}
                                </code>
                            </td>

                            {{-- Difficulty --}}
                            <td>
                                @php
                                    $difficulty = strtolower($task->difficulty ?? '');
                                    $difficultyClass = match($difficulty) {
                                        'easy' => 'success',
                                        'medium' => 'warning',
                                        'hard' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp

                                <span class="badge rounded-pill bg-{{ $difficultyClass }}-subtle text-{{ $difficultyClass }} px-3 py-2">
                                    {{ ucfirst($task->difficulty ?? 'N/A') }}
                                </span>
                            </td>

                            {{-- XP --}}
                            <td>
                                <span class="xp-label">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>
                                    {{ $task->xp ?? 0 }} XP
                                </span>
                            </td>

                            {{-- Review and completion status --}}
                            <td>
                                @php
                                    $reviewStatus = $task->review_status ?? 'pending_review';
                                    $reviewBadge = match($reviewStatus) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge rounded-pill text-bg-{{ $reviewBadge }} mb-1">
                                    {{ $reviewStatus === 'pending_review' ? 'Needs review' : ucfirst($reviewStatus) }}
                                </span>
                                @if($task->status === 'completed')
                                    <span class="status-pill status-completed">
                                        <i class="bi bi-check-circle-fill"></i>
                                        Completed
                                    </span>
                                @else
                                    <span class="status-pill status-pending">
                                        <i class="bi bi-clock-fill"></i>
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.tasks.show', $task->id) }}"
                                   class="btn btn-sm btn-outline-primary rounded-3 px-3 task-view-btn">
                                    View
                                    <i class="bi bi-arrow-up-right ms-1"></i>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state-icon mx-auto mb-3">
                                    <i class="bi bi-journal-x"></i>
                                </div>
                                <h5 class="fw-bold">No Learning Tasks Found</h5>
                                <p class="text-muted mb-0">
                                    Student learning tasks will appear here when available.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- Pagination Footer --}}
        @if($tasks->hasPages())
            <div class="card-footer bg-white border-0 px-4 py-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                    <small class="text-muted">
                        Showing
                        <strong>{{ $tasks->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $tasks->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $tasks->total() }}</strong>
                        tasks
                    </small>

                    <div class="task-pagination">
                        {{ $tasks->onEachSide(1)->links() }}
                    </div>

                </div>
            </div>
        @endif

    </div>

</div>

<style>
    .task-hero {
        background: linear-gradient(120deg, #173b8f 0%, #315bd6 55%, #667eea 100%);
        box-shadow: 0 12px 30px rgba(49, 91, 214, .16);
    }

    .task-hero-icon {
        width: 60px;
        height: 60px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 18px;
        background: rgba(255, 255, 255, .16);
        font-size: 1.8rem;
    }

    .hero-total {
        display: inline-block;
        padding: 12px 18px;
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 14px;
        background: rgba(255, 255, 255, .12);
        font-weight: 600;
    }

    .task-stat-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .task-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(0, 0, 0, .07) !important;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 15px;
        font-size: 1.4rem;
    }

    .task-table thead th {
        padding: 16px 12px;
        background: #f7f9fc;
        color: #667085;
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .045em;
        white-space: nowrap;
        border-bottom: 1px solid #edf0f5;
    }

    .task-table tbody td {
        padding-top: 17px;
        padding-bottom: 17px;
        border-color: #f0f2f6;
    }

    .task-table tbody tr {
        transition: background-color .2s ease;
    }

    .task-table tbody tr:hover {
        background-color: #f8faff;
    }

    .task-row-icon {
        width: 40px;
        height: 40px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
        border-radius: 12px;
        color: #315bd6;
        background: #edf2ff;
        font-size: 1.15rem;
    }

    .task-title-cell {
        min-width: 240px;
        max-width: 320px;
    }

    .game-label {
        color: #475467;
        font-size: .875rem;
        white-space: nowrap;
    }

    .task-type {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 6px;
        background: #f2f4f7;
        color: #475467;
        font-size: .78rem;
    }

    .xp-label {
        color: #a16207;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 50px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-completed {
        color: #15803d;
        background: #dcfce7;
    }

    .status-pending {
        color: #a16207;
        background: #fef3c7;
    }

    .task-view-btn {
        white-space: nowrap;
        transition: all .2s ease;
    }

    .task-view-btn:hover {
        transform: translateY(-1px);
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        display: grid;
        place-items: center;
        border-radius: 22px;
        background: #f2f4f7;
        color: #98a2b3;
        font-size: 2rem;
    }

    .task-pagination nav > div:first-child {
        display: none;
    }

    .task-pagination nav > div:last-child {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 5px;
    }

    @media (max-width: 767.98px) {
        .task-hero {
            padding: 1.5rem !important;
        }

        .task-hero-icon {
            width: 48px;
            height: 48px;
            font-size: 1.4rem;
        }

        .task-pagination nav > div:last-child {
            justify-content: center;
        }
    }
</style>

@endsection