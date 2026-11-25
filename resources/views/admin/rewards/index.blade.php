@extends('admin.layouts.app')

@section('title', 'XP & Rewards')
@section('page-heading', 'XP & Rewards Management')

@push('styles')
<style>
    .rewards-page {
        --forest: #12382a;
        --green: #167345;
        --gold: #c2851a;
        --gold-bg: #fff8e0;
        --muted: #7a8c82;
        --border: #e5eee8;
    }

    /* HERO */
    .rewards-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        border-radius: 24px;
        color: white;
        background: linear-gradient(115deg, #1a2d16 0%, #256637 50%, #1a9b6b 100%);
        box-shadow: 0 14px 35px rgba(26, 45, 22, .18);
        margin-bottom: 24px;
    }

    .rewards-hero::before {
        content: "";
        position: absolute;
        width: 310px;
        height: 310px;
        right: -75px;
        top: -155px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow: 0 0 0 40px rgba(255,255,255,.04), 0 0 0 80px rgba(255,255,255,.025);
    }

    .rewards-hero::after {
        content: "\F604";
        font-family: "bootstrap-icons";
        position: absolute;
        right: 36px;
        bottom: -30px;
        font-size: 160px;
        opacity: .07;
        line-height: 1;
    }

    .rewards-hero .hero-content { position: relative; z-index: 1; }

    .rewards-hero .hero-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        flex-shrink: 0;
    }

    .rewards-hero .hero-label {
        color: #b7f5a0;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .rewards-hero .hero-title {
        font-size: 29px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 7px;
    }

    .rewards-hero .hero-description {
        color: rgba(255,255,255,.75);
        font-size: 15px;
        margin: 0;
        max-width: 540px;
    }

    /* STAT CARDS */
    .reward-stat {
        background: white;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 4px 16px rgba(20,55,40,.04);
        transition: .2s;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .reward-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(20,55,40,.08);
    }

    .reward-stat-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        font-size: 24px;
        flex-shrink: 0;
    }

    .reward-stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--forest);
        line-height: 1.1;
    }

    .reward-stat-label {
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        margin-top: 4px;
    }

    /* CONTENT PANEL */
    .rewards-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(20,55,40,.045);
    }

    .rewards-panel-header {
        padding: 24px 26px;
        background: linear-gradient(110deg, #fbfdfb, #f1f7f2);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .rewards-panel-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 4px;
    }

    .rewards-panel-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    /* TABLE */
    .rewards-table { margin: 0; }

    .rewards-table thead th {
        padding: 16px 15px;
        background: #f4f8f5;
        color: #526d5c;
        border-bottom: 1px solid #e5eee7;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .rewards-table tbody td {
        padding: 18px 15px;
        border-bottom: 1px solid #edf2ee;
        vertical-align: middle;
        color: #3a4a40;
        font-size: 14px;
    }

    .rewards-table tbody tr {
        transition: background .2s;
    }

    .rewards-table tbody tr:hover { background: #f7fcf8; }
    .rewards-table tbody tr:last-child td { border-bottom: 0; }

    .student-avatar {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        background: linear-gradient(135deg, #d9f3e2, #b8e6c8);
        color: #167345;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 800;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .student-name { font-weight: 700; color: #1a3828; font-size: 14px; margin-bottom: 2px; }
    .student-email { color: #8a9990; font-size: 12px; }

    .level-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #edf0ff;
        color: #4a5fbe;
        padding: 7px 11px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
    }

    .xp-value {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 800;
        font-size: 15px;
        color: #c2851a;
    }

    .tasks-value {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
        color: #167345;
    }

    /* PROGRESS BAR */
    .level-progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
        font-size: 11px;
        color: var(--muted);
        font-weight: 600;
    }

    .level-progress-bar {
        height: 7px;
        border-radius: 99px;
        background: #eaf0f2;
        overflow: hidden;
        min-width: 140px;
    }

    .level-progress-bar > div {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #36a987, #79d1b0);
    }

    /* PAGINATION */
    .rewards-footer {
        padding: 18px 25px;
        border-top: 1px solid var(--border);
        background: white;
    }

    .pagination { gap: 5px; margin: 0; }

    .page-link {
        border: 1px solid #e2ebe4;
        border-radius: 9px !important;
        color: #167345;
        font-weight: 700;
        padding: 8px 13px;
        box-shadow: none;
    }

    .page-item.active .page-link {
        background: #167345;
        border-color: #167345;
        color: white;
    }

    .page-item.disabled .page-link { background: #f8faf9; color: #aab5ae; }

    @media(max-width: 767px) {
        .rewards-hero { padding: 24px 20px; }
        .rewards-hero .hero-title { font-size: 23px; }
        .rewards-panel-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="rewards-page">

    {{-- HERO HEADER --}}
    <div class="rewards-hero">
        <div class="hero-content">
            <div class="d-flex align-items-center gap-3">
                <div class="hero-icon">
                    <i class="bi bi-stars"></i>
                </div>
                <div>
                    <div class="hero-label">
                        <i class="bi bi-lightning-charge-fill me-1"></i> EcoQuest / Reward System
                    </div>
                    <h2 class="hero-title">XP & Rewards Overview</h2>
                    <p class="hero-description">
                        Monitor student experience points, levels, and task completion progress across your learning community.
                    </p>
                </div>
            </div>
        </div>
    </div>


    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="reward-stat">
                <div>
                    <div class="reward-stat-label">Total XP Earned</div>
                    <div class="reward-stat-value">{{ number_format($totalXp) }}</div>
                </div>
                <div class="reward-stat-icon" style="background:#fff8e0;color:#c2851a;">
                    <i class="bi bi-stars"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="reward-stat">
                <div>
                    <div class="reward-stat-label">Students With XP</div>
                    <div class="reward-stat-value">{{ $studentsWithXp }}</div>
                </div>
                <div class="reward-stat-icon" style="background:#e5f5ec;color:#167345;">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="reward-stat">
                <div>
                    <div class="reward-stat-label">Completed Tasks</div>
                    <div class="reward-stat-value">{{ number_format($completedTasks) }}</div>
                </div>
                <div class="reward-stat-icon" style="background:#fffbe6;color:#ca9700;">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="reward-stat">
                <div>
                    <div class="reward-stat-label">Average XP / Student</div>
                    <div class="reward-stat-value">{{ number_format($averageXp ?? 0) }}</div>
                </div>
                <div class="reward-stat-icon" style="background:#e8f4ff;color:#3f78db;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- STUDENT XP TABLE --}}
    <div class="rewards-panel">

        <div class="rewards-panel-header">
            <div>
                <div class="rewards-panel-title">
                    <i class="bi bi-lightning-charge-fill me-2" style="color:#c2851a;"></i>
                    Student XP Progress
                </div>
                <p class="rewards-panel-subtitle">Track experience points, levels and completed learning tasks per student.</p>
            </div>
            <span style="padding:9px 15px;background:#fff8e0;color:#c2851a;border-radius:30px;font-size:13px;font-weight:800;">
                <i class="bi bi-stars me-1"></i>
                {{ $students->total() }} Students
            </span>
        </div>

        <div class="table-responsive">
            <table class="table rewards-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Level</th>
                        <th>Total XP</th>
                        <th>Completed Tasks</th>
                        <th style="min-width:160px;">Level Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    @php
                        $progress    = $student->progress;
                        $level       = $progress->level ?? 1;
                        $xp          = $progress->total_xp ?? 0;
                        $completed   = $progress->completed_tasks ?? 0;
                        $levelBaseXp = max(($level - 1) * 100, 0);
                        $nextLevelXp = $level * 100;
                        $levelProgress = $nextLevelXp > $levelBaseXp
                            ? (($xp - $levelBaseXp) / ($nextLevelXp - $levelBaseXp)) * 100
                            : 0;
                        $levelProgress = max(0, min(100, $levelProgress));
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-email">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="level-badge">
                                <i class="bi bi-award-fill"></i>
                                Level {{ $level }}
                            </span>
                        </td>
                        <td>
                            <span class="xp-value">
                                <i class="bi bi-lightning-charge-fill" style="color:#e8a52d;"></i>
                                {{ number_format($xp) }} XP
                            </span>
                        </td>
                        <td>
                            <span class="tasks-value">
                                <i class="bi bi-check-circle-fill"></i>
                                {{ number_format($completed) }} tasks
                            </span>
                        </td>
                        <td>
                            <div class="level-progress-label">
                                <span>Level progress</span>
                                <span style="color:var(--forest);font-weight:700;">{{ round($levelProgress) }}%</span>
                            </div>
                            <div class="level-progress-bar">
                                <div style="width:{{ $levelProgress }}%;"></div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div style="padding:60px 20px;text-align:center;">
                                <div style="width:75px;height:75px;background:#fff8e0;color:#c2851a;display:flex;align-items:center;justify-content:center;border-radius:22px;font-size:32px;margin:0 auto 16px;">
                                    <i class="bi bi-stars"></i>
                                </div>
                                <h5 style="font-weight:800;color:#12382a;font-size:16px;">No XP Data Found</h5>
                                <p style="color:#8a9990;font-size:13px;margin:0;">
                                    Student progress and XP will appear here once students start learning.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="rewards-footer">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <small style="color:#7a8c82;font-weight:600;">
                    Showing <strong>{{ $students->firstItem() }}</strong> to <strong>{{ $students->lastItem() }}</strong>
                    of <strong>{{ $students->total() }}</strong> students
                </small>
                {{ $students->links() }}
            </div>
        </div>
        @endif

    </div>

</div>

@endsection