@extends('admin.layouts.app')

@section('title', 'Game Content Details')

@section('page-heading', 'Game Content Details')

@section('content')

<style>
    .content-details {
        --forest: #153d2e;
        --forest-light: #205c43;
        --emerald: #15966b;
        --cream: #f7f8f2;
        --text: #243b30;
        --muted: #829087;
        --border: #e8eee9;
        color: var(--text);
    }

    .content-details .hero {
        position: relative;
        overflow: hidden;
        padding: 35px;
        border-radius: 23px;
        color: white;
        background: linear-gradient(115deg, #123728, #1d6547 65%, #168568);
        box-shadow: 0 12px 30px rgba(21, 61, 46, .15);
    }

    .content-details .hero::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -75px;
        top: -140px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        box-shadow: 0 0 0 38px rgba(255, 255, 255, .035),
            0 0 0 75px rgba(255, 255, 255, .025);
    }

    .content-details .hero-inner {
        position: relative;
        z-index: 1;
    }

    .content-details .hero-icon {
        width: 76px;
        height: 76px;
        border-radius: 21px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 36px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .2);
    }

    .content-details .eyebrow {
        color: #b0ead1;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .content-details .hero-title {
        font-size: 32px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 8px;
    }

    .content-details .hero-description {
        color: rgba(255, 255, 255, .78);
        font-size: 15px;
        line-height: 1.7;
        max-width: 650px;
        margin: 0;
    }

    .content-details .engine-label {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, .13);
        border: 1px solid rgba(255, 255, 255, .19);
        color: white;
        border-radius: 30px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 700;
        margin-top: 16px;
    }

    .content-details .back-button {
        border: 1px solid rgba(255, 255, 255, .4);
        color: white;
        border-radius: 11px;
        padding: 11px 17px;
        font-size: 14px;
        font-weight: 750;
        transition: .2s;
        white-space: nowrap;
    }

    .content-details .back-button:hover {
        background: white;
        color: var(--forest);
    }

    .content-details .stat-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 21px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(21, 61, 46, .04);
    }

    .content-details .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 14px;
    }

    .content-details .stat-number {
        color: var(--forest);
        font-size: 29px;
        font-weight: 850;
        line-height: 1.2;
    }

    .content-details .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 5px;
    }

    .content-details .panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 19px;
        box-shadow: 0 5px 20px rgba(21, 61, 46, .045);
        overflow: hidden;
    }

    .content-details .panel-header {
        padding: 23px 25px;
        border-bottom: 1px solid var(--border);
        background: linear-gradient(110deg, #fbfcf9, #f4f8f4);
    }

    .content-details .panel-title {
        color: var(--forest);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 5px;
    }

    .content-details .panel-subtitle {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .content-details .info-row {
        padding: 16px 0;
        border-bottom: 1px solid #edf1ed;
    }

    .content-details .info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .content-details .info-label {
        color: #84948a;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .7px;
        text-transform: uppercase;
        margin-bottom: 7px;
    }

    .content-details .info-value {
        color: var(--forest);
        font-size: 16px;
        font-weight: 750;
        overflow-wrap: anywhere;
    }

    .content-details .engine-code {
        display: inline-block;
        background: #f0efff;
        color: #6b5ab9;
        border: 1px solid #e4e0ff;
        border-radius: 8px;
        padding: 7px 11px;
        font-size: 12px;
        font-weight: 750;
    }

    .content-details .category-tag {
        display: inline-flex;
        padding: 8px 13px;
        border-radius: 30px;
        background: #e7f5ed;
        color: #16754f;
        font-size: 13px;
        font-weight: 800;
    }

    .content-details .difficulty-tag {
        display: inline-flex;
        padding: 8px 13px;
        border-radius: 30px;
        background: #fff3df;
        color: #a66c16;
        font-size: 13px;
        font-weight: 800;
    }

    .content-details .task-card {
        padding: 23px 25px;
        border-bottom: 1px solid #edf1ed;
        transition: .2s;
    }

    .content-details .task-card:last-child {
        border-bottom: 0;
    }

    .content-details .task-card:hover {
        background: #fafffb;
    }

    .content-details .task-number {
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #e8f5ed;
        color: #16845a;
        font-size: 19px;
    }

    .content-details .task-title {
        color: var(--forest);
        font-size: 17px;
        font-weight: 850;
        margin-bottom: 7px;
    }

    .content-details .task-description {
        color: #7d8d83;
        font-size: 14px;
        line-height: 1.7;
        margin-bottom: 15px;
    }

    .content-details .task-meta {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 9px;
    }

    .content-details .xp-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #fff4dc;
        color: #a66d11;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 850;
    }

    .content-details .task-difficulty {
        background: #f0efff;
        color: #6d5bc2;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .content-details .status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 13px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .content-details .status-completed {
        background: #e4f7ec;
        color: #167447;
    }

    .content-details .status-pending {
        background: #fff3d9;
        color: #9b6900;
    }

    .content-details .status-other {
        background: #edf1ee;
        color: #65776c;
    }

    .content-details .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .content-details .empty-icon {
        width: 76px;
        height: 76px;
        border-radius: 23px;
        background: #eaf5ee;
        color: #16845a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 34px;
        margin: 0 auto 17px;
    }

    .content-details .empty-title {
        color: var(--forest);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 7px;
    }

    .content-details .empty-description {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .content-details .content-count {
        background: #e7f5ed;
        color: #16754f;
        padding: 9px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 850;
        white-space: nowrap;
    }

    @media(max-width: 767px) {
        .content-details .hero {
            padding: 23px 19px;
        }

        .content-details .hero-title {
            font-size: 25px;
        }

        .content-details .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .content-details .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .content-details .panel-header {
            padding: 20px 17px;
        }

        .content-details .task-card {
            padding: 20px 16px;
        }

        .content-details .task-layout {
            flex-direction: column;
            align-items: flex-start !important;
        }
    }
</style>


<div class="content-details">

    {{-- HERO SECTION --}}
    <div class="hero mb-4">

        <div class="hero-inner">

            <div class="d-flex justify-content-between align-items-start hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-controller"></i>
                    </div>

                    <div>

                        <div class="eyebrow">
                            <i class="bi bi-collection-play me-1"></i>
                            ECOQUEST / GAME CONTENT EXPLORER
                        </div>

                        <h2 class="hero-title">
                            {{ $game->name }}
                        </h2>

                        <p class="hero-description">
                            {{ $game->description ?? 'Explore the learning activities and configuration connected to this game.' }}
                        </p>

                        <span class="engine-label">
                            <i class="bi bi-cpu"></i>
                            Engine:
                            {{ $game->game_type ?? 'N/A' }}
                        </span>

                    </div>

                </div>

                <a href="{{ route('admin.game-content.index') }}"
                    class="btn back-button">

                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Game Content

                </a>

            </div>

        </div>

    </div>


    {{-- SUMMARY --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e7f5ed;color:#16845a;">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="stat-number">
                    {{ $game->learningTasks->count() }}
                </div>

                <div class="stat-label">
                    Connected Learning Tasks
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#eeebff;color:#7664d8;">
                    <i class="bi bi-grid"></i>
                </div>

                <div class="stat-number" style="font-size:23px;">
                    {{ ucfirst(str_replace('_', ' ', $game->category ?? 'N/A')) }}
                </div>

                <div class="stat-label">
                    Game Category
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#fff2df;color:#c2872b;">
                    <i class="bi bi-speedometer2"></i>
                </div>

                <div class="stat-number" style="font-size:23px;">
                    {{ ucfirst($game->difficulty ?? 'N/A') }}
                </div>

                <div class="stat-label">
                    Difficulty Level
                </div>

            </div>

        </div>

    </div>


    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- GAME INFORMATION --}}
        <div class="col-lg-4">

            <div class="panel h-100">

                <div class="panel-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="stat-icon mb-0"
                            style="background:#e7f5ed;color:#16845a;">
                            <i class="bi bi-info-circle"></i>
                        </div>

                        <div>
                            <h4 class="panel-title">
                                Game Information
                            </h4>

                            <p class="panel-subtitle">
                                Configuration overview
                            </p>
                        </div>

                    </div>

                </div>


                <div class="p-4">

                    <div class="info-row">

                        <div class="info-label">
                            Game Name
                        </div>

                        <div class="info-value">
                            {{ $game->name }}
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Category
                        </div>

                        <div>
                            <span class="category-tag">
                                <i class="bi bi-grid me-1"></i>
                                {{ ucfirst(str_replace('_', ' ', $game->category ?? 'N/A')) }}
                            </span>
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Difficulty
                        </div>

                        <div>
                            <span class="difficulty-tag">
                                <i class="bi bi-speedometer2 me-1"></i>
                                {{ ucfirst($game->difficulty ?? 'N/A') }}
                            </span>
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Game Engine
                        </div>

                        <div>
                            <code class="engine-code">
                                {{ $game->game_type ?? 'N/A' }}
                            </code>
                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">
                            Connected Tasks
                        </div>

                        <div class="info-value">
                            <i class="bi bi-journal-check me-1"
                                style="color:#15966b;"></i>
                            {{ $game->learningTasks->count() }} Tasks
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- LEARNING CONTENT --}}
        <div class="col-lg-8">

            <div class="panel">

                <div class="panel-header">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div class="d-flex align-items-center gap-3">

                            <div class="stat-icon mb-0"
                                style="background:#eeebff;color:#7664d8;">
                                <i class="bi bi-journal-richtext"></i>
                            </div>

                            <div>
                                <h4 class="panel-title">
                                    Learning Content
                                </h4>

                                <p class="panel-subtitle">
                                    Learning tasks connected to this game.
                                </p>
                            </div>

                        </div>

                        <span class="content-count">
                            {{ $game->learningTasks->count() }} Tasks
                        </span>

                    </div>

                </div>


                <div>

                    @forelse($game->learningTasks as $task)

                    @php
                    $taskStatus = strtolower($task->status ?? 'unknown');

                    $statusClass = match($taskStatus) {
                    'completed', 'accepted' => 'status-completed',
                    'pending', 'submitted', 'in_progress', 'revision_requested' => 'status-pending',
                    default => 'status-other',
                    };

                    $statusIcon = match($taskStatus) {
                    'completed', 'accepted' => 'bi-check-circle-fill',
                    'pending', 'submitted', 'in_progress', 'revision_requested' => 'bi-clock-fill',
                    default => 'bi-circle',
                    };

                    $statusText = match($taskStatus) {
                    'completed', 'accepted' => 'Completed',
                    'pending' => 'Pending',
                    default => ucfirst(str_replace('_', ' ', $taskStatus)),
                    };
                    @endphp

                    <div class="task-card">

                        <div class="d-flex justify-content-between align-items-start gap-3 task-layout">

                            <div class="d-flex align-items-start gap-3">

                                <div class="task-number">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>

                                <div>

                                    <h5 class="task-title">
                                        {{ $task->title }}
                                    </h5>

                                    <p class="task-description">
                                        {{ Str::limit($task->description ?? 'No task description available.', 150) }}
                                    </p>

                                    <div class="task-meta">

                                        <span class="xp-tag">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                            {{ $task->xp ?? 0 }} XP
                                        </span>

                                        <span class="task-difficulty">
                                            <i class="bi bi-bar-chart-line me-1"></i>
                                            {{ ucfirst($task->difficulty ?? 'N/A') }}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div>
                                <span class="status {{ $statusClass }}">
                                    <i class="bi {{ $statusIcon }}"></i>
                                    {{ $statusText }}
                                </span>
                            </div>

                        </div>

                    </div>

                    @empty

                    <div class="empty-state">

                        <div class="empty-icon">
                            <i class="bi bi-file-earmark-x"></i>
                        </div>

                        <h5 class="empty-title">
                            No Learning Content Yet
                        </h5>

                        <p class="empty-description">
                            This game currently has no connected learning tasks.
                        </p>

                    </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection