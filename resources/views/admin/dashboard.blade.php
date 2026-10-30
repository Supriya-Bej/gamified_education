@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('page-heading', 'Command Center')

@push('styles')
<style>
    .admin-command {
        --command-ink: #14253d;
        --command-muted: #718096;
        --command-line: #e5ebf2;
        --command-canvas: #f4f7fb;
        --command-card: #fff;
        color: var(--command-ink);
    }

    .admin-command-hero {
        position: relative;
        display: flex;
        min-height: 210px;
        align-items: center;
        justify-content: space-between;
        gap: 28px;
        overflow: hidden;
        margin-bottom: 26px;
        padding: clamp(26px, 4vw, 42px);
        border: 1px solid rgba(255, 255, 255, .08);
        border-radius: 24px;
        background:
            radial-gradient(circle at 86% 18%, rgba(91, 199, 175, .3), transparent 14rem),
            linear-gradient(116deg, #12243d 0%, #1a3651 58%, #235b60 100%);
        color: #fff;
        box-shadow: 0 18px 42px rgba(18, 36, 61, .17);
    }

    .admin-command-hero::after {
        position: absolute;
        right: 5%;
        bottom: -155px;
        width: 340px;
        height: 340px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50%;
        box-shadow: 0 0 0 28px rgba(255, 255, 255, .035), 0 0 0 60px rgba(255, 255, 255, .025);
        content: '';
    }

    .admin-command-hero-copy,
    .admin-command-hero-mark {
        position: relative;
        z-index: 1;
    }

    .admin-command-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 14px;
        color: #8ce0c8;
        font-size: .72rem;
        font-weight: 800;
        letter-spacing: .14em;
        text-transform: uppercase;
    }

    .admin-command-hero h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(1.8rem, 3vw, 2.6rem);
        font-weight: 800;
        letter-spacing: -.04em;
    }

    .admin-command-hero p {
        max-width: 580px;
        margin: 0;
        color: rgba(255, 255, 255, .72);
        font-size: .93rem;
        line-height: 1.7;
    }

    .admin-command-hero-mark {
        display: grid;
        width: 110px;
        height: 110px;
        flex: 0 0 110px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .18);
        border-radius: 30px;
        background: rgba(255, 255, 255, .09);
        color: #a0ead4;
        font-size: 3rem;
        transform: rotate(4deg);
        backdrop-filter: blur(8px);
    }

    .admin-command-metrics {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .admin-command-metric {
        display: flex;
        min-width: 0;
        min-height: 132px;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 20px;
        border: 1px solid var(--command-line);
        border-radius: 18px;
        background: var(--command-card);
        box-shadow: 0 7px 22px rgba(27, 49, 77, .045);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .admin-command-metric:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(27, 49, 77, .09);
    }

    .admin-command-metric-label {
        margin-bottom: 13px;
        color: var(--command-muted);
        font-size: .75rem;
        font-weight: 700;
    }

    .admin-command-metric-value {
        color: var(--command-ink);
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -.05em;
        line-height: 1;
    }

    .admin-command-metric-note {
        margin-top: 9px;
        color: var(--command-muted);
        font-size: .7rem;
    }

    .admin-command-metric-icon {
        display: grid;
        width: 44px;
        height: 44px;
        flex: 0 0 44px;
        place-items: center;
        border-radius: 14px;
        background: var(--metric-bg, #eaf6f2);
        color: var(--metric-color, #27836c);
        font-size: 1.15rem;
    }

    .admin-command-section-title {
        margin: 0 0 14px;
        color: var(--command-ink);
        font-size: 1.02rem;
        font-weight: 800;
        letter-spacing: -.02em;
    }

    .admin-command-section-subtitle {
        margin: -7px 0 18px;
        color: var(--command-muted);
        font-size: .78rem;
    }

    .admin-command-panel {
        height: 100%;
        padding: 23px;
        border: 1px solid var(--command-line);
        border-radius: 18px;
        background: var(--command-card);
        box-shadow: 0 7px 22px rgba(27, 49, 77, .045);
    }

    .admin-command-pending {
        display: flex;
        align-items: center;
        gap: 17px;
        margin-top: 23px;
        padding: 17px;
        border: 1px solid #e7edf3;
        border-radius: 15px;
        background: #f8fafc;
    }

    .admin-command-pending-icon {
        display: grid;
        width: 48px;
        height: 48px;
        flex: 0 0 48px;
        place-items: center;
        border-radius: 15px;
        background: #fff2dd;
        color: #c37b22;
        font-size: 1.2rem;
    }

    .admin-command-pending-value {
        color: var(--command-ink);
        font-size: 1.45rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .admin-command-pending-label {
        margin-top: 5px;
        color: var(--command-muted);
        font-size: .75rem;
    }

    .admin-command-progress {
        height: 8px;
        margin-top: 18px;
        overflow: hidden;
        border-radius: 99px;
        background: #eaf0f4;
    }

    .admin-command-progress > span {
        display: block;
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #36a987, #79d1b0);
    }

    .admin-command-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .admin-command-action {
        display: flex;
        min-height: 68px;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border: 1px solid var(--command-line);
        border-radius: 14px;
        background: #fff;
        color: var(--command-ink);
        font-size: .8rem;
        font-weight: 750;
        text-decoration: none;
        transition: border-color .18s ease, background .18s ease, transform .18s ease;
    }

    .admin-command-action:hover {
        transform: translateY(-2px);
        border-color: #a9d9cd;
        background: #f5fbf8;
        color: #1b725d;
    }

    .admin-command-action:focus-visible {
        outline: 3px solid rgba(39, 131, 108, .25);
        outline-offset: 2px;
    }

    .admin-command-action i {
        display: grid;
        width: 36px;
        height: 36px;
        flex: 0 0 36px;
        place-items: center;
        border-radius: 11px;
        background: #eaf6f2;
        color: #27836c;
        font-size: 1rem;
    }

    @media (max-width: 1199px) {
        .admin-command-metrics {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .admin-command-hero {
            min-height: auto;
            padding: 25px 22px;
            border-radius: 19px;
        }

        .admin-command-hero-mark {
            width: 74px;
            height: 74px;
            flex-basis: 74px;
            border-radius: 22px;
            font-size: 2rem;
        }

        .admin-command-metrics {
            gap: 11px;
        }

        .admin-command-metric {
            min-height: 116px;
            padding: 15px;
        }

        .admin-command-metric-value {
            font-size: 1.65rem;
        }

        .admin-command-metric-icon {
            width: 37px;
            height: 37px;
            flex-basis: 37px;
            border-radius: 12px;
        }
    }

    @media (max-width: 420px) {
        .admin-command-metrics {
            grid-template-columns: 1fr;
        }

        .admin-command-metric {
            min-height: auto;
        }

        .admin-command-actions {
            grid-template-columns: 1fr;
        }

        .admin-command-hero-mark {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .admin-command *,
        .admin-command *::before,
        .admin-command *::after {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
        }
    }
</style>
@endpush

@section('content')
@php
    $completionRate = $totalTasks > 0
        ? min(100, (int) round(($completedTasks / $totalTasks) * 100))
        : 0;
@endphp

<div class="admin-command">
    <section class="admin-command-hero">
        <div class="admin-command-hero-copy">
            <div class="admin-command-eyebrow">
                <i class="bi bi-activity" aria-hidden="true"></i>
                EcoQuest operations
            </div>
            <h1>Welcome back, {{ Auth::guard('admin')->user()->name }}</h1>
            <p>
                A clear view of your learning community, game library, and student quest activity.
                Choose a workspace below to manage what happens next.
            </p>
        </div>
        <div class="admin-command-hero-mark" aria-hidden="true">
            <i class="bi bi-compass-fill"></i>
        </div>
    </section>

    <section class="admin-command-metrics" aria-label="EcoQuest overview">
        <article class="admin-command-metric">
            <div>
                <div class="admin-command-metric-label">Total students</div>
                <div class="admin-command-metric-value">{{ number_format($totalStudents) }}</div>
                <div class="admin-command-metric-note">Registered learners</div>
            </div>
            <span class="admin-command-metric-icon" style="--metric-bg:#e9f3ff;--metric-color:#3679bc" aria-hidden="true">
                <i class="bi bi-people-fill"></i>
            </span>
        </article>

        <article class="admin-command-metric">
            <div>
                <div class="admin-command-metric-label">Games</div>
                <div class="admin-command-metric-value">{{ number_format($totalGames) }}</div>
                <div class="admin-command-metric-note">In the game library</div>
            </div>
            <span class="admin-command-metric-icon" style="--metric-bg:#f1edff;--metric-color:#7257b7" aria-hidden="true">
                <i class="bi bi-controller"></i>
            </span>
        </article>

        <article class="admin-command-metric">
            <div>
                <div class="admin-command-metric-label">Learning tasks</div>
                <div class="admin-command-metric-value">{{ number_format($totalTasks) }}</div>
                <div class="admin-command-metric-note">Created for students</div>
            </div>
            <span class="admin-command-metric-icon" style="--metric-bg:#fff2df;--metric-color:#c2812c" aria-hidden="true">
                <i class="bi bi-journal-check"></i>
            </span>
        </article>

        <article class="admin-command-metric">
            <div>
                <div class="admin-command-metric-label">Completed tasks</div>
                <div class="admin-command-metric-value">{{ number_format($completedTasks) }}</div>
                <div class="admin-command-metric-note">Successfully finished</div>
            </div>
            <span class="admin-command-metric-icon" style="--metric-bg:#e8f7ef;--metric-color:#2d9662" aria-hidden="true">
                <i class="bi bi-check2-circle"></i>
            </span>
        </article>
    </section>

    <section class="row g-4">
        <div class="col-lg-5">
            <div class="admin-command-panel">
                <h2 class="admin-command-section-title">Task activity</h2>
                <p class="admin-command-section-subtitle">A quick snapshot of learner task progress.</p>

                <div class="admin-command-pending">
                    <span class="admin-command-pending-icon" aria-hidden="true">
                        <i class="bi bi-hourglass-split"></i>
                    </span>
                    <div>
                        <div class="admin-command-pending-value">{{ number_format($pendingTasks) }}</div>
                        <div class="admin-command-pending-label">Tasks waiting for students</div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between gap-3 mt-4">
                    <span class="small fw-semibold text-secondary">Completion rate</span>
                    <span class="small fw-bold">{{ $completionRate }}%</span>
                </div>
                <div
                    class="admin-command-progress"
                    role="progressbar"
                    aria-label="Task completion rate"
                    aria-valuenow="{{ $completionRate }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                >
                    <span style="width: {{ $completionRate }}%"></span>
                </div>
                <p class="admin-command-section-subtitle mt-3 mb-0">
                    {{ number_format($completedTasks) }} of {{ number_format($totalTasks) }} tasks completed
                </p>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="admin-command-panel">
                <h2 class="admin-command-section-title">Go to workspace</h2>
                <p class="admin-command-section-subtitle">Manage the content and people that power EcoQuest.</p>

                <nav class="admin-command-actions" aria-label="Admin workspaces">
                    <a class="admin-command-action" href="{{ route('admin.games.index') }}">
                        <i class="bi bi-controller" aria-hidden="true"></i>
                        <span>Manage games</span>
                    </a>
                    <a class="admin-command-action" href="{{ route('admin.game-content.index') }}">
                        <i class="bi bi-collection-play" aria-hidden="true"></i>
                        <span>Review game content</span>
                    </a>
                    <a class="admin-command-action" href="{{ route('admin.students.index') }}">
                        <i class="bi bi-people" aria-hidden="true"></i>
                        <span>View students</span>
                    </a>
                    <a class="admin-command-action" href="{{ route('admin.tasks.index') }}">
                        <i class="bi bi-list-check" aria-hidden="true"></i>
                        <span>Review learning tasks</span>
                    </a>
                    <a class="admin-command-action" href="{{ route('admin.learning-materials.index') }}">
                        <i class="bi bi-book-half" aria-hidden="true"></i>
                        <span>Learning materials</span>
                    </a>
                    <a class="admin-command-action" href="{{ route('admin.badges.index') }}">
                        <i class="bi bi-award" aria-hidden="true"></i>
                        <span>Badges & achievements</span>
                    </a>
                    <a class="admin-command-action" href="{{ route('admin.leaderboard.index') }}">
                        <i class="bi bi-trophy" aria-hidden="true"></i>
                        <span>Manage leaderboard</span>
                    </a>
                </nav>
            </div>
        </div>
    </section>
</div>
@endsection
