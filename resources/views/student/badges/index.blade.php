{{-- =========================================================
     EcoQuest — Student Badges Page (AI Theme)
     ========================================================= --}}

@extends('student.layouts.app')

@section('title', 'My Badges | EcoQuest')

@section('head')
<style>
    /* ── Page ─────────────────────────────────────────────── */
    .badges-page {
        padding: 28px 30px 60px;
        min-height: 100vh;
        background: var(--page-bg);
    }

    /* ── Hero ─────────────────────────────────────────────── */
    .badge-hero {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        padding: 34px 36px;
        margin-bottom: 28px;
        background: linear-gradient(135deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
    }

    .badge-hero::before {
        content: '🏆';
        position: absolute;
        right: 28px; top: 8px;
        font-size: 120px;
        opacity: 0.05;
        pointer-events: none;
    }

    .badge-hero h1 {
        font-family: var(--font-display);
        font-size: clamp(1.4rem, 3.5vw, 2rem);
        font-weight: 800;
        color: var(--secondary);
        margin-bottom: 8px;
    }

    .badge-hero p {
        color: var(--text-muted);
        font-size: 0.9rem;
        max-width: 600px;
        margin: 0;
    }

    .badge-count-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 50px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #000;
        font-size: 0.82rem;
        font-weight: 700;
        margin-top: 18px;
        box-shadow: 0 4px 14px var(--glow);
    }

    /* ── Badge card grid ──────────────────────────────────── */
    .badge-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }

    .badge-card {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        background: linear-gradient(145deg, var(--surface), var(--surface-alt));
        border: 1px solid var(--border);
        transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
    }

    .badge-card:hover {
        transform: translateY(-7px);
        border-color: var(--secondary);
        box-shadow: 0 16px 36px rgba(0,0,0,0.3), 0 0 0 1px var(--glow);
    }

    .badge-card::before {
        content: '';
        position: absolute;
        width: 120px; height: 120px;
        right: -50px; top: -50px;
        border-radius: 50%;
        background: var(--glow);
        opacity: 0.08;
    }

    .badge-body {
        padding: 28px 22px;
        text-align: center;
        position: relative;
        z-index: 1;
    }

    .badge-icon-wrap {
        width: 90px; height: 90px;
        margin: 0 auto 18px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 44px;
        background: linear-gradient(135deg,
            rgba(var(--primary), 0.12),
            rgba(var(--secondary), 0.05));
        border: 2px solid var(--border);
        box-shadow: 0 0 28px var(--glow);
        transition: box-shadow 0.3s ease;
    }

    .badge-card:hover .badge-icon-wrap {
        box-shadow: 0 0 40px var(--glow);
    }

    .badge-name {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 8px;
    }

    .badge-desc {
        color: var(--text-muted);
        font-size: 0.78rem;
        line-height: 1.6;
        min-height: 40px;
        margin-bottom: 16px;
    }

    .earned-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 50px;
        background: rgba(34,197,94,0.10);
        border: 1px solid rgba(34,197,94,0.20);
        color: #4ade80;
        font-size: 0.72rem;
        font-weight: 700;
    }

    /* ── Empty state ──────────────────────────────────────── */
    .empty-card {
        grid-column: 1 / -1;
        text-align: center;
        padding: 70px 24px;
        border-radius: 22px;
        background: var(--surface);
        border: 1px dashed var(--border);
    }

    .empty-icon { font-size: 4rem; margin-bottom: 16px; opacity: 0.6; }

    .empty-card h3 {
        font-family: var(--font-display);
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 10px;
    }

    .empty-card p {
        color: var(--text-muted);
        font-size: 0.85rem;
        margin-bottom: 22px;
    }

    /* ── Responsive ───────────────────────────────────────── */
    @media (max-width: 768px) {
        .badges-page { padding: 18px 14px 40px; }
        .badge-hero { padding: 22px 20px; border-radius: 18px; }
        .badge-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 14px; }
    }
</style>
@endsection

@section('content')
<div class="badges-page">

    {{-- ── TOPBAR ────────────────────────────────────────────────────── --}}
    <div class="theme-topbar mb-0" style="margin: -28px -30px 28px; padding: 18px 30px;">
        <div>
            <div class="theme-topbar-title">
                <i class="bi bi-award-fill me-2" style="color: var(--secondary);"></i>
                My Badges
            </div>
            <div class="theme-topbar-subtitle">
                Achievements unlocked by {{ $student->name ?? 'you' }}
            </div>
        </div>

        <a href="{{ route('student.dashboard') }}" class="btn-theme text-decoration-none d-inline-flex align-items-center gap-2">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
    </div>

    {{-- ── HERO ──────────────────────────────────────────────────────── --}}
    <div class="badge-hero">
        <h1>🏅 Achievement Collection</h1>
        <p>Every badge represents a milestone conquered on your gamified learning journey.</p>

        <div class="badge-count-pill">
            <i class="bi bi-award-fill"></i>
            {{ $badges->count() }} {{ Str::plural('Badge', $badges->count()) }} Earned
        </div>
    </div>


    {{-- ── BADGE GRID ────────────────────────────────────────────────── --}}
    <div class="badge-grid">

        @forelse($badges as $badge)
            <div class="badge-card">
                <div class="badge-body">
                    <div class="badge-icon-wrap">
                        {{ $badge->icon ?? '🏅' }}
                    </div>

                    <div class="badge-name">{{ $badge->name }}</div>

                    <div class="badge-desc">
                        {{ $badge->description ?? 'A special achievement badge.' }}
                    </div>

                    <div class="earned-tag">
                        <i class="bi bi-check-circle-fill"></i>
                        Earned {{
                            \Carbon\Carbon::parse(
                                $badge->pivot->earned_at ?? $badge->created_at
                            )->format('M d, Y')
                        }}
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-card">
                <div class="empty-icon">🏅</div>
                <h3>No Badges Yet</h3>
                <p>Complete quests and challenges to unlock your first achievement badge!</p>
                <a href="{{ route('student.dashboard') }}"
                   class="btn-theme text-decoration-none d-inline-flex align-items-center gap-2">
                    <i class="bi bi-lightning-charge-fill"></i> Start a Quest
                </a>
            </div>
        @endforelse

    </div>

</div>
@endsection