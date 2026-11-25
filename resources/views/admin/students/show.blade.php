
@extends('admin.layouts.app')

@section('title', 'Student Details')
@section('page-heading', 'Student Profile')

@section('content')

<style>
    :root {
        --student-green: #198754;
        --student-dark: #16452f;
        --student-light: #eaf6ee;
        --student-border: #e7eee9;
        --student-muted: #7b8b80;
    }

    .student-page {
        padding-bottom: 35px;
    }

    /* Profile Header */
    .profile-banner {
        position: relative;
        overflow: hidden;
        background: linear-gradient(120deg, #153f2d, #198754);
        border-radius: 20px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
    }

    .profile-banner::after {
        content: "🌿";
        position: absolute;
        right: 45px;
        top: -25px;
        font-size: 150px;
        opacity: .12;
        transform: rotate(-20deg);
        pointer-events: none;
    }

    .profile-main {
        display: flex;
        align-items: center;
        gap: 19px;
        position: relative;
        z-index: 1;
    }

    .profile-avatar {
        width: 78px;
        height: 78px;
        flex-shrink: 0;
        border-radius: 22px;
        background: rgba(255,255,255,.18);
        border: 2px solid rgba(255,255,255,.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 750;
        color: white;
    }

    .profile-info h2 {
        font-size: 25px;
        font-weight: 750;
        margin-bottom: 6px;
    }

    .profile-info p {
        font-size: 13px;
        color: rgba(255,255,255,.78);
        margin-bottom: 10px;
    }

    .profile-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 11px;
        border-radius: 30px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.2);
        font-size: 11px;
        font-weight: 650;
    }

    .back-button {
        position: relative;
        z-index: 2;
        border: 1px solid rgba(255,255,255,.4);
        color: white;
        border-radius: 9px;
        padding: 9px 15px;
        font-size: 12px;
        font-weight: 600;
        background: rgba(255,255,255,.08);
        text-decoration: none;
        white-space: nowrap;
        transition: .2s;
    }

    .back-button:hover {
        background: white;
        color: var(--student-dark);
    }

    /* General Cards */
    .student-card {
        border: 1px solid var(--student-border);
        border-radius: 16px;
        background: white;
        box-shadow: 0 3px 14px rgba(20,70,40,.035);
        overflow: hidden;
        height: 100%;
        transition: .2s ease;
    }

    .student-card:hover {
        box-shadow: 0 7px 20px rgba(20,70,40,.07);
    }

    .card-inner {
        padding: 23px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        font-weight: 750;
        color: var(--student-dark);
        margin-bottom: 22px;
    }

    .section-title i {
        color: var(--student-green);
        font-size: 19px;
    }

    /* Information */
    .info-item {
        padding: 15px;
        height: 100%;
        background: #f8fbf9;
        border: 1px solid #edf3ef;
        border-radius: 11px;
    }

    .info-label {
        display: block;
        color: var(--student-muted);
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 7px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .info-value {
        color: #263d30;
        font-size: 13px;
        font-weight: 650;
        word-break: break-word;
    }

    /* Stat Cards */
    .stat-card {
        border: 1px solid var(--student-border);
        border-radius: 16px;
        padding: 22px;
        background: white;
        height: 100%;
        position: relative;
        overflow: hidden;
        transition: .2s ease;
        box-shadow: 0 3px 14px rgba(20,70,40,.035);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(20,70,40,.07);
    }

    .stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 19px;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 13px;
        font-size: 21px;
    }

    .icon-xp {
        background: #fff3d9;
        color: #d99a16;
    }

    .icon-level {
        background: #e8efff;
        color: #5278d6;
    }

    .icon-task {
        background: #e1f5e9;
        color: #198754;
    }

    .stat-label {
        font-size: 12px;
        color: var(--student-muted);
        font-weight: 600;
    }

    .stat-number {
        font-size: 30px;
        font-weight: 800;
        color: var(--student-dark);
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .stat-description {
        font-size: 11px;
        color: var(--student-muted);
    }

    .stat-decoration {
        position: absolute;
        right: -12px;
        bottom: -20px;
        font-size: 75px;
        opacity: .035;
    }

    /* Task Sections */
    .task-card {
        border: 1px solid var(--student-border);
        border-radius: 16px;
        background: white;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(20,70,40,.035);
    }

    .task-header {
        padding: 21px 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        border-bottom: 1px solid var(--student-border);
    }

    .task-heading {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .task-heading-icon {
        width: 40px;
        height: 40px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .completed-icon {
        background: #e3f5e9;
        color: #198754;
    }

    .pending-icon {
        background: #fff3dc;
        color: #d69b25;
    }

    .task-heading h5 {
        color: var(--student-dark);
        font-size: 15px;
        font-weight: 750;
        margin-bottom: 4px;
    }

    .task-heading small {
        font-size: 11px;
        color: var(--student-muted);
    }

    .task-count {
        padding: 7px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
    }

    .completed-count {
        color: #198754;
        background: #e6f5eb;
    }

    .pending-count {
        color: #a8750d;
        background: #fff3dc;
    }

    /* Tables */
    .task-table {
        margin-bottom: 0;
    }

    .task-table thead th {
        background: #f8fbf9;
        color: #718078;
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: .5px;
        font-weight: 750;
        padding: 15px 20px;
        border-bottom: 1px solid var(--student-border);
        white-space: nowrap;
    }

    .task-table tbody td {
        padding: 16px 20px;
        font-size: 12px;
        color: #405449;
        vertical-align: middle;
        border-bottom: 1px solid #f0f4f1;
    }

    .task-table tbody tr:last-child td {
        border-bottom: none;
    }

    .task-table tbody tr:hover {
        background: #f9fcfa;
    }

    .task-name {
        color: #263d30;
        font-weight: 650;
    }

    .game-label {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 7px;
        background: #f0f5f2;
        color: #52665a;
        font-size: 11px;
        font-weight: 600;
    }

    .xp-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #bd8514;
        background: #fff8e8;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 750;
        white-space: nowrap;
    }

    .status-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border-radius: 30px;
        padding: 6px 11px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-completed {
        color: #16834b;
        background: #e6f5eb;
    }

    .status-pending {
        color: #a8750d;
        background: #fff3dc;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 15px !important;
    }

    .empty-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        border-radius: 50%;
        background: #f0f6f2;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        color: #8da797;
    }

    .empty-state strong {
        display: block;
        font-size: 13px;
        color: #405449;
        margin-bottom: 5px;
    }

    .empty-state small {
        color: #8b9990;
        font-size: 11px;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 16px 22px;
        border-top: 1px solid var(--student-border);
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: flex-end;
    }

    .pagination-wrapper .pagination {
        margin-bottom: 0;
        flex-wrap: wrap;
        gap: 4px;
    }

    .pagination-wrapper .page-link {
        border-radius: 7px !important;
        border: 1px solid #e5eee8;
        color: #42644f;
        font-size: 12px;
    }

    .pagination-wrapper .active .page-link {
        background: var(--student-green);
        border-color: var(--student-green);
        color: white;
    }

    @media(max-width: 767px) {
        .profile-banner {
            padding: 22px;
        }

        .profile-banner::after {
            right: -15px;
            font-size: 95px;
        }

        .profile-main {
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .profile-info h2 {
            font-size: 21px;
        }

        .profile-avatar {
            width: 62px;
            height: 62px;
            border-radius: 17px;
            font-size: 25px;
        }

        .card-inner {
            padding: 18px;
        }

        .task-header {
            padding: 17px;
        }

        .task-table thead th,
        .task-table tbody td {
            padding: 13px;
        }

        .pagination-wrapper nav {
            justify-content: center;
        }
    }
</style>


<div class="container-fluid student-page">

    {{-- PROFILE HEADER --}}

    <div class="profile-banner">

        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">

            <div class="profile-main">

                <div class="profile-avatar">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>

                <div class="profile-info">

                    <div class="profile-tag mb-2">
                        <i class="bi bi-mortarboard-fill"></i>
                        STUDENT PROFILE
                    </div>

                    <h2>{{ $student->name }}</h2>

                    <p>
                        <i class="bi bi-envelope me-1"></i>
                        {{ $student->email }}
                    </p>

                    <span class="profile-tag">
                        <i class="bi bi-patch-check-fill"></i>
                        Active Student
                    </span>

                </div>

            </div>

            <a href="{{ route('admin.students.index') }}"
               class="back-button">

                <i class="bi bi-arrow-left me-1"></i>
                Back to Students

            </a>

        </div>

    </div>


    {{-- BASIC INFORMATION --}}

    <div class="student-card mb-4">

        <div class="card-inner">

            <h5 class="section-title">
                <i class="bi bi-person-vcard"></i>
                Basic Information
            </h5>

            <div class="row g-3">

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="info-item">
                        <span class="info-label">Full Name</span>
                        <div class="info-value">
                            {{ $student->name }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="info-item">
                        <span class="info-label">Email Address</span>
                        <div class="info-value">
                            {{ $student->email }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="info-item">
                        <span class="info-label">Registration Date</span>
                        <div class="info-value">
                            {{ $student->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4">
                    <div class="info-item">
                        <span class="info-label">Account Role</span>
                        <div class="info-value">
                            <span class="status-label status-completed">
                                <i class="bi bi-person-check-fill"></i>
                                Student
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- PERFORMANCE STATISTICS --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL XP --}}

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">
                    <span class="stat-label">TOTAL EXPERIENCE POINTS</span>

                    <div class="stat-icon icon-xp">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                </div>

                <h2 class="stat-number">
                    {{ number_format($student->progress->total_xp ?? 0) }}
                </h2>

                <div class="stat-description">
                    <i class="bi bi-stars me-1"></i>
                    Experience earned through learning
                </div>

                <i class="bi bi-lightning-charge-fill stat-decoration"></i>

            </div>

        </div>


        {{-- LEVEL --}}

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">
                    <span class="stat-label">CURRENT LEVEL</span>

                    <div class="stat-icon icon-level">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>

                <h2 class="stat-number">
                    {{ $student->progress->level ?? 1 }}
                </h2>

                <div class="stat-description">
                    <i class="bi bi-graph-up-arrow me-1"></i>
                    Student achievement level
                </div>

                <i class="bi bi-trophy-fill stat-decoration"></i>

            </div>

        </div>


        {{-- COMPLETED TASKS --}}

        <div class="col-12 col-sm-6 col-xl-4">

            <div class="stat-card">

                <div class="stat-top">
                    <span class="stat-label">COMPLETED TASKS</span>

                    <div class="stat-icon icon-task">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                </div>

                <h2 class="stat-number">
                    {{ number_format($student->progress->completed_tasks ?? 0) }}
                </h2>

                <div class="stat-description">
                    <i class="bi bi-check-circle me-1"></i>
                    Successfully completed activities
                </div>

                <i class="bi bi-check2-circle stat-decoration"></i>

            </div>

        </div>

    </div>


    {{-- COMPLETED TASKS --}}

    <div class="task-card mb-4">

        <div class="task-header">

            <div class="task-heading">

                <div class="task-heading-icon completed-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <div>
                    <h5>Completed Tasks</h5>
                    <small>Successfully completed learning activities</small>
                </div>

            </div>

            <span class="task-count completed-count">
                {{ $completedTasks->total() }}
                {{ $completedTasks->total() === 1 ? 'Task' : 'Tasks' }}
            </span>

        </div>


        <div class="table-responsive">

            <table class="table task-table">

                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Game / Activity</th>
                        <th>XP Earned</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($completedTasks as $task)

                        <tr>

                            <td>
                                <span class="task-name">
                                    <i class="bi bi-journal-check text-success me-2"></i>
                                    {{ $task->title }}
                                </span>
                            </td>

                            <td>
                                <span class="game-label">
                                    {{ $task->game_type ?? 'General' }}
                                </span>
                            </td>

                            <td>
                                <span class="xp-label">
                                    <i class="bi bi-lightning-fill"></i>
                                    {{ $task->xp }} XP
                                </span>
                            </td>

                            <td>
                                <span class="status-label status-completed">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Completed
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="empty-state">

                                <div class="empty-icon">
                                    <i class="bi bi-clipboard-check"></i>
                                </div>

                                <strong>No completed tasks yet</strong>

                                <small>
                                    Completed learning activities will appear here.
                                </small>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($completedTasks->hasPages())

            <div class="pagination-wrapper">
                {{ $completedTasks->links() }}
            </div>

        @endif

    </div>


    {{-- PENDING TASKS --}}

    <div class="task-card">

        <div class="task-header">

            <div class="task-heading">

                <div class="task-heading-icon pending-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div>
                    <h5>Pending Tasks</h5>
                    <small>Learning activities awaiting completion</small>
                </div>

            </div>

            <span class="task-count pending-count">
                {{ $pendingTasks->total() }}
                {{ $pendingTasks->total() === 1 ? 'Task' : 'Tasks' }}
            </span>

        </div>


        <div class="table-responsive">

            <table class="table task-table">

                <thead>
                    <tr>
                        <th>Task Name</th>
                        <th>Game / Activity</th>
                        <th>Available XP</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pendingTasks as $task)

                        <tr>

                            <td>
                                <span class="task-name">
                                    <i class="bi bi-journal-text text-warning me-2"></i>
                                    {{ $task->title }}
                                </span>
                            </td>

                            <td>
                                <span class="game-label">
                                    {{ $task->game_type ?? 'General' }}
                                </span>
                            </td>

                            <td>
                                <span class="xp-label">
                                    <i class="bi bi-lightning-fill"></i>
                                    {{ $task->xp }} XP
                                </span>
                            </td>

                            <td>
                                <span class="status-label status-pending">
                                    <i class="bi bi-clock-fill"></i>
                                    Pending
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="empty-state">

                                <div class="empty-icon">
                                    <i class="bi bi-emoji-smile"></i>
                                </div>

                                <strong>No pending tasks</strong>

                                <small>
                                    This student has no pending activities right now.
                                </small>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($pendingTasks->hasPages())

            <div class="pagination-wrapper">
                {{ $pendingTasks->links() }}
            </div>

        @endif

    </div>

</div>

@endsection
