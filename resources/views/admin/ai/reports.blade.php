@extends('admin.layouts.app')

@section('title', 'AI Reports & Verification Rules')
@section('page-heading', 'AI Reports & Verification Rules')

@push('styles')
<style>
    .ai-page {
        --forest: #12382a;
        --green: #167345;
        --teal: #159c91;
        --violet: #6049c0;
        --muted: #7a8c82;
        --border: #e5eee8;
    }

    /* HERO */
    .ai-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        border-radius: 24px;
        color: white;
        background: linear-gradient(115deg, #1a1550 0%, #3b2d9a 55%, #6049c0 100%);
        box-shadow: 0 14px 35px rgba(26, 21, 80, .2);
        margin-bottom: 24px;
    }

    .ai-hero::before {
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

    .ai-hero::after {
        content: "\F3FE";
        font-family: "bootstrap-icons";
        position: absolute;
        right: 36px;
        bottom: -30px;
        font-size: 160px;
        opacity: .07;
        line-height: 1;
    }

    .ai-hero .hero-content { position: relative; z-index: 1; }

    .ai-hero .hero-icon {
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

    .ai-hero .hero-label {
        color: #c5b8ff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .ai-hero .hero-title {
        font-size: 29px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 7px;
    }

    .ai-hero .hero-description {
        color: rgba(255,255,255,.75);
        font-size: 15px;
        margin: 0;
        max-width: 600px;
        line-height: 1.65;
    }

    /* RULES PANEL */
    .ai-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(20,55,40,.045);
    }

    .ai-panel-header {
        padding: 22px 26px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .ai-panel-icon {
        width: 46px;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 20px;
        flex-shrink: 0;
    }

    .ai-panel-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 4px;
    }

    .ai-panel-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    .ai-panel-body {
        padding: 26px;
    }

    /* PIPELINE STEPS */
    .pipeline-steps {
        display: flex;
        gap: 0;
        margin-bottom: 28px;
        border: 1px solid #e8e3ff;
        border-radius: 14px;
        overflow: hidden;
    }

    .pipeline-step {
        flex: 1;
        padding: 16px 18px;
        background: #f9f7ff;
        border-right: 1px solid #e8e3ff;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        position: relative;
    }

    .pipeline-step:last-child { border-right: 0; }

    .pipeline-step-num {
        width: 28px;
        height: 28px;
        background: #6049c0;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
    }

    .pipeline-step-text {
        font-size: 13px;
        color: #3a3060;
        line-height: 1.55;
    }

    /* RULES FORM FIELDS */
    .rules-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .rule-field-label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: #2a2060;
        margin-bottom: 7px;
    }

    .rule-field-input {
        width: 100%;
        min-height: 44px;
        padding: 10px 14px;
        border: 1px solid #dce0ef;
        border-radius: 10px;
        font-size: 14px;
        color: #2a2060;
        background: #fbfaff;
        transition: .2s;
    }

    .rule-field-input:focus {
        border-color: #6049c0;
        box-shadow: 0 0 0 .2rem rgba(96,73,192,.12);
        outline: none;
    }

    .rule-field-hint {
        font-size: 12px;
        color: #9290a8;
        margin-top: 6px;
    }

    .save-rules-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #6049c0;
        color: white;
        border: 0;
        border-radius: 10px;
        padding: 11px 22px;
        font-size: 14px;
        font-weight: 700;
        transition: .2s;
        cursor: pointer;
    }

    .save-rules-btn:hover {
        background: #4a3498;
        transform: translateY(-1px);
    }

    .example-box {
        background: #f4f1ff;
        border: 1px solid #e0d9ff;
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 20px;
        font-size: 14px;
        color: #3b2d9a;
        line-height: 1.65;
    }

    .example-box strong { color: #2a1d7a; }

    /* STAT CARDS */
    .ai-stat-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 22px;
        height: 100%;
        box-shadow: 0 4px 16px rgba(20,55,40,.04);
        transition: .2s;
    }

    .ai-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(20,55,40,.08);
    }

    .ai-stat-card-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .ai-stat-card-title .title-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        font-size: 15px;
    }

    .ai-count-row {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .ai-count-chip {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 12px 16px;
        border-radius: 12px;
        min-width: 80px;
        text-align: center;
        flex: 1;
    }

    .ai-count-chip-value {
        font-size: 22px;
        font-weight: 800;
        line-height: 1.1;
        color: var(--forest);
    }

    .ai-count-chip-label {
        font-size: 11px;
        font-weight: 700;
        margin-top: 4px;
        letter-spacing: .3px;
    }

    /* TABLES */
    .ai-table { margin: 0; }

    .ai-table thead th {
        padding: 15px 15px;
        background: #f7f5ff;
        color: #4a4280;
        border-bottom: 1px solid #e9e6ff;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .ai-table tbody td {
        padding: 16px 15px;
        border-bottom: 1px solid #edf2ee;
        vertical-align: middle;
        color: #3a4a40;
        font-size: 14px;
    }

    .ai-table tbody tr { transition: background .2s; }
    .ai-table tbody tr:hover { background: #fbf9ff; }
    .ai-table tbody tr:last-child td { border-bottom: 0; }

    .quest-link {
        color: #6049c0;
        font-weight: 700;
        text-decoration: none;
    }

    .quest-link:hover {
        color: #3b2d9a;
        text-decoration: underline;
    }

    /* REVIEW STATUS PILLS */
    .review-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .review-pending  { background: #fff8e0; color: #c2851a; }
    .review-approved { background: #e5f5ec; color: #167345; }
    .review-rejected { background: #fff0ef; color: #c24b43; }
    .review-default  { background: #f0f2f1; color: #677370; }

    @media(max-width: 991px) {
        .rules-row { grid-template-columns: 1fr 1fr; }
        .pipeline-steps { flex-direction: column; }
        .pipeline-step { border-right: 0; border-bottom: 1px solid #e8e3ff; }
        .pipeline-step:last-child { border-bottom: 0; }
    }

    @media(max-width: 767px) {
        .ai-hero { padding: 24px 20px; }
        .ai-hero .hero-title { font-size: 23px; }
        .rules-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="ai-page">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" style="font-size:14px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" style="font-size:14px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>Please check the verification rule values below.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- HERO --}}
    <div class="ai-hero">
        <div class="hero-content">
            <div class="d-flex align-items-center gap-3">
                <div class="hero-icon">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <div class="hero-label">
                        <i class="bi bi-cpu-fill me-1"></i> EcoQuest / AI Monitoring
                    </div>
                    <h2 class="hero-title">AI Reports & Verification Rules</h2>
                    <p class="hero-description">
                        Configure quality rules for Gemini-generated content and monitor AI learning quests and project challenges.
                        Rules apply to new content only — existing items are not affected.
                    </p>
                </div>
            </div>
        </div>
    </div>


    


    {{-- STATS --}}
    <div class="row g-3 mb-4">

        @foreach ([
            ['AI Learning Quests', $taskCounts, 'bi-journal-check', '#f4f0ff', '#6049c0'],
            ['AI Project Challenges', $challengeCounts, 'bi-kanban', '#e9f7f1', '#167345'],
        ] as [$label, $counts, $icon, $bg, $color])
        <div class="col-xl-6">
            <div class="ai-stat-card">
                <div class="ai-stat-card-title">
                    <div class="title-icon" style="background:{{ $bg }};color:{{ $color }};">
                        <i class="bi {{ $icon }}"></i>
                    </div>
                    {{ $label }}
                </div>
                <div class="ai-count-row">
                    <div class="ai-count-chip" style="background:#f0f2f1;">
                        <span class="ai-count-chip-value">{{ $counts['total'] }}</span>
                        <span class="ai-count-chip-label" style="color:#677370;">Total</span>
                    </div>
                    <div class="ai-count-chip" style="background:#fff8e0;">
                        <span class="ai-count-chip-value" style="color:#c2851a;">{{ $counts['pending'] }}</span>
                        <span class="ai-count-chip-label" style="color:#c2851a;">Pending</span>
                    </div>
                    <div class="ai-count-chip" style="background:#e5f5ec;">
                        <span class="ai-count-chip-value" style="color:#167345;">{{ $counts['approved'] }}</span>
                        <span class="ai-count-chip-label" style="color:#167345;">Approved</span>
                    </div>
                    <div class="ai-count-chip" style="background:#fff0ef;">
                        <span class="ai-count-chip-value" style="color:#c24b43;">{{ $counts['rejected'] }}</span>
                        <span class="ai-count-chip-label" style="color:#c24b43;">Rejected</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>


    {{-- GENERATED QUESTS TABLE --}}
    <div class="ai-panel mb-4">

        <div class="ai-panel-header">
            <div class="ai-panel-icon" style="background:#f4f0ff;color:#6049c0;">
                <i class="bi bi-journal-check"></i>
            </div>
            <div>
                <div class="ai-panel-title">Generated Learning Quests</div>
                <p class="ai-panel-subtitle">AI-generated game quests assigned to students for learning.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table ai-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Quest Title</th>
                        <th>Student</th>
                        <th>Category</th>
                        <th>Difficulty</th>
                        <th>Review Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('admin.tasks.show', $task) }}" class="quest-link">
                                <i class="bi bi-journal-text me-1"></i>{{ $task->title }}
                            </a>
                        </td>
                        <td style="font-weight:600;color:#1a3828;">
                            {{ $task->user?->name ?? 'Unknown student' }}
                        </td>
                        <td>
                            <span style="background:#edf0ff;color:#4a5fbe;padding:6px 10px;border-radius:7px;font-size:12px;font-weight:700;">
                                {{ ucfirst($task->category ?? 'General') }}
                            </span>
                        </td>
                        <td>
                            @php $diff = strtolower($task->difficulty ?? ''); @endphp
                            <span style="background:{{ $diff==='easy'?'#e5f5ec':($diff==='hard'?'#fff0ef':'#fff8e0') }};
                                         color:{{ $diff==='easy'?'#167345':($diff==='hard'?'#c24b43':'#c2851a') }};
                                         padding:6px 10px;border-radius:7px;font-size:12px;font-weight:700;">
                                {{ ucfirst($task->difficulty ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            @php $status = $task->review_status ?? 'pending_review'; @endphp
                            <span class="review-pill {{ $status==='approved'?'review-approved':($status==='rejected'?'review-rejected':'review-pending') }}">
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </td>
                        <td style="color:#526d5c;font-size:13px;white-space:nowrap;">
                            <i class="bi bi-calendar3 me-1" style="color:#15966b;"></i>
                            {{ $task->created_at?->format('M j, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:45px 20px;color:#8a9990;font-size:14px;">
                            <i class="bi bi-journal-x" style="font-size:30px;display:block;margin-bottom:12px;opacity:.5;"></i>
                            No AI learning quests have been generated yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tasks->hasPages())
        <div style="padding:18px 25px;border-top:1px solid var(--border);background:white;">
            {{ $tasks->links() }}
        </div>
        @endif

    </div>


    {{-- GENERATED CHALLENGES TABLE --}}
    <div class="ai-panel">

        <div class="ai-panel-header">
            <div class="ai-panel-icon" style="background:#e9f7f1;color:#167345;">
                <i class="bi bi-kanban"></i>
            </div>
            <div>
                <div class="ai-panel-title">Generated Real-World Project Challenges</div>
                <p class="ai-panel-subtitle">AI-generated environmental project challenges for students to complete.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table ai-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Challenge Title</th>
                        <th>Student</th>
                        <th>Interest</th>
                        <th>Difficulty</th>
                        <th>Review Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($challenges as $challenge)
                    <tr>
                        <td class="ps-4">
                            <a href="{{ route('admin.project-challenges.show', $challenge) }}" class="quest-link">
                                <i class="bi bi-kanban me-1"></i>{{ $challenge->title }}
                            </a>
                        </td>
                        <td style="font-weight:600;color:#1a3828;">
                            {{ $challenge->student?->name ?? 'Unknown student' }}
                        </td>
                        <td>
                            <span style="background:#f0efff;color:#6856b7;padding:6px 10px;border-radius:7px;font-size:12px;font-weight:700;">
                                {{ $challenge->interest }}
                            </span>
                        </td>
                        <td>
                            @php $diff = strtolower($challenge->difficulty ?? ''); @endphp
                            <span style="background:{{ $diff==='easy'?'#e5f5ec':($diff==='hard'?'#fff0ef':'#fff8e0') }};
                                         color:{{ $diff==='easy'?'#167345':($diff==='hard'?'#c24b43':'#c2851a') }};
                                         padding:6px 10px;border-radius:7px;font-size:12px;font-weight:700;">
                                {{ ucfirst($challenge->difficulty) }}
                            </span>
                        </td>
                        <td>
                            @php $status = $challenge->review_status ?? 'pending_review'; @endphp
                            <span class="review-pill {{ $status==='approved'?'review-approved':($status==='rejected'?'review-rejected':'review-pending') }}">
                                {{ str_replace('_', ' ', ucfirst($status)) }}
                            </span>
                        </td>
                        <td style="color:#526d5c;font-size:13px;white-space:nowrap;">
                            <i class="bi bi-calendar3 me-1" style="color:#15966b;"></i>
                            {{ $challenge->created_at?->format('M j, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:45px 20px;color:#8a9990;font-size:14px;">
                            <i class="bi bi-kanban" style="font-size:30px;display:block;margin-bottom:12px;opacity:.5;"></i>
                            No AI project challenges have been generated yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($challenges->hasPages())
        <div style="padding:18px 25px;border-top:1px solid var(--border);background:white;">
            {{ $challenges->links() }}
        </div>
        @endif

    </div>

</div>

@endsection
