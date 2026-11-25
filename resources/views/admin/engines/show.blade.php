@extends('admin.layouts.app')

@section('title', 'Engine Details')

@section('page-heading', 'Game Engine Details')

@section('content')

<style>
    .engine-detail-page {
        --navy: #17233e;
        --navy-light: #2b4162;
        --emerald: #14966c;
        --blue: #527be8;
        --purple: #8068d9;
        --text: #26364d;
        --muted: #8290a2;
        --border: #e8edf3;
    }

    .engine-detail-page .hero-panel {
        position: relative;
        overflow: hidden;
        padding: 34px;
        border-radius: 23px;
        color: white;
        background: linear-gradient(115deg, #17233e 0%, #263d61 57%, #147b65 100%);
        box-shadow: 0 12px 30px rgba(23, 35, 62, .14);
    }

    .engine-detail-page .hero-panel::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -65px;
        top: -130px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .engine-detail-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .engine-detail-page .hero-icon {
        width: 77px;
        height: 77px;
        border-radius: 22px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        flex-shrink: 0;
    }

    .engine-detail-page .hero-label {
        color: #a7e9d1;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .engine-detail-page .hero-title {
        font-size: 31px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 8px;
    }

    .engine-detail-page .hero-description {
        color: rgba(255, 255, 255, .75);
        font-size: 15px;
        margin: 0;
    }

    .engine-detail-page .engine-key {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        padding: 9px 13px;
        border-radius: 9px;
        background: rgba(255, 255, 255, .13);
        border: 1px solid rgba(255, 255, 255, .19);
        color: #e7fff3;
        font-size: 13px;
        font-weight: 700;
    }

    .engine-detail-page .back-btn {
        color: white;
        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 11px;
        padding: 11px 17px;
        font-size: 14px;
        font-weight: 700;
        transition: .2s;
        white-space: nowrap;
    }

    .engine-detail-page .back-btn:hover {
        background: white;
        color: var(--navy);
    }

    .engine-detail-page .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(31, 49, 75, .045);
        transition: .2s;
    }

    .engine-detail-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(31, 49, 75, .08);
    }

    .engine-detail-page .stat-icon {
        width: 47px;
        height: 47px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
        margin-bottom: 15px;
    }

    .engine-detail-page .stat-number {
        color: var(--navy);
        font-size: 30px;
        font-weight: 850;
        line-height: 1.2;
    }

    .engine-detail-page .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 6px;
    }

    .engine-detail-page .activity-panel {
        height: 100%;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 19px;
        box-shadow: 0 5px 20px rgba(31, 49, 75, .045);
        overflow: hidden;
    }

    .engine-detail-page .activity-header {
        padding: 23px;
        background: linear-gradient(110deg, #fbfcfe, #f5f9f7);
        border-bottom: 1px solid var(--border);
    }

    .engine-detail-page .activity-icon {
        width: 45px;
        height: 45px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        flex-shrink: 0;
    }

    .engine-detail-page .activity-title {
        font-size: 19px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 5px;
    }

    .engine-detail-page .activity-description {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
        line-height: 1.5;
    }

    .engine-detail-page .count-pill {
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .engine-detail-page .game-item,
    .engine-detail-page .task-item {
        border: 1px solid #e9eef2;
        border-radius: 14px;
        padding: 17px;
        margin-bottom: 13px;
        background: #fff;
        transition: .2s;
    }

    .engine-detail-page .game-item:hover,
    .engine-detail-page .task-item:hover {
        border-color: #b9dfcf;
        background: #fbfefc;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(31, 49, 75, .045);
    }

    .engine-detail-page .game-item:last-child,
    .engine-detail-page .task-item:last-child {
        margin-bottom: 0;
    }

    .engine-detail-page .item-icon {
        width: 41px;
        height: 41px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
    }

    .engine-detail-page .item-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--navy);
        margin-bottom: 5px;
        overflow-wrap: anywhere;
    }

    .engine-detail-page .item-meta {
        color: var(--muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .engine-detail-page .view-btn {
        border: 1px solid #dce7f6;
        color: #456fc9;
        background: #f5f8ff;
        border-radius: 9px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        transition: .2s;
    }

    .engine-detail-page .view-btn:hover {
        background: #456fc9;
        color: #fff;
        border-color: #456fc9;
    }

    .engine-detail-page .student-info {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 9px;
        color: #64758a;
        font-size: 13px;
        font-weight: 600;
    }

    .engine-detail-page .student-avatar {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e5f5ec;
        color: #16835a;
        font-size: 13px;
    }

    .engine-detail-page .task-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 13px;
    }

    .engine-detail-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 850;
    }

    .engine-detail-page .status-completed {
        background: #e5f7ed;
        color: #14774c;
    }

    .engine-detail-page .status-pending {
        background: #fff3d9;
        color: #9b6900;
    }

    .engine-detail-page .status-rejected {
        background: #fde8e7;
        color: #b63e38;
    }

    .engine-detail-page .status-default {
        background: #edf1ee;
        color: #65776c;
    }

    .engine-detail-page .xp-badge {
        background: #fff4df;
        color: #a36b12;
        padding: 7px 11px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 850;
    }

    .engine-detail-page .empty-state {
        text-align: center;
        padding: 50px 15px;
    }

    .engine-detail-page .empty-icon {
        width: 65px;
        height: 65px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        background: #eaf4ef;
        color: #17825b;
        font-size: 29px;
        margin: 0 auto 15px;
    }

    .engine-detail-page .empty-title {
        font-size: 17px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 6px;
    }

    .engine-detail-page .empty-text {
        color: var(--muted);
        font-size: 13px;
        margin: 0;
    }

    .engine-detail-page .panel-body {
        padding: 21px;
    }

    .engine-detail-page .panel-footer {
        padding: 18px 21px;
        border-top: 1px solid var(--border);
        background: #fff;
    }

    .engine-detail-page .pagination {
        gap: 5px;
        margin: 0;
    }

    .engine-detail-page .page-link {
        border: 1px solid #e2e9ef;
        border-radius: 9px !important;
        padding: 8px 12px;
        color: #426b54;
        font-weight: 700;
        box-shadow: none;
    }

    .engine-detail-page .page-item.active .page-link {
        background: #147b56;
        border-color: #147b56;
        color: #fff;
    }

    .engine-detail-page .page-item.disabled .page-link {
        color: #aab5ae;
        background: #f8faf9;
    }

    @media(max-width: 767px) {
        .engine-detail-page .hero-panel {
            padding: 23px 19px;
        }

        .engine-detail-page .hero-title {
            font-size: 25px;
        }

        .engine-detail-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .engine-detail-page .hero-icon {
            width: 61px;
            height: 61px;
            font-size: 29px;
        }

        .engine-detail-page .activity-header {
            padding: 18px;
        }

        .engine-detail-page .panel-body {
            padding: 16px;
        }
    }
