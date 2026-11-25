@extends('admin.layouts.app')

@section('title', 'Badge Management')
@section('page-heading', 'Badge & Achievement Management')

@push('styles')
<style>
    .badges-page {
        --forest: #12382a;
        --green: #167345;
        --amber: #c2851a;
        --purple: #6b4fb0;
        --mint: #e9f5ed;
        --muted: #7a8c82;
        --border: #e5eee8;
    }

    /* HERO */
    .badges-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        border-radius: 24px;
        color: white;
        background: linear-gradient(115deg, #2d1b6e 0%, #553b9a 55%, #8b6ed4 100%);
        box-shadow: 0 14px 35px rgba(45, 27, 110, .2);
        margin-bottom: 24px;
    }

    .badges-hero::before {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        right: -70px;
        top: -150px;
        border: 1px solid rgba(255,255,255,.12);
        border-radius: 50%;
        box-shadow: 0 0 0 40px rgba(255,255,255,.04), 0 0 0 80px rgba(255,255,255,.025);
    }

    .badges-hero::after {
        content: "\F3FD";
        font-family: "bootstrap-icons";
        position: absolute;
        right: 36px;
        bottom: -30px;
        font-size: 160px;
        opacity: .07;
        line-height: 1;
    }

    .badges-hero .hero-content {
        position: relative;
        z-index: 1;
    }

    .badges-hero .hero-icon {
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

    .badges-hero .hero-label {
        color: #d4baff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .badges-hero .hero-title {
        font-size: 30px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 7px;
    }

    .badges-hero .hero-description {
        color: rgba(255,255,255,.75);
        font-size: 15px;
        margin: 0;
        max-width: 540px;
    }

    .badges-hero .add-badge-btn {
        background: white;
        color: #3b1f94;
        border: 0;
        border-radius: 11px;
        padding: 13px 20px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: .2s;
        white-space: nowrap;
    }

    .badges-hero .add-badge-btn:hover {
        background: #f0eaff;
        color: #2d1b6e;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,.12);
    }

    /* STAT CARDS */
    .badge-stat {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 22px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 16px rgba(20,55,40,.04);
        transition: .2s;
    }

    .badge-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(20,55,40,.08);
    }

    .badge-stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
        flex-shrink: 0;
    }

    .badge-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--forest);
        line-height: 1.1;
    }

    .badge-stat-label {
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        margin-top: 3px;
    }

    /* CONTENT PANEL */
    .badges-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(20,55,40,.045);
    }

    .badges-panel-header {
        padding: 24px 26px;
        background: linear-gradient(110deg, #fbfdfb, #f4f0ff);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .badges-panel-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 4px;
    }

    .badges-panel-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .total-pill {
        padding: 9px 15px;
        background: #f0eaff;
        color: #5035a0;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* TABLE */
    .badges-table {
        margin: 0;
    }

    .badges-table thead th {
        padding: 16px 15px;
        background: #f7f5ff;
        color: #5a4d8a;
        border-bottom: 1px solid #e9e3ff;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .badges-table tbody td {
        padding: 18px 15px;
        border-bottom: 1px solid #edf0f0;
        vertical-align: middle;
        color: #3a4a40;
        font-size: 14px;
    }

    .badges-table tbody tr {
        transition: background .2s;
    }

    .badges-table tbody tr:hover {
        background: #fbf8ff;
    }

    .badges-table tbody tr:last-child td {
        border-bottom: 0;
    }

    /* BADGE ICON CELL */
    .badge-identity {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .badge-emoji-wrap {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(145deg, #fff3d9, #ffe8a8);
        font-size: 26px;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(0,0,0,.07);
    }

    .badge-name {
        font-size: 15px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 3px;
    }

    .badge-id-label {
        font-size: 11px;
        color: #9aacaa;
        font-weight: 600;
    }

    /* REQUIREMENT PILL */
    .req-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 11px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .req-xp {
        background: #edf0ff;
        color: #4a5fbe;
    }

    .req-tasks {
        background: #e9f5ed;
        color: #167345;
    }

    .req-other {
        background: #f0f2f1;
        color: #677370;
    }

    .req-value {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 15px;
        font-weight: 800;
        color: var(--forest);
    }

    /* STATUS */
    .status-active {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 30px;
        background: #e5f5ec;
        color: #167345;
        font-size: 12px;
        font-weight: 700;
    }

    .status-inactive {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        border-radius: 30px;
        background: #eef1f0;
        color: #677370;
        font-size: 12px;
        font-weight: 700;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
        flex-shrink: 0;
    }

    /* ACTION BUTTONS */
    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 15px;
        transition: .2s;
    }

    .edit-btn {
        background: #edf0ff;
        border: 1px solid #dce4ff;
        color: #4a5fbe;
        text-decoration: none;
    }

    .edit-btn:hover {
        background: #4a5fbe;
        color: white;
        transform: translateY(-2px);
    }

    .delete-btn {
        background: #fff0ef;
        border: 1px solid #f7dedd;
        color: #c24b43;
    }

    .delete-btn:hover {
        background: #c24b43;
        color: white;
        transform: translateY(-2px);
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 24px;
        background: #f4f0ff;
        color: #6b4fb0;
        font-size: 36px;
    }

    .empty-title {
        color: var(--forest);
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .empty-description {
        color: var(--muted);
        font-size: 15px;
        margin-bottom: 22px;
    }

    .empty-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #553b9a;
        color: white;
        border: 0;
        border-radius: 10px;
        padding: 11px 20px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
    }

    .empty-cta:hover {
        background: #3f2b7a;
        color: white;
    }

    /* PAGINATION */
    .badges-footer {
        padding: 20px 25px;
        background: white;
        border-top: 1px solid var(--border);
    }

    .pagination {
        gap: 5px;
        margin: 0;
    }

    .page-link {
        border: 1px solid #e2e8ea;
        border-radius: 9px !important;
        color: #553b9a;
        font-weight: 700;
        padding: 8px 13px;
        box-shadow: none;
    }

    .page-item.active .page-link {
        background: #553b9a;
        border-color: #553b9a;
        color: white;
    }

    .page-item.disabled .page-link {
        background: #f8faf9;
        color: #aab5ae;
    }

    @media(max-width: 767px) {
        .badges-hero { padding: 24px 20px; }
        .badges-hero .hero-title { font-size: 23px; }
        .badges-hero .hero-icon { width: 58px; height: 58px; font-size: 26px; }
        .badges-panel-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="badges-page">

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" style="font-size:14px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" style="font-size:14px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- HERO HEADER --}}
    <div class="badges-hero">
        <div class="hero-content">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">

                <div class="d-flex align-items-center gap-3">
                    <div class="hero-icon">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <div>
                        <div class="hero-label">
                            <i class="bi bi-stars me-1"></i> EcoQuest / Reward System
                        </div>
                        <h2 class="hero-title">Badges & Achievements</h2>
                        <p class="hero-description">
                            Design motivating badges that reward student milestones and drive deeper learning engagement.
                        </p>
                    </div>
                </div>

                <a href="{{ route('admin.badges.create') }}" class="add-badge-btn">
                    <i class="bi bi-plus-circle-fill"></i>
                    Create New Badge
                </a>

            </div>
        </div>
    </div>


    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="badge-stat">
                <div class="badge-stat-icon" style="background:#f4f0ff;color:#6b4fb0;">
                    <i class="bi bi-award-fill"></i>
                </div>
                <div>
                    <div class="badge-stat-value">{{ $badges->total() }}</div>
                    <div class="badge-stat-label">Total Badges</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="badge-stat">
                <div class="badge-stat-icon" style="background:#e5f5ec;color:#167345;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div>
                    <div class="badge-stat-value">
                        {{ $badges->getCollection()->where('is_active', true)->count() }}
                    </div>
                    <div class="badge-stat-label">Active Badges (This Page)</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="badge-stat">
                <div class="badge-stat-icon" style="background:#eef1f0;color:#677370;">
                    <i class="bi bi-pause-circle-fill"></i>
                </div>
                <div>
                    <div class="badge-stat-value">
                        {{ $badges->getCollection()->where('is_active', false)->count() }}
                    </div>
                    <div class="badge-stat-label">Inactive Badges (This Page)</div>
                </div>
            </div>
        </div>

    </div>


    {{-- BADGE TABLE --}}
    <div class="badges-panel">

        <div class="badges-panel-header">
            <div>
                <div class="badges-panel-title">
                    <i class="bi bi-collection-fill me-2" style="color:#553b9a;"></i>
                    All Badges
                </div>
                <p class="badges-panel-subtitle">Manage badge details, requirements, and active status.</p>
            </div>
            <span class="total-pill">
                <i class="bi bi-award me-1"></i>
                {{ $badges->total() }} Badges
            </span>
        </div>

        <div class="table-responsive">
            <table class="table badges-table align-middle">

                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Badge</th>
                        <th>Description</th>
                        <th>Requirement</th>
                        <th>Threshold</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($badges as $badge)
                    <tr>

                        {{-- ID --}}
                        <td class="ps-4">
                            <span style="color:#9aacaa;font-size:13px;font-weight:700;">#{{ $badge->id }}</span>
                        </td>

                        {{-- BADGE IDENTITY --}}
                        <td>
                            <div class="badge-identity">
                                <div class="badge-emoji-wrap">
                                    @if($badge->icon)
                                        {{ $badge->icon }}
                                    @else
                                        <i class="bi bi-award-fill" style="color:#c2851a;"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="badge-name">{{ $badge->name }}</div>
                                    <div class="badge-id-label">Achievement badge</div>
                                </div>
                            </div>
                        </td>

                        {{-- DESCRIPTION --}}
                        <td>
                            <span style="color:#718279;font-size:13px;max-width:220px;display:block;">
                                {{ $badge->description ?: 'No description provided.' }}
                            </span>
                        </td>

                        {{-- REQUIREMENT TYPE --}}
                        <td>
                            @if($badge->requirement_type === 'total_xp')
                                <span class="req-pill req-xp">
                                    <i class="bi bi-lightning-charge-fill"></i> Total XP
                                </span>
                            @elseif($badge->requirement_type === 'completed_tasks')
                                <span class="req-pill req-tasks">
                                    <i class="bi bi-check2-circle"></i> Completed Tasks
                                </span>
                            @else
                                <span class="req-pill req-other">
                                    <i class="bi bi-tag-fill"></i> {{ $badge->requirement_type }}
                                </span>
                            @endif
                        </td>

                        {{-- REQUIREMENT VALUE --}}
                        <td>
                            <span class="req-value">
                                <i class="bi bi-bullseye" style="color:#553b9a;font-size:13px;"></i>
                                {{ number_format($badge->requirement_value) }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td>
                            @if($badge->is_active)
                                <span class="status-active">
                                    <span class="status-dot"></span> Active
                                </span>
                            @else
                                <span class="status-inactive">
                                    <span class="status-dot"></span> Inactive
                                </span>
                            @endif
                        </td>

                        {{-- ACTIONS --}}
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.badges.edit', $badge->id) }}"
                                   class="action-btn edit-btn" title="Edit Badge">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form action="{{ route('admin.badges.destroy', $badge->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this badge? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn border-0" title="Delete Badge">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="bi bi-award"></i>
                                </div>
                                <h4 class="empty-title">No Badges Created Yet</h4>
                                <p class="empty-description">
                                    Design your first badge to start rewarding students for their achievements.
                                </p>
                                <a href="{{ route('admin.badges.create') }}" class="empty-cta">
                                    <i class="bi bi-plus-circle-fill"></i>
                                    Create First Badge
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION --}}
        @if($badges->hasPages())
        <div class="badges-footer">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <small style="color:#7a8c82;font-weight:600;">
                    Showing <strong>{{ $badges->firstItem() }}</strong> to <strong>{{ $badges->lastItem() }}</strong> of <strong>{{ $badges->total() }}</strong> badges
                </small>
                {{ $badges->links() }}
            </div>
        </div>
        @endif

    </div>

</div>

@endsection