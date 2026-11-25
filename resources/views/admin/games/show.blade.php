
@extends('admin.layouts.app')

@section('title', 'Game Details')
@section('page-heading', 'Game Details')

@section('content')

<style>
    .game-details {
        --forest: #123d2c;
        --green: #168653;
        --mint: #e8f5ed;
        --muted: #718078;
        color: #23372d;
    }

    .game-details .hero {
        position: relative;
        overflow: hidden;
        border-radius: 25px;
        padding: 38px;
        color: white;
        background: linear-gradient(115deg, #103d2b 0%, #176b46 60%, #239966 100%);
        box-shadow: 0 15px 35px rgba(18, 61, 44, .16);
    }

    .game-details .hero::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        right: -65px;
        top: -135px;
        box-shadow: 0 0 0 35px rgba(255,255,255,.035),
                    0 0 0 75px rgba(255,255,255,.025);
    }

    .game-details .hero-content {
        position: relative;
        z-index: 1;
    }

    .game-details .game-icon {
        width: 82px;
        height: 82px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 23px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        font-size: 39px;
        flex-shrink: 0;
    }

    .game-details .hero-title {
        font-size: clamp(28px, 3vw, 38px);
        font-weight: 800;
        letter-spacing: -.8px;
        margin-bottom: 9px;
    }

    .game-details .hero-subtitle {
        font-size: 16px;
        color: rgba(255,255,255,.76);
    }

    .game-details .hero-tag {
        display: inline-flex;
        align-items: center;
        padding: 8px 15px;
        border-radius: 30px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.18);
        color: white;
        font-size: 14px;
        font-weight: 600;
        margin: 5px 5px 0 0;
    }

    .game-details .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 12px 19px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,.35);
        background: rgba(255,255,255,.12);
        color: white;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .game-details .back-btn:hover {
        background: white;
        color: var(--forest);
    }

    .game-details .stat-card {
        height: 100%;
        padding: 23px;
        border: 1px solid #e9efeb;
        border-radius: 19px;
        background: white;
        box-shadow: 0 5px 20px rgba(25, 60, 42, .045);
        transition: .2s ease;
    }

    .game-details .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(25, 60, 42, .09);
    }

    .game-details .stat-icon {
        width: 51px;
        height: 51px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        margin-bottom: 18px;
    }

    .game-details .stat-number {
        font-size: 30px;
        font-weight: 800;
        line-height: 1.2;
        color: #183b2b;
    }

    .game-details .stat-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--muted);
        margin-top: 7px;
    }

    .game-details .section-card {
        background: white;
        border: 1px solid #e9efeb;
        border-radius: 21px;
        box-shadow: 0 5px 22px rgba(25, 60, 42, .045);
        overflow: hidden;
    }

    .game-details .section-heading {
        font-size: 22px;
        font-weight: 800;
        color: #193c2c;
        letter-spacing: -.3px;
    }

    .game-details .section-description {
        font-size: 15px;
        color: var(--muted);
        margin-bottom: 0;
    }

    .game-details .info-label {
        color: #78877e;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        margin-bottom: 9px;
    }

    .game-details .info-value {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.7;
        color: #294536;
        overflow-wrap: anywhere;
    }

    .game-details .info-box {
        height: 100%;
        padding: 19px;
        border-radius: 15px;
        background: #f6faf7;
        border: 1px solid #edf3ee;
    }

    .game-details .table {
        margin-bottom: 0;
        min-width: 650px;
    }

    .game-details .table thead th {
        background: #f5f9f6;
        color: #627369;
        padding: 17px 20px;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: .65px;
        font-weight: 800;
        border-bottom: 1px solid #e9efeb;
        white-space: nowrap;
    }

    .game-details .table tbody td {
        padding: 19px 20px;
        font-size: 15px;
        font-weight: 500;
        color: #344c3e;
        border-color: #edf2ee;
        vertical-align: middle;
    }

    .game-details .task-name {
        font-size: 16px;
        font-weight: 700;
        color: #214632;
    }

    .game-details .task-icon {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #eaf5ed;
        color: #168653;
        font-size: 19px;
        margin-right: 11px;
        flex-shrink: 0;
    }

    .game-details .status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .game-details .status-success {
        color: #167345;
        background: #e5f6eb;
    }

    .game-details .status-warning {
        color: #96620b;
        background: #fff4d9;
    }

    .game-details .status-danger {
        color: #b53d3d;
        background: #fdeaea;
    }

    .game-details .status-neutral {
        color: #5e6d64;
        background: #edf1ee;
    }

    .game-details .empty-state {
        padding: 55px 20px;
        text-align: center;
    }

    .game-details .empty-icon {
        width: 75px;
        height: 75px;
        border-radius: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        background: #eaf5ed;
        color: #168653;
        font-size: 32px;
    }

    @media(max-width: 767px) {
        .game-details .hero {
            padding: 25px 20px;
        }

        .game-details .game-icon {
            width: 65px;
            height: 65px;
            font-size: 30px;
        }

        .game-details .hero-title {
            font-size: 27px;
        }

        .game-details .stat-card {
            padding: 18px;
        }

        .game-details .section-card > .p-4 {
            padding: 20px !important;
        }
    }
</style>

<div class="game-details">

    {{-- TOP HERO --}}
    <div class="hero mb-4">
        <div class="hero-content">

            <div class="d-flex flex-wrap justify-content-between align-items-start gap-4">

                <div class="d-flex align-items-center gap-3">
                    <div class="game-icon">
                        <i class="bi bi-controller"></i>
                    </div>

                    <div>
                        <div class="text-uppercase mb-2"
                             style="font-size:12px;letter-spacing:1.8px;color:#c8ead7;font-weight:700;">
                            ECOQUEST / GAME LIBRARY
                        </div>

                        <h2 class="hero-title">{{ $game->name }}</h2>

                        <p class="hero-subtitle mb-2">
                            Game configuration and learning activity overview
                        </p>

                        <div>
                            <span class="hero-tag">
                                <i class="bi bi-controller me-2"></i>
                                {{ $game->game_type ?? 'No game type' }}
                            </span>

                            <span class="hero-tag">
                                <i class="bi bi-grid me-2"></i>
                                {{ $game->category ?? 'Uncategorized' }}
                            </span>

                            <span class="hero-tag">
                                <i class="bi bi-bar-chart me-2"></i>
                                {{ $game->difficulty ?? 'Difficulty not set' }}
                            </span>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.games.index') }}" class="back-btn">
                    <i class="bi bi-arrow-left"></i>
                    Back to Games
                </a>

            </div>
        </div>
    </div>


    {{-- STATISTICS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e6f5eb;color:#168653;">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="stat-number">
                    {{ $game->learningTasks->count() }}
                </div>

                <div class="stat-label">Connected Learning Tasks</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:#fff3dd;color:#c58a20;">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>

                <div class="stat-number">
                    {{ $game->learningTasks->sum('xp') }}
                </div>

                <div class="stat-label">Total XP Assigned</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background:#e9efff;color:#5475ca;">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <div class="stat-number">
                    {{ $game->learningTasks->where('status', 'completed')->count() }}
                </div>

                <div class="stat-label">Completed Tasks</div>
            </div>
        </div>

    </div>


    {{-- GAME INFORMATION --}}
    <div class="section-card mb-4">

        <div class="p-4 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon mb-0"
                     style="background:#e6f5eb;color:#168653;">
                    <i class="bi bi-info-circle"></i>
                </div>

                <div>
                    <h4 class="section-heading mb-1">Game Information</h4>
                    <p class="section-description">
                        Details and configuration of this learning game.
                    </p>
                </div>
            </div>
        </div>

        <div class="p-4">
            <div class="row g-3">

                <div class="col-lg-6">
                    <div class="info-box">
                        <div class="info-label">
                            <i class="bi bi-card-text me-1"></i>
                            Description
                        </div>

                        <div class="info-value">
                            {{ $game->description ?? 'No description available.' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="info-box">
                        <div class="info-label">
                            <i class="bi bi-collection me-1"></i>
                            Category
                        </div>

                        <div class="info-value">
                            {{ $game->category ?? 'Not specified' }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="info-box">
                        <div class="info-label">
                            <i class="bi bi-speedometer2 me-1"></i>
                            Difficulty
                        </div>

                        <div class="info-value">
                            {{ $game->difficulty ?? 'Not specified' }}
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>


    {{-- LEARNING TASKS --}}
    <div class="section-card">

        <div class="p-4 border-bottom">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon mb-0"
                         style="background:#e9efff;color:#5475ca;">
                        <i class="bi bi-list-task"></i>
                    </div>

                    <div>
                        <h4 class="section-heading mb-1">Learning Tasks</h4>
                        <p class="section-description">
                            Tasks currently connected to this game.
                        </p>
                    </div>
                </div>

                <div class="px-3 py-2 rounded-3"
                     style="background:#e8f5ed;color:#167345;font-weight:800;font-size:15px;">
                    <i class="bi bi-collection-check me-1"></i>
                    {{ $game->learningTasks->count() }} Tasks
                </div>

            </div>
        </div>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Learning Task</th>
                        <th>Student</th>
                        <th>XP Reward</th>
                        <th>Task Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($game->learningTasks as $task)

                        <tr>

                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="task-icon">
                                        <i class="bi bi-journal-text"></i>
                                    </span>

                                    <span class="task-name">
                                        {{ $task->title }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <span class="fw-semibold">
                                    <i class="bi bi-person-circle me-1 text-secondary"></i>
                                    Student #{{ $task->user_id }}
                                </span>
                            </td>

                            <td>
                                <span class="fw-bold" style="color:#168653;font-size:16px;">
                                    <i class="bi bi-lightning-charge-fill me-1"></i>
                                    {{ $task->xp }} XP
                                </span>
                            </td>

                            <td>
                                @php
                                    $status = strtolower($task->status ?? 'unknown');

                                    $statusClass = match($status) {
                                        'completed', 'accepted' => 'status-success',
                                        'pending', 'in_progress', 'submitted' => 'status-warning',
                                        'rejected', 'failed' => 'status-danger',
                                        default => 'status-neutral',
                                    };
                                @endphp

                                <span class="status {{ $statusClass }}">
                                    <i class="bi bi-circle-fill" style="font-size:7px;"></i>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-controller"></i>
                                    </div>

                                    <h5 class="fw-bold mb-2" style="color:#234633;">
                                        No Learning Tasks Yet
                                    </h5>

                                    <p class="text-secondary mb-0" style="font-size:15px;">
                                        There are currently no learning tasks connected to this game.
                                    </p>
                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="px-4 py-3 border-top"
             style="background:#f9fbf9;">
            <div class="d-flex align-items-center gap-2 text-secondary"
                 style="font-size:13px;">
                <i class="bi bi-shield-check text-success"></i>
                Game information and connected task records
            </div>
        </div>

    </div>

</div>

@endsection