</style>


<div class="engine-detail-page">

    @php
    $engineName = match(strtolower($gameType)) {
    'arithmetic_speed' => 'Arithmetic Speed',
    'algorithm_race' => 'Algorithm Race',
    'sorting_challenge' => 'Sorting Challenge',
    'creative_coding' => 'Creative Coding',
    'physics_quiz' => 'Physics Quiz',
    default => ucwords(str_replace('_', ' ', $gameType)),
    };

    $engineIcon = match(strtolower($gameType)) {
    'arithmetic_speed' => 'bi-calculator',
    'algorithm_race' => 'bi-code-slash',
    'sorting_challenge' => 'bi-sort-down',
    'creative_coding' => 'bi-braces',
    'physics_quiz' => 'bi-atom',
    default => 'bi-controller',
    };
    @endphp


    {{-- HERO --}}
    <div class="hero-panel mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi {{ $engineIcon }}"></i>
                    </div>

                    <div>

                        <div class="hero-label">
                            <i class="bi bi-cpu me-1"></i>
                            ECOQUEST / ENGINE INFRASTRUCTURE
                        </div>

                        <h2 class="hero-title">
                            {{ $engineName }}
                        </h2>

                        <p class="hero-description">
                            Explore the games and learning activities powered by this engine.
                        </p>

                        <span class="engine-key">
                            <i class="bi bi-code-square"></i>
                            {{ $gameType }}
                        </span>

                    </div>

                </div>

                <a href="{{ route('admin.engines.index') }}"
                    class="btn back-btn">

                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Engines

                </a>

            </div>

        </div>

    </div>


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e9efff;color:#527be8;">
                    <i class="bi bi-controller"></i>
                </div>

                <div class="stat-number">
                    {{ $games->total() }}
                </div>

                <div class="stat-label">
                    Total Games Using This Engine
                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e5f5ec;color:#16835a;">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="stat-number">
                    {{ $tasks->total() }}
                </div>

                <div class="stat-label">
                    Total Learning Tasks
                </div>

            </div>

        </div>

    </div>


    {{-- ACTIVITY PANELS --}}
    <div class="row g-4">


        {{-- GAMES --}}
        <div class="col-xl-6">

            <div class="activity-panel">

                <div class="activity-header">

                    <div class="d-flex justify-content-between align-items-center gap-2">

                        <div class="d-flex align-items-center gap-3">

                            <div class="activity-icon"
                                style="background:#e9efff;color:#527be8;">
                                <i class="bi bi-controller"></i>
                            </div>

                            <div>
                                <h5 class="activity-title">
                                    Games Using This Engine
                                </h5>

                                <p class="activity-description">
                                    Game library entries connected to this engine.
                                </p>
                            </div>

                        </div>

                        <span class="count-pill"
                            style="background:#e9efff;color:#456fc9;">
                            {{ $games->total() }} Games
                        </span>

                    </div>

                </div>


                <div class="panel-body">

                    @forelse($games as $game)

                    <div class="game-item">

                        <div class="d-flex justify-content-between align-items-center gap-3">

                            <div class="d-flex align-items-center gap-3">

                                <div class="item-icon"
                                    style="background:#eaf0ff;color:#527be8;">
                                    <i class="bi bi-controller"></i>
                                </div>

                                <div>
                                    <h6 class="item-title">
                                        {{ $game->name }}
                                    </h6>

                                    <div class="item-meta">
                                        <i class="bi bi-grid me-1"></i>
                                        {{ $game->category ?? 'Uncategorized' }}
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('admin.games.show', $game->id) }}"
                                class="btn view-btn">

                                <i class="bi bi-eye me-1"></i>
                                View

                            </a>

                        </div>

                    </div>

                    @empty

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="bi bi-controller"></i>
                        </div>

                        <h6 class="empty-title">
                            No Games Connected
                        </h6>

                        <p class="empty-text">
                            No games are currently using this engine.
                        </p>

                    </div>

                    @endforelse

                </div>


                @if($games->hasPages())

                <div class="panel-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <span class="text-muted" style="font-size:12px;">
                            Showing {{ $games->firstItem() }}–{{ $games->lastItem() }}
                            of {{ $games->total() }} games
                        </span>

                        {{ $games->appends(request()->except('games_page'))->links('pagination::bootstrap-5') }}

                    </div>

                </div>

                @endif

            </div>

        </div>


        {{-- LEARNING TASKS --}}
        <div class="col-xl-6">

            <div class="activity-panel">

                <div class="activity-header">

                    <div class="d-flex justify-content-between align-items-center gap-2">

                        <div class="d-flex align-items-center gap-3">

                            <div class="activity-icon"
                                style="background:#e5f5ec;color:#16835a;">
                                <i class="bi bi-journal-check"></i>
                            </div>

                            <div>
                                <h5 class="activity-title">
                                    Learning Tasks
                                </h5>

                                <p class="activity-description">
                                    Student tasks associated with this engine.
                                </p>
                            </div>

                        </div>

                        <span class="count-pill"
                            style="background:#e5f5ec;color:#16835a;">
                            {{ $tasks->total() }} Tasks
                        </span>

                    </div>

                </div>


                <div class="panel-body">

                    @forelse($tasks as $task)

                    @php
                    $status = strtolower($task->status ?? 'unknown');

                    $statusClass = match($status) {
                    'completed', 'accepted' => 'status-completed',
                    'pending', 'submitted', 'in_progress', 'revision_requested' => 'status-pending',
                    'rejected', 'failed' => 'status-rejected',
                    default => 'status-default',
                    };

                    $statusIcon = match($status) {
                    'completed', 'accepted' => 'bi-check-circle-fill',
                    'pending', 'submitted', 'in_progress', 'revision_requested' => 'bi-clock-fill',
                    'rejected', 'failed' => 'bi-x-circle-fill',
                    default => 'bi-circle',
                    };
                    @endphp

                    <div class="task-item">

                        <h6 class="item-title">
                            {{ $task->title }}
                        </h6>

                        @if($task->user)

                        <div class="student-info">
                            <span class="student-avatar">
                                <i class="bi bi-person-fill"></i>
                            </span>

                            <span>
                                {{ $task->user->name }}
                            </span>
                        </div>

                        @else

                        <div class="student-info">
                            <span class="student-avatar">
                                <i class="bi bi-person"></i>
                            </span>

                            <span>
                                Student unavailable
                            </span>
                        </div>

                        @endif


                        <div class="task-footer">

                            <span class="status-badge {{ $statusClass }}">
                                <i class="bi {{ $statusIcon }}"></i>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </span>

                            <span class="xp-badge">
                                <i class="bi bi-lightning-charge-fill me-1"></i>
                                {{ $task->xp ?? 0 }} XP
                            </span>

                        </div>

                    </div>

                    @empty

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="bi bi-journal-x"></i>
                        </div>

                        <h6 class="empty-title">
                            No Learning Tasks
                        </h6>

                        <p class="empty-text">
                            No learning tasks are currently using this engine.
                        </p>

                    </div>

                    @endforelse

                </div>


                @if($tasks->hasPages())

                <div class="panel-footer">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <span class="text-muted" style="font-size:12px;">
                            Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }}
                            of {{ $tasks->total() }} tasks
                        </span>

                        {{ $tasks->appends(request()->except('tasks_page'))->links('pagination::bootstrap-5') }}

                    </div>

                </div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection