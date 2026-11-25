@extends('admin.layouts.app')

@section('title', 'Leaderboard')
@section('page-heading', 'Leaderboard Management')

@push('styles')
<style>
    .leaderboard-page {
        --gold: #c8961a;
        --gold-bg: #fff8e0;
        --silver: #768798;
        --bronze: #9b6a44;
        --forest: #113425;
        --green: #167345;
        --muted: #7a8c82;
        --border: #e6eee8;
    }

    /* HERO */
    .lb-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        border-radius: 24px;
        color: white;
        background: linear-gradient(115deg, #1a2d16 0%, #2d5a29 50%, #3d8c35 100%);
        box-shadow: 0 14px 35px rgba(26, 45, 22, .2);
        margin-bottom: 24px;
    }

    .lb-hero::before {
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

    .lb-hero::after {
        content: "\F3FE";
        font-family: "bootstrap-icons";
        position: absolute;
        right: 36px;
        bottom: -40px;
        font-size: 180px;
        opacity: .07;
        line-height: 1;
    }

    .lb-hero .hero-content { position: relative; z-index: 1; }

    .lb-hero .hero-icon {
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

    .lb-hero .hero-label {
        color: #b7f5a0;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .lb-hero .hero-title {
        font-size: 29px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 7px;
    }

    .lb-hero .hero-description {
        color: rgba(255,255,255,.75);
        font-size: 15px;
        margin: 0;
        max-width: 540px;
        line-height: 1.65;
    }

    .lb-hero .hero-search-box {
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 14px;
        padding: 16px 20px;
        min-width: 320px;
        backdrop-filter: blur(8px);
    }

    .lb-hero .hero-search-box .form-control {
        background: rgba(255,255,255,.95);
        border: none;
        border-radius: 8px;
        font-size: 14px;
        min-height: 40px;
    }

    .lb-hero .hero-search-box .form-control:focus {
        box-shadow: 0 0 0 .2rem rgba(255,255,255,.25);
    }

    .lb-hero .search-btn {
        background: white;
        color: #2d5a29;
        border: none;
        border-radius: 8px;
        padding: 9px 18px;
        font-weight: 700;
        font-size: 14px;
        transition: .2s;
        white-space: nowrap;
    }

    .lb-hero .search-btn:hover {
        background: #e6f5e3;
        transform: translateY(-1px);
    }

    .lb-hero .clear-btn {
        background: rgba(255,255,255,.18);
        color: white;
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 8px;
        padding: 9px 14px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: .2s;
    }

    .lb-hero .clear-btn:hover {
        background: rgba(255,255,255,.28);
        color: white;
    }

    /* RANK MEDAL */
    .rank-medal {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .rank-gold { background: linear-gradient(135deg, #ffd700, #ffaa00); color: #7a4a00; }
    .rank-silver { background: linear-gradient(135deg, #e0e8f0, #b0c0d0); color: #445566; }
    .rank-bronze { background: linear-gradient(135deg, #e8c090, #c89040); color: #6b3a1a; }
    .rank-other { background: #f0f4f1; color: #718279; }

    /* CONTENT PANELS */
    .lb-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(20,55,40,.045);
    }

    .lb-panel-header {
        padding: 22px 26px;
        background: linear-gradient(110deg, #fbfdfb, #f1f7f2);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .lb-panel-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 4px;
    }

    .lb-panel-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    /* TABLE */
    .lb-table { margin: 0; }

    .lb-table thead th {
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

    .lb-table tbody td {
        padding: 17px 15px;
        border-bottom: 1px solid #edf2ee;
        vertical-align: middle;
        color: #3a4a40;
        font-size: 14px;
    }

    .lb-table tbody tr {
        transition: background .2s;
    }

    .lb-table tbody tr:hover { background: #f7fcf8; }

    .lb-table tbody tr:last-child td { border-bottom: 0; }

    .student-avatar {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #d9f3e2, #b8e6c8);
        color: #167345;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        font-weight: 800;
        flex-shrink: 0;
        text-transform: uppercase;
    }

    .student-name { font-weight: 700; color: #1a3828; font-size: 14px; margin-bottom: 2px; }
    .student-email { color: #8a9990; font-size: 12px; }

    .xp-value {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 800;
        font-size: 15px;
        color: #c8961a;
    }

    /* XP ADJUSTMENT FORM */
    .xp-form-group .form-control-sm {
        border-radius: 8px;
        border: 1px solid #dce6e6;
        font-size: 13px;
        min-height: 36px;
    }

    .xp-form-group .form-control-sm:focus {
        border-color: #70bea7;
        box-shadow: 0 0 0 .2rem rgba(22,124,101,.12);
    }

    .record-btn {
        background: #167345;
        color: white;
        border: 0;
        border-radius: 8px;
        padding: 7px 14px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
        white-space: nowrap;
    }

    .record-btn:hover {
        background: #115535;
        transform: translateY(-1px);
    }

    /* HISTORY TABLE */
    .change-positive { color: #15803d; font-weight: 800; }
    .change-negative { color: #c0392b; font-weight: 800; }

    .admin-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 20px;
        background: #f0f4f1;
        color: #526d5c;
        font-size: 12px;
        font-weight: 700;
    }

    /* PAGINATION */
    .lb-footer {
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

    .page-item.disabled .page-link {
        background: #f8faf9;
        color: #aab5ae;
    }

    @media(max-width: 767px) {
        .lb-hero { padding: 24px 20px; }
        .lb-hero .hero-title { font-size: 23px; }
        .lb-hero .hero-search-box { min-width: auto; width: 100%; }
        .lb-panel-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="leaderboard-page">

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 shadow-sm mb-4">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" style="font-size:14px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- HERO --}}
    <div class="lb-hero">
        <div class="hero-content">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-4">

                <div class="d-flex align-items-center gap-3">
                    <div class="hero-icon">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                    <div>
                        <div class="hero-label">
                            <i class="bi bi-graph-up-arrow me-1"></i> EcoQuest / Student Rankings
                        </div>
                        <h2 class="hero-title">Leaderboard Management</h2>
                        <p class="hero-description">
                            View student XP rankings and manually adjust experience points with a recorded reason.
                        </p>
                    </div>
                </div>

                {{-- SEARCH BOX --}}
                <div class="hero-search-box">
                    <form method="GET" action="{{ route('admin.leaderboard.index') }}">
                        <label class="d-block text-white mb-2" style="font-size:12px;font-weight:700;opacity:.85;">
                            <i class="bi bi-search me-1"></i> Search Students
                        </label>
                        <div class="d-flex gap-2">
                            <label for="leaderboard-search" class="visually-hidden">Search students</label>
                            <input id="leaderboard-search" type="search" name="search" value="{{ $search }}"
                                class="form-control" placeholder="Name or email…" maxlength="100">
                            <button class="search-btn" type="submit">Search</button>
                            @if($search !== '')
                                <a class="clear-btn" href="{{ route('admin.leaderboard.index') }}">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    {{-- RANKINGS TABLE --}}
    <div class="lb-panel mb-4">

        <div class="lb-panel-header">
            <div>
                <div class="lb-panel-title">
                    <i class="bi bi-bar-chart-steps me-2" style="color:#167345;"></i>
                    Student Rankings
                </div>
                <p class="lb-panel-subtitle">Ranked by total XP earned. Adjust XP with a reason for the audit log.</p>
            </div>
            <span class="total-pill" style="padding:9px 15px;background:#e6f5ec;color:#14734f;border-radius:30px;font-size:13px;font-weight:800;">
                <i class="bi bi-people-fill me-1"></i>
                {{ $students->total() }} Students
            </span>
        </div>

        <div class="table-responsive">
            <table class="table lb-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Rank</th>
                        <th>Student</th>
                        <th>Total XP</th>
                        <th>Adjust XP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="ps-4">
                            @php $rank = $student->leaderboard_rank; @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="rank-medal {{ $rank === 1 ? 'rank-gold' : ($rank === 2 ? 'rank-silver' : ($rank === 3 ? 'rank-bronze' : 'rank-other')) }}">
                                    @if($rank <= 3)
                                        <i class="bi bi-trophy-fill"></i>
                                    @else
                                        {{ $rank }}
                                    @endif
                                </div>
                                @if($rank <= 3)
                                    <span style="font-weight:800;color:{{ $rank===1?'#c8961a':($rank===2?'#768798':'#9b6a44') }};">
                                        #{{ $rank }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td>
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
                            <span class="xp-value">
                                <i class="bi bi-lightning-charge-fill" style="color:#e8a52d;"></i>
                                {{ number_format($student->total_xp) }} XP
                            </span>
                        </td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.leaderboard.xp.adjust', ['student' => $student->id, 'search' => $search]) }}"
                                  class="d-flex flex-wrap gap-2 xp-form-group">
                                @csrf
                                <label for="xp-change-{{ $student->id }}" class="visually-hidden">XP adjustment for {{ $student->name }}</label>
                                <input id="xp-change-{{ $student->id }}" type="number" name="xp_change"
                                    class="form-control form-control-sm" style="max-width:110px;"
                                    min="-2147483647" max="2147483647" placeholder="+/− XP" required>
                                <label for="xp-reason-{{ $student->id }}" class="visually-hidden">Reason for {{ $student->name }}</label>
                                <input id="xp-reason-{{ $student->id }}" type="text" name="reason"
                                    class="form-control form-control-sm" style="max-width:200px;"
                                    maxlength="500" placeholder="Reason for adjustment" required>
                                <button type="submit" class="record-btn">
                                    <i class="bi bi-save me-1"></i> Record
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">
                            <div style="padding:55px 20px;text-align:center;">
                                <div style="width:75px;height:75px;background:#e8f5ed;color:#167345;display:flex;align-items:center;justify-content:center;border-radius:22px;font-size:32px;margin:0 auto 16px;">
                                    <i class="bi bi-trophy"></i>
                                </div>
                                <h5 style="font-weight:800;color:#113425;font-size:16px;">
                                    {{ $search !== '' ? 'No Matching Students Found' : 'No Students Available' }}
                                </h5>
                                <p style="color:#8a9990;font-size:13px;margin:0;">
                                    {{ $search !== '' ? 'Try a different name or email.' : 'Student rankings will appear here as they earn XP.' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($students->hasPages())
        <div class="lb-footer">
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


    {{-- XP ADJUSTMENT HISTORY --}}
    <div class="lb-panel">

        <div class="lb-panel-header">
            <div>
                <div class="lb-panel-title">
                    <i class="bi bi-clock-history me-2" style="color:#167345;"></i>
                    XP Adjustment History
                </div>
                <p class="lb-panel-subtitle">Recent admin XP corrections, with reason and administrator recorded.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table lb-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Date & Time</th>
                        <th>Student</th>
                        <th>XP Change</th>
                        <th>Reason</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adjustment)
                    <tr>
                        <td class="ps-4">
                            <span style="color:#526d5c;font-size:13px;white-space:nowrap;">
                                <i class="bi bi-calendar3 me-1" style="color:#15966b;"></i>
                                {{ $adjustment->created_at->format('M j, Y') }}
                                <small class="d-block" style="color:#9aacaa;">{{ $adjustment->created_at->format('H:i') }}</small>
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:700;color:#1a3828;">
                                {{ $adjustment->student?->name ?? 'Deleted student' }}
                            </span>
                        </td>
                        <td>
                            <span class="{{ $adjustment->xp_change > 0 ? 'change-positive' : 'change-negative' }}">
                                <i class="bi bi-{{ $adjustment->xp_change > 0 ? 'arrow-up-circle-fill' : 'arrow-down-circle-fill' }} me-1"></i>
                                {{ $adjustment->xp_change > 0 ? '+' : '' }}{{ number_format($adjustment->xp_change) }} XP
                            </span>
                        </td>
                        <td>
                            <span style="color:#526d5c;font-size:13px;max-width:220px;display:block;">
                                {{ $adjustment->reason }}
                            </span>
                        </td>
                        <td>
                            <span class="admin-chip">
                                <i class="bi bi-person-badge-fill"></i>
                                {{ $adjustment->admin?->name ?? 'Deleted admin' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding:45px 20px;color:#8a9990;">
                            <i class="bi bi-clock-history" style="font-size:32px;display:block;margin-bottom:12px;opacity:.5;"></i>
                            No XP adjustments have been recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($adjustments->hasPages())
        <div class="lb-footer">
            {{ $adjustments->links() }}
        </div>
        @endif

    </div>

</div>

@endsection
