{{-- =========================================================
     EcoQuest - Student Progress Page
     ========================================================= --}}

@extends('student.layouts.app')

{{-- Bootstrap Icons --}}
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    /* =====================================================
           PAGE
        ===================================================== */

    .progress-page {
        min-height: 100vh;
        background: #f5f7fb;
        padding: 28px;
    }


    /* =====================================================
           HEADER
        ===================================================== */

    .progress-header {
        background: linear-gradient(135deg,
                #172554,
                #2563eb);

        border-radius: 24px;
        padding: 30px;
        color: #ffffff;

        position: relative;
        overflow: hidden;

        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.18);
    }

    .progress-header::before {
        content: "";
        position: absolute;

        width: 220px;
        height: 220px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.08);

        right: -70px;
        top: -100px;
    }

    .progress-header::after {
        content: "";
        position: absolute;

        width: 140px;
        height: 140px;

        border-radius: 50%;

        background: rgba(255, 255, 255, 0.06);

        right: 100px;
        bottom: -80px;
    }

    .progress-header-content {
        position: relative;
        z-index: 2;
    }

    .progress-header h2 {
        font-weight: 800;
        margin-bottom: 8px;
    }

    .progress-header p {
        margin: 0;
        color: rgba(255, 255, 255, 0.78);
    }

    .header-level {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        margin-top: 20px;

        padding: 8px 15px;

        border-radius: 50px;

        background: rgba(255, 255, 255, 0.12);

        font-size: 14px;
        font-weight: 600;
    }


    /* =====================================================
           STAT CARDS
        ===================================================== */

    .stat-card {
        border: 0;
        border-radius: 20px;

        background: #ffffff;

        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);

        transition:
            transform 0.25s ease,
            box-shadow 0.25s ease;

        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);

        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.10);
    }

    .stat-card-body {
        padding: 24px;
    }

    .stat-icon {
        width: 52px;
        height: 52px;

        border-radius: 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 22px;
    }

    .stat-icon.blue {
        background: #e8f0ff;
        color: #2563eb;
    }

    .stat-icon.yellow {
        background: #fff5d6;
        color: #e5a700;
    }

    .stat-icon.green {
        background: #e7f8ef;
        color: #16a05d;
    }

    .stat-icon.purple {
        background: #f1eaff;
        color: #7c3aed;
    }

    .stat-label {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;

        margin-bottom: 5px;
    }

    .stat-value {
        color: #0f172a;

        font-size: 28px;
        font-weight: 800;

        margin: 0;
    }


    /* =====================================================
           COMMON CARD
        ===================================================== */

    .eco-card {
        border: 0;
        border-radius: 22px;

        background: #ffffff;

        box-shadow: 0 8px 25px rgba(15, 23, 42, 0.06);
    }

    .eco-card-header {
        padding: 23px 25px;

        border-bottom: 1px solid #eef1f6;

        background: transparent;
    }

    .eco-card-header h5 {
        font-weight: 750;
        color: #0f172a;

        margin-bottom: 4px;
    }

    .eco-card-header p {
        color: #64748b;

        font-size: 13px;

        margin: 0;
    }


    /* =====================================================
           LEVEL PROGRESS
        ===================================================== */

    .level-card {
        padding: 27px;
    }

    .level-number {
        font-size: 34px;
        font-weight: 850;

        color: #172554;
    }

    .level-subtitle {
        color: #64748b;
        font-size: 13px;
    }

    .xp-badge {
        padding: 9px 15px;

        background: #eef4ff;
        color: #2563eb;

        border-radius: 50px;

        font-size: 13px;
        font-weight: 700;
    }

    .xp-progress {
        height: 15px;

        background: #e9eef7;

        border-radius: 50px;

        overflow: hidden;
    }

    .xp-progress-bar {
        height: 100%;

        border-radius: 50px;

        background: linear-gradient(90deg,
                #2563eb,
                #60a5fa);

        transition: width 0.6s ease;
    }

    .level-labels {
        display: flex;
        justify-content: space-between;

        margin-top: 9px;

        font-size: 12px;
        color: #94a3b8;
    }


    /* =====================================================
           QUEST LIST
        ===================================================== */

    .quest-item {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 17px 0;

        border-bottom: 1px solid #edf0f5;
    }

    .quest-item:last-child {
        border-bottom: 0;
    }

    .quest-left {
        display: flex;
        align-items: center;

        min-width: 0;
    }

    .quest-check {
        width: 44px;
        height: 44px;

        flex-shrink: 0;

        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e8f8ef;
        color: #16a05d;

        margin-right: 13px;
    }

    .quest-title {
        color: #172033;

        font-weight: 700;
        font-size: 14px;

        margin-bottom: 4px;
    }

    .quest-meta {
        color: #94a3b8;

        font-size: 12px;
    }

    .quest-xp {
        flex-shrink: 0;

        padding: 7px 11px;

        border-radius: 50px;

        background: #fff5d6;
        color: #a56c00;

        font-size: 12px;
        font-weight: 700;
    }


    /* =====================================================
           BADGES
        ===================================================== */

    .badge-item {
        display: flex;
        align-items: center;

        padding: 16px 0;

        border-bottom: 1px solid #edf0f5;
    }

    .badge-item:last-child {
        border-bottom: 0;
    }

    .badge-icon {
        width: 50px;
        height: 50px;

        flex-shrink: 0;

        border-radius: 16px;

        background: #fff6db;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 27px;

        margin-right: 13px;
    }

    .badge-name {
        color: #172033;

        font-size: 14px;
        font-weight: 750;

        margin-bottom: 3px;
    }

    .badge-date {
        color: #94a3b8;

        font-size: 12px;
    }


    /* =====================================================
           EMPTY STATE
        ===================================================== */

    .empty-state {
        text-align: center;

        padding: 45px 20px;
    }

    .empty-icon {
        width: 70px;
        height: 70px;

        margin: 0 auto 16px;

        border-radius: 22px;

        background: #f1f5f9;

        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 32px;
    }

    .empty-state h6 {
        font-weight: 750;
        color: #172033;
    }

    .empty-state p {
        color: #94a3b8;
        font-size: 13px;
    }


    /* =====================================================
           BUTTON
        ===================================================== */

    .view-badges-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 9px 15px;

        border-radius: 10px;

        font-size: 13px;
        font-weight: 650;
    }


    /* =====================================================
           RESPONSIVE
        ===================================================== */

    @media (max-width: 768px) {

        .progress-page {
            padding: 18px;
        }

        .progress-header {
            padding: 24px;
            border-radius: 20px;
        }

        .progress-header h2 {
            font-size: 24px;
        }

        .stat-value {
            font-size: 24px;
        }

        .level-card {
            padding: 21px;
        }

        .quest-item {
            align-items: flex-start;
        }

        .quest-xp {
            font-size: 11px;
        }

    }

    @media (max-width: 480px) {

        .progress-page {
            padding: 13px;
        }

        .progress-header {
            padding: 20px;
        }

        .progress-header h2 {
            font-size: 21px;
        }

        .stat-card-body {
            padding: 20px;
        }

        .quest-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .quest-xp {
            margin-left: 57px;
        }

    }
</style>



{{-- =========================================================
     PAGE CONTENT
     ========================================================= --}}

@section('content')

<div class="progress-page">

    {{-- =====================================================
         HERO HEADER
    ===================================================== --}}

    <div class="progress-header mb-4">

        <div class="progress-header-content">

            <h2>
                <i class="bi bi-graph-up-arrow me-2"></i>
                My Progress
            </h2>

            <p>
                Track your EcoQuest journey, level up and unlock achievements.
            </p>

            <div class="header-level">

                <i class="bi bi-lightning-charge-fill"></i>

                Level {{ $currentLevel }}

                <span class="opacity-50">•</span>

                {{ $progress->total_xp }} XP

            </div>

        </div>

    </div>


    {{-- =====================================================
         STAT CARDS
    ===================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Total XP --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="stat-card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-label">
                                Total XP
                            </div>

                            <h3 class="stat-value">
                                {{ $progress->total_xp }}
                            </h3>

                        </div>

                        <div class="stat-icon blue">

                            <i class="bi bi-star-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Level --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="stat-card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-label">
                                Current Level
                            </div>

                            <h3 class="stat-value">
                                {{ $currentLevel }}
                            </h3>

                        </div>

                        <div class="stat-icon yellow">

                            <i class="bi bi-lightning-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Completed --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="stat-card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-label">
                                Completed Quests
                            </div>

                            <h3 class="stat-value">
                                {{ $progress->completed_tasks }}
                            </h3>

                        </div>

                        <div class="stat-icon green">

                            <i class="bi bi-check-circle-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Badges --}}
        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card h-100">

                <div class="stat-card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="stat-label">
                                Badges Earned
                            </div>

                            <h3 class="stat-value">
                                {{ $badges->count() }}
                            </h3>

                        </div>

                        <div class="stat-icon purple">

                            <i class="bi bi-award-fill"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LEVEL PROGRESS
    ===================================================== --}}

    <div class="eco-card mb-4">

        <div class="level-card">

            <div class="d-flex justify-content-between
                        align-items-center flex-wrap gap-3 mb-4">

                <div>

                    <div class="level-number">

                        Level {{ $currentLevel }}

                    </div>

                    <div class="level-subtitle">

                        Keep completing quests to reach Level
                        {{ $currentLevel + 1 }}

                    </div>

                </div>

                <div class="xp-badge">

                    <i class="bi bi-star-fill me-1"></i>

                    {{ $xpIntoLevel }} / {{ $xpNeededForLevel }} XP

                </div>

            </div>


            <div class="xp-progress">

                <div class="xp-progress-bar"
                    style="width: {{ $xpPercentage }}%;">

                </div>

            </div>


            <div class="level-labels">

                <span>
                    Level {{ $currentLevel }}
                </span>

                <span>
                    {{ round($xpPercentage) }}% Complete
                </span>

                <span>
                    Level {{ $currentLevel + 1 }}
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         LOWER CONTENT
    ===================================================== --}}

    <div class="row g-4">


        {{-- =================================================
             COMPLETED QUESTS
        ================================================= --}}

        <div class="col-12 col-xl-8">

            <div class="eco-card h-100">

                <div class="eco-card-header">

                    <h5>

                        <i class="bi bi-check2-circle text-success me-2"></i>

                        Completed Quests

                    </h5>

                    <p>
                        Your recently completed learning activities.
                    </p>

                </div>


                <div class="px-4 pb-3">

                    @forelse($completedTasks as $task)

                    <div class="quest-item">

                        <div class="quest-left">

                            <div class="quest-check">

                                <i class="bi bi-check-lg"></i>

                            </div>

                            <div class="min-w-0">

                                <div class="quest-title">

                                    {{ $task->title }}

                                </div>

                                <div class="quest-meta">

                                    {{ ucfirst($task->category ?? 'General') }}

                                    @if($task->updated_at)

                                    <span class="mx-1">•</span>

                                    {{ $task->updated_at->format('d M Y') }}

                                    @endif

                                </div>

                            </div>

                        </div>


                        <div class="quest-xp">

                            <i class="bi bi-star-fill me-1"></i>

                            +{{ $task->xp }} XP

                        </div>

                    </div>

                    @empty

                    <div class="empty-state">

                        <div class="empty-icon">
                            🎯
                        </div>

                        <h6>
                            No quests completed yet
                        </h6>

                        <p class="mb-0">
                            Complete your first quest to start building
                            your progress.
                        </p>

                    </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =================================================
             ACHIEVEMENTS
        ================================================= --}}

        <div class="col-12 col-xl-4">

            <div class="eco-card h-100">

                <div class="eco-card-header">

                    <h5>

                        <i class="bi bi-award-fill text-warning me-2"></i>

                        Achievements

                    </h5>

                    <p>
                        Your earned badges.
                    </p>

                </div>


                <div class="px-4 pb-3">

                    @forelse($badges->take(5) as $studentBadge)

                    @php

                    $badge = $studentBadge->badge;

                    @endphp


                    <div class="badge-item">

                        <div class="badge-icon">

                            {{ $badge->icon ?? '🏆' }}

                        </div>


                        <div>

                            <div class="badge-name">

                                {{ $badge->name }}

                            </div>

                            <div class="badge-date">

                                <i class="bi bi-calendar3 me-1"></i>

                                Earned
                                {{ $studentBadge->awarded_at?->format('d M Y') }}

                            </div>

                        </div>

                    </div>

                    @empty

                    <div class="empty-state">

                        <div class="empty-icon">
                            🏆
                        </div>

                        <h6>
                            No badges yet
                        </h6>

                        <p class="mb-0">
                            Complete quests and unlock achievements.
                        </p>

                    </div>

                    @endforelse


                    @if($badges->count() > 5)

                    <div class="text-center pt-3">

                        <a href="{{ route('student.badges') }}"
                            class="btn btn-outline-primary view-badges-btn">

                            View All Badges

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


{{-- =========================================================
     Bootstrap JS
     ========================================================= --}}

@section('scripts')


@endsection