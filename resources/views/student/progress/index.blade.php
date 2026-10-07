{{-- =========================================================
     EcoQuest — Student Progress Page (AI Theme)
     ========================================================= --}}

@extends('student.layouts.app')

@section('title', 'My Progress | EcoQuest')

@section('head')
<style>
    /* ── Page wrapper ─────────────────────────────────────── */
    .progress-page {
        padding: 28px 30px 60px;
        min-height: 100vh;
        background: var(--page-bg);
    }

    /* ── Page hero banner ─────────────────────────────────── */
    .progress-hero {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 34px 36px;
        margin-bottom: 28px;
        background: linear-gradient(135deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
    }

    .progress-hero::before {
        content: '📈';
        position: absolute;
        right: 30px; top: 10px;
        font-size: 120px;
        opacity: 0.05;
        pointer-events: none;
    }

    .progress-hero h2 {
        font-family: var(--font-display);
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 6px;
    }

    .progress-hero p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin: 0;
    }

    .hero-level-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 50px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #000;
        font-size: 0.85rem;
        font-weight: 700;
        margin-top: 18px;
        box-shadow: 0 4px 14px var(--glow);
    }

    /* ── Stat grid ────────────────────────────────────────── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 18px;
        margin-bottom: 26px;
    }

    .stat-card-inner {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 22px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card-inner:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.3);
    }

    .stat-icon-box {
        width: 52px; height: 52px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        flex-shrink: 0;
        box-shadow: 0 4px 12px var(--glow);
    }

    .stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-family: var(--font-display);
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--secondary);
        line-height: 1;
    }

    /* ── Level progress card ──────────────────────────────── */
    .level-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        padding: 28px;
        margin-bottom: 26px;
    }

    .level-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
    }

    .level-number {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 800;
        color: var(--secondary);
    }

    .level-subtitle {
        color: var(--text-muted);
        font-size: 0.82rem;
        margin-top: 2px;
    }

    .xp-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #000;
        box-shadow: 0 4px 12px var(--glow);
    }

    .xp-track {
        height: 14px;
        background: var(--surface-alt);
        border-radius: 50px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .xp-fill {
        height: 100%;
        border-radius: 50px;
        background: linear-gradient(90deg, var(--primary), var(--secondary));
        box-shadow: 0 0 8px var(--glow);
        transition: width 0.8s ease;
    }

    .xp-labels {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    /* ── Section card ─────────────────────────────────────── */
    .section-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 22px;
        margin-bottom: 26px;
        overflow: hidden;
    }

    .section-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-card-header h5 {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 800;
        color: var(--secondary);
        margin: 0;
    }

    .section-card-header p {
        font-size: 0.78rem;
        color: var(--text-muted);
        margin: 3px 0 0;
    }

    .section-icon {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        flex-shrink: 0;
    }

    /* ── Quest items ──────────────────────────────────────── */
    .quest-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
    }

    .quest-item:last-child { border-bottom: 0; }

    .quest-left { display: flex; align-items: center; min-width: 0; }

    .quest-check {
        width: 40px; height: 40px;
        flex-shrink: 0;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(34,197,94,0.12);
        color: #4ade80;
        font-size: 1rem;
        margin-right: 13px;
    }

    .quest-title {
        color: var(--text-main);
        font-weight: 700;
        font-size: 0.88rem;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .quest-meta {
        color: var(--text-muted);
        font-size: 0.72rem;
    }

    .xp-tag {
        flex-shrink: 0;
        padding: 5px 12px;
        border-radius: 50px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        font-size: 0.72rem;
        font-weight: 700;
        color: #000;
    }

    /* ── Badge items ──────────────────────────────────────── */
    .badge-row {
        display: flex;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
    }

    .badge-row:last-child { border-bottom: 0; }

    .badge-emoji {
        width: 48px; height: 48px;
        flex-shrink: 0;
        border-radius: 14px;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        margin-right: 14px;
    }

    .badge-name {
        color: var(--text-main);
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 3px;
    }

    .badge-date { color: var(--text-muted); font-size: 0.72rem; }

    /* ── Empty state ──────────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        margin-bottom: 14px;
        opacity: 0.6;
    }

    .empty-state h6 {
        color: var(--text-main);
        font-weight: 800;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: var(--text-muted);
        font-size: 0.82rem;
    }

    /* ── Responsive ───────────────────────────────────────── */
    @media (max-width: 768px) {
        .progress-page { padding: 18px 16px 40px; }
        .progress-hero { padding: 24px 20px; border-radius: 18px; }
        .progress-hero h2 { font-size: 1.4rem; }
        .level-number { font-size: 1.6rem; }
        .quest-item { flex-wrap: wrap; }
    }
</style>
@endsection

@section('content')
<div class="progress-page" id="progress">

    {{-- ── TOPBAR ────────────────────────────────────────────────────── --}}
    <div class="theme-topbar mb-0" style="margin: -28px -30px 28px; padding: 18px 30px;">
        <div>
            <div class="theme-topbar-title">
                <i class="bi bi-graph-up-arrow me-2" style="color: var(--secondary);"></i>
                {{ $labels['mission_title'] ?? 'My Progress' }}
            </div>
            <div class="theme-topbar-subtitle">
                Track your journey and level up, {{ $student->name }}
            </div>
        </div>

        <a href="{{ route('student.dashboard') }}" class="btn-theme text-decoration-none d-inline-flex align-items-center gap-2">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
    </div>

    {{-- ── HERO ──────────────────────────────────────────────────────── --}}
    <div class="progress-hero">
        <h2>
            {{ $themeIcon ?? '📈' }}
            {{ $labels['exp_label'] ?? 'XP Progress' }}
        </h2>
        <p>{{ $aiProfile['tagline'] ?? 'Track your quest journey, level up and unlock achievements.' }}</p>

        <div class="hero-level-pill">
            <i class="bi bi-lightning-charge-fill"></i>
            Level {{ $currentLevel }}
            <span style="opacity:0.5;">•</span>
            {{ $progress->total_xp ?? 0 }} XP
        </div>
    </div>


    {{-- ── STAT GRID ─────────────────────────────────────────────────── --}}
    <div class="stat-grid">

        {{-- Total XP --}}
        <div class="stat-card-inner">
            <div class="stat-icon-box">⚡</div>
            <div>
                <div class="stat-label">{{ $labels['exp_label'] ?? 'Total XP' }}</div>
                <div class="stat-value">{{ $progress->total_xp ?? 0 }}</div>
            </div>
        </div>

        {{-- Level --}}
        <div class="stat-card-inner">
            <div class="stat-icon-box">🏆</div>
            <div>
                <div class="stat-label">Current Level</div>
                <div class="stat-value">{{ $currentLevel }}</div>
            </div>
        </div>

        {{-- Quests --}}
        <div class="stat-card-inner">
            <div class="stat-icon-box">✅</div>
            <div>
                <div class="stat-label">{{ $labels['mission_title'] ?? 'Completed Quests' }}</div>
                <div class="stat-value">{{ $progress->completed_tasks ?? 0 }}</div>
            </div>
        </div>

        {{-- Badges --}}
        <div class="stat-card-inner">
            <div class="stat-icon-box">🎖️</div>
            <div>
                <div class="stat-label">Badges Earned</div>
                <div class="stat-value">{{ $badges->count() }}</div>
            </div>
        </div>

    </div>


    {{-- ── LEVEL PROGRESS ────────────────────────────────────────────── --}}
    <div class="level-card">
        <div class="level-card-header">
            <div>
                <div class="level-number">Level {{ $currentLevel }}</div>
                <div class="level-subtitle">
                    Keep completing quests to reach Level {{ $currentLevel + 1 }}
                </div>
            </div>
            <div class="xp-pill">
                <i class="bi bi-star-fill"></i>
                {{ $xpIntoLevel }} / {{ $xpNeededForLevel }} XP
            </div>
        </div>

        <div class="xp-track">
            <div class="xp-fill" style="width: {{ $xpPercentage }}%;"></div>
        </div>

        <div class="xp-labels">
            <span>Level {{ $currentLevel }}</span>
            <span>{{ round($xpPercentage) }}% Complete</span>
            <span>Level {{ $currentLevel + 1 }}</span>
        </div>
    </div>


    {{-- ── COMPLETED QUESTS ──────────────────────────────────────────── --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-icon">🗡️</div>
            <div>
                <h5>{{ $labels['mission_title'] ?? 'Completed Quests' }}</h5>
                <p>Quests you have already conquered</p>
            </div>
        </div>

        @forelse($completedTasks as $task)
            <div class="quest-item">
                <div class="quest-left">
                    <div class="quest-check">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div style="min-width:0;">
                        <div class="quest-title">{{ $task->title }}</div>
                        <div class="quest-meta">
                            {{ ucfirst($task->difficulty ?? 'beginner') }}
                            @if($task->completed_at)
                                &nbsp;•&nbsp;
                                {{ \Carbon\Carbon::parse($task->completed_at)->format('M d, Y') }}
                            @endif
                        </div>
                    </div>
                </div>
                <div class="xp-tag">+{{ $task->xp ?? 0 }} XP</div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🗡️</div>
                <h6>No quests completed yet</h6>
                <p>Head to the dashboard and start your first quest!</p>
                <a href="{{ route('student.dashboard') }}" class="btn-theme text-decoration-none d-inline-flex align-items-center gap-2 mt-2">
                    <i class="bi bi-arrow-left"></i> Go to Dashboard
                </a>
            </div>
        @endforelse
    </div>


    {{-- ── BADGES ────────────────────────────────────────────────────── --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-icon">🏅</div>
            <div>
                <h5>Badges Earned</h5>
                <p>Achievements unlocked on your journey</p>
            </div>
        </div>

        @forelse($badges as $badge)
            <div class="badge-row">
                <div class="badge-emoji">
                    {{ $badge->icon ?? '🏅' }}
                </div>
                <div>
                    <div class="badge-name">{{ $badge->name }}</div>
                    <div class="badge-date">
                        {{ \Carbon\Carbon::parse($badge->pivot->earned_at ?? $badge->created_at)->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-state-icon">🏅</div>
                <h6>No badges yet</h6>
                <p>Complete quests and challenges to earn badges!</p>
            </div>
        @endforelse
    </div>

</div>
@endsection