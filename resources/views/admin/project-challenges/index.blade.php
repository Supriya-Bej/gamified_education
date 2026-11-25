
@extends('admin.layouts.app')

@section('title', 'Project Challenges')
@section('page-heading', 'Project Challenge Management')

@section('content')

<style>
    .challenge-page {
        --forest: #153d2d;
        --forest-dark: #102f23;
        --emerald: #15966b;
        --navy: #1c2b43;
        --muted: #819087;
        --border: #e7eee9;
        color: #253a30;
    }

    /* HERO */
    .challenge-page .challenge-hero {
        position: relative;
        overflow: hidden;
        padding: 32px;
        border-radius: 22px;
        color: white;
        background: linear-gradient(115deg, #102f23, #185a40 58%, #15966b);
        box-shadow: 0 12px 30px rgba(16, 63, 42, .14);
    }

    .challenge-page .challenge-hero::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        right: -75px;
        top: -135px;
        border: 1px solid rgba(255,255,255,.13);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255,255,255,.035),
                    0 0 0 70px rgba(255,255,255,.025);
    }

    .challenge-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .challenge-page .hero-icon {
        width: 75px;
        height: 75px;
        border-radius: 21px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.2);
        font-size: 34px;
        flex-shrink: 0;
    }

    .challenge-page .hero-label {
        color: #a9e8cf;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .challenge-page .hero-title {
        font-size: 30px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 8px;
    }

    .challenge-page .hero-description {
        color: rgba(255,255,255,.77);
        font-size: 15px;
        margin: 0;
        line-height: 1.7;
    }

    .challenge-page .hero-count {
        min-width: 115px;
        padding: 13px 18px;
        border: 1px solid rgba(255,255,255,.22);
        background: rgba(255,255,255,.12);
        border-radius: 13px;
        text-align: center;
    }

    .challenge-page .hero-count strong {
        display: block;
        font-size: 27px;
        font-weight: 850;
    }

    .challenge-page .hero-count span {
        color: rgba(255,255,255,.75);
        font-size: 11px;
        font-weight: 750;
        letter-spacing: .5px;
    }

    /* SUCCESS MESSAGE */
    .challenge-page .success-message {
        padding: 15px 19px;
        background: #e9f8ef;
        border: 1px solid #ccebd8;
        border-radius: 13px;
        color: #176b45;
        font-size: 14px;
        font-weight: 650;
    }

    /* STAT CARDS */
    .challenge-page .stat-card {
        background: #fff;
        padding: 21px;
        border: 1px solid var(--border);
        border-radius: 17px;
        height: 100%;
        box-shadow: 0 5px 20px rgba(25,65,45,.04);
        transition: .2s;
    }

    .challenge-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(25,65,45,.08);
    }

    .challenge-page .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 14px;
    }

    .challenge-page .stat-number {
        color: var(--forest);
        font-size: 29px;
        font-weight: 850;
        line-height: 1.2;
    }

    .challenge-page .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 6px;
    }

    /* MAIN PANEL */
    .challenge-page .main-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 19px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(25,65,45,.045);
    }

    .challenge-page .panel-header {
        padding: 24px 27px;
        background: linear-gradient(110deg, #fbfdfb, #f3f8f4);
        border-bottom: 1px solid var(--border);
    }

    .challenge-page .panel-title {
        font-size: 21px;
        font-weight: 850;
        color: var(--forest);
        margin-bottom: 5px;
    }

    .challenge-page .panel-subtitle {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    .challenge-page .panel-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #e5f5ec;
        color: #16835a;
        font-size: 21px;
        flex-shrink: 0;
    }

    /* FILTER */
    .challenge-page .filter-area {
        padding: 23px 27px;
        background: #fff;
        border-bottom: 1px solid var(--border);
    }

    .challenge-page .filter-label {
        display: block;
        color: #405a49;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 9px;
    }

    .challenge-page .filter-select {
        width: 100%;
        min-height: 47px;
        padding: 10px 13px;
        border: 1px solid #dfe8e1;
        border-radius: 10px;
        background-color: #fbfdfb;
        color: #354b3d;
        font-size: 14px;
        font-weight: 550;
        box-shadow: none;
    }

    .challenge-page .filter-select:focus {
        border-color: #48a77e;
        box-shadow: 0 0 0 4px rgba(21,150,107,.10);
    }

    .challenge-page .filter-btn {
        min-height: 47px;
        border: 0;
        border-radius: 10px;
        padding: 10px 21px;
        background: linear-gradient(110deg, #126b4a, #15966b);
        color: white;
        font-size: 14px;
        font-weight: 800;
        box-shadow: 0 4px 12px rgba(21,150,107,.18);
    }

    .challenge-page .filter-btn:hover {
        background: #105b40;
        color: white;
    }

    .challenge-page .reset-btn {
        min-height: 47px;
        border: 1px solid #dfe8e1;
        border-radius: 10px;
        padding: 10px 16px;
        background: white;
        color: #607267;
        font-size: 14px;
        font-weight: 750;
    }

    .challenge-page .reset-btn:hover {
        background: #f1f6f2;
    }

    /* TABLE */
    .challenge-page .table {
        margin-bottom: 0;
    }

    .challenge-page .table thead th {
        padding: 17px 16px;
        background: #f0f6f1;
        color: #496251;
        border-bottom: 1px solid #e2ece4;
        font-size: 12px;
        font-weight: 850;
        letter-spacing: .65px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .challenge-page .table tbody td {
        padding: 19px 16px;
        border-bottom: 1px solid #edf2ee;
        color: #53675a;
        font-size: 14px;
        vertical-align: middle;
    }

    .challenge-page .table tbody tr {
        transition: background .2s;
    }

    .challenge-page .table tbody tr:hover {
        background: #f8fcf9;
    }

    .challenge-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .challenge-page .project-info {
        min-width: 210px;
    }

    .challenge-page .project-title {
        color: #193e2d;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.5;
        margin-bottom: 7px;
    }

    .challenge-page .project-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        align-items: center;
        color: #839287;
        font-size: 12px;
        font-weight: 650;
    }

    .challenge-page .xp-label {
        color: #a36b14;
        background: #fff4df;
        padding: 5px 8px;
        border-radius: 6px;
        font-weight: 800;
    }

    .challenge-page .difficulty-label {
        color: #567267;
        background: #edf4ef;
        padding: 5px 8px;
        border-radius: 6px;
        font-weight: 750;
    }

    .challenge-page .student-info {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 130px;
        font-weight: 700;
        color: #3b5948;
    }

    .challenge-page .student-avatar {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e5f3eb;
        color: #17744f;
        font-size: 15px;
        flex-shrink: 0;
    }

    .challenge-page .interest-label {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 8px;
        background: #f0efff;
        color: #6856b7;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    /* STATUS */
    .challenge-page .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 850;
        white-space: nowrap;
    }

    .challenge-page .status-approved {
        color: #167348;
        background: #e4f7eb;
    }

    .challenge-page .status-rejected {
        color: #b83f3a;
        background: #fdebea;
    }

    .challenge-page .status-pending {
        color: #986800;
        background: #fff3d9;
    }

    .challenge-page .status-submitted {
        color: #956000;
        background: #fff3dc;
    }

    .challenge-page .status-revision {
        color: #6e56bd;
        background: #f0ecff;
    }

    .challenge-page .status-none {
        color: #758379;
        background: #eef2ef;
    }

    .challenge-page .status-accepted {
        color: #167348;
        background: #e4f7eb;
    }

    .challenge-page .view-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1px solid #cfe4d6;
        background: #f2faf5;
        color: #16734e;
        padding: 9px 14px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 800;
        text-decoration: none;
        transition: .2s;
        white-space: nowrap;
    }

    .challenge-page .view-btn:hover {
        background: #16734e;
        border-color: #16734e;
        color: white;
    }

    /* EMPTY STATE */
    .challenge-page .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .challenge-page .empty-icon {
        width: 75px;
        height: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 17px;
        border-radius: 22px;
        background: #e9f5ed;
        color: #16835a;
        font-size: 33px;
    }

    .challenge-page .empty-title {
        color: var(--forest);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .challenge-page .empty-description {
        color: var(--muted);
        font-size: 14px;
        margin: 0;
    }

    /* FOOTER AND PAGINATION */
    .challenge-page .panel-footer {
        padding: 20px 25px;
        background: white;
        border-top: 1px solid var(--border);
    }

    .challenge-page .pagination {
        gap: 5px;
        margin: 0;
    }

    .challenge-page .page-link {
        border: 1px solid #e2eae4;
        border-radius: 9px !important;
        color: #35654a;
        padding: 8px 13px;
        font-weight: 700;
        box-shadow: none;
    }

    .challenge-page .page-item.active .page-link {
        background: #16734f;
        border-color: #16734f;
        color: white;
    }

    .challenge-page .page-item.disabled .page-link {
        color: #aab6ad;
        background: #f7faf8;
    }

    @media(max-width: 767px) {
        .challenge-page .challenge-hero {
            padding: 23px 19px;
        }

        .challenge-page .hero-title {
            font-size: 25px;
        }

        .challenge-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .challenge-page .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .challenge-page .panel-header,
        .challenge-page .filter-area {
            padding: 20px 17px;
        }

        .challenge-page .panel-footer {
            padding: 18px;
        }
    }
</style>


<div class="challenge-page">

    {{-- HERO --}}
    <div class="challenge-hero mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-kanban"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-stars me-1"></i>
                            ECOQUEST / CHALLENGE CENTER
                        </div>

                        <h2 class="hero-title">
                            Project Challenge Management
                        </h2>

                        <p class="hero-description">
                            Monitor AI-generated project challenges, student participation and verification progress.
                        </p>
                    </div>

                </div>

                <div class="hero-count">
                    <strong>{{ $challenges->total() }}</strong>
                    <span>CHALLENGES</span>
                </div>

            </div>

        </div>

    </div>


    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="success-message mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="stat-card">

                <div class="stat-icon"
                     style="background:#e8efff;color:#527be8;">
                    <i class="bi bi-kanban"></i>
                </div>

                <div class="stat-number">
                    {{ $challenges->total() }}
                </div>

                <div class="stat-label">
                    Challenges in Current Filter
                </div>

            </div>
        </div>


        <div class="col-md-4">
            <div class="stat-card">

                <div class="stat-icon"
                     style="background:#fff2dc;color:#bd831e;">
                    <i class="bi bi-hourglass-split"></i>
                </div>

                <div class="stat-number" style="font-size:24px;">
                    AI Verification
                </div>

                <div class="stat-label">
                    Automated submission checking
                </div>

            </div>
        </div>


        <div class="col-md-4">
            <div class="stat-card">

                <div class="stat-icon"
                     style="background:#e5f5ec;color:#16835a;">
                    <i class="bi bi-lightning-charge"></i>
                </div>

                <div class="stat-number" style="font-size:24px;">
                    XP Rewards
                </div>

                <div class="stat-label">
                    Awarded according to configured rules
                </div>

            </div>
        </div>

    </div>


    {{-- MAIN MANAGEMENT PANEL --}}
    <div class="main-panel">

        {{-- HEADER --}}
        <div class="panel-header">

            <div class="d-flex align-items-center gap-3">

                <div class="panel-icon">
                    <i class="bi bi-list-check"></i>
                </div>

                <div>
                    <h4 class="panel-title">
                        Challenge Directory
                    </h4>

                    <p class="panel-subtitle">
                        Explore project briefs, assigned students and submission progress.
                    </p>
                </div>

            </div>

        </div>


        {{-- FILTER SECTION --}}
        <div class="filter-area">

            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-funnel-fill"
                   style="color:#16835a;"></i>

                <h6 class="fw-bold mb-0" style="color:#244b35;">
                    Filter Challenges
                </h6>
            </div>

            <form method="GET" class="row g-3 align-items-end">

                <div class="col-lg-4 col-md-5">

                    <label class="filter-label" for="review_status">
                        <i class="bi bi-clipboard-check me-1"></i>
                        Challenge Status
                    </label>

                    <select class="form-select filter-select"
                            name="review_status"
                            id="review_status">

                        @foreach ([
                            'pending_review' => 'Needs review',
                            'approved' => 'Approved',
                            'rejected' => 'Rejected',
                            'all' => 'All challenges'
                        ] as $value => $label)

                            <option value="{{ $value }}"
                                @selected($reviewStatus === $value)>
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-lg-4 col-md-5">

                    <label class="filter-label" for="submission_status">
                        <i class="bi bi-file-earmark-check me-1"></i>
                        Submission Status
                    </label>

                    <select class="form-select filter-select"
                            name="submission_status"
                            id="submission_status">

                        @foreach ([
                            'all' => 'All submissions',
                            'none' => 'Not submitted',
                            'submitted' => 'AI verification pending',
                            'revision_requested' => 'Revision requested',
                            'accepted' => 'Accepted'
                        ] as $value => $label)

                            <option value="{{ $value }}"
                                @selected($submissionStatus === $value)>
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="col-lg-4 col-md-12">

                    <div class="d-flex gap-2">

                        <button class="btn filter-btn flex-grow-1"
                                type="submit">

                            <i class="bi bi-funnel me-2"></i>
                            Apply Filters

                        </button>

                        <a href="{{ route('admin.project-challenges.index') }}"
                           class="btn reset-btn">

                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>


        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th class="ps-4">Project Details</th>
                        <th>Student</th>
                        <th>Interest</th>
                        <th>Challenge Status</th>
                        <th>Submission</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>


                <tbody>

                    @forelse ($challenges as $challenge)

                        @php
                            $submission = $challenge->latestSubmission;

                            $challengeStatus = $challenge->review_status;

                            $challengeStatusClass = match($challengeStatus) {
                                'approved' => 'status-approved',
                                'rejected' => 'status-rejected',
                                default => 'status-pending',
                            };

                            $submissionStatus = $submission?->status;

                            $submissionStatusClass = match($submissionStatus) {
                                'accepted' => 'status-accepted',
                                'revision_requested' => 'status-revision',
                                'submitted' => 'status-submitted',
                                default => 'status-none',
                            };

                            $submissionLabel = match($submissionStatus) {
                                'submitted' => 'AI Verification Pending',
                                'revision_requested' => 'Revision Requested',
                                'accepted' => 'Accepted',
                                default => 'Not Submitted',
                            };
                        @endphp


                        <tr>

                            {{-- PROJECT --}}
                            <td class="ps-4">

                                <div class="project-info">

                                    <div class="project-title">
                                        {{ $challenge->title }}
                                    </div>

                                    <div class="project-meta">

                                        <span class="xp-label">
                                            <i class="bi bi-lightning-charge-fill me-1"></i>
                                            {{ $challenge->xp }} XP
                                        </span>

                                        <span class="difficulty-label">
                                            <i class="bi bi-bar-chart me-1"></i>
                                            {{ ucfirst($challenge->difficulty) }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            {{-- STUDENT --}}
                            <td>

                                <div class="student-info">

                                    <div class="student-avatar">
                                        <i class="bi bi-person-fill"></i>
                                    </div>

                                    <span>
                                        {{ $challenge->student?->name ?? 'Student removed' }}
                                    </span>

                                </div>

                            </td>


                            {{-- INTEREST --}}
                            <td>

                                <span class="interest-label">
                                    <i class="bi bi-bookmark-star me-1"></i>
                                    {{ ucwords(str_replace('_', ' ', $challenge->interest)) }}
                                </span>

                            </td>


                            {{-- CHALLENGE STATUS --}}
                            <td>

                                <span class="status-badge {{ $challengeStatusClass }}">

                                    @if($challengeStatus === 'approved')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif($challengeStatus === 'rejected')
                                        <i class="bi bi-x-circle-fill"></i>
                                    @else
                                        <i class="bi bi-clock-fill"></i>
                                    @endif

                                    {{ $challengeStatus === 'pending_review'
                                        ? 'Needs Review'
                                        : ucfirst($challengeStatus) }}

                                </span>

                            </td>


                            {{-- SUBMISSION STATUS --}}
                            <td>

                                <span class="status-badge {{ $submissionStatusClass }}">

                                    @if($submissionStatus === 'accepted')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif($submissionStatus === 'revision_requested')
                                        <i class="bi bi-arrow-repeat"></i>
                                    @elseif($submissionStatus === 'submitted')
                                        <i class="bi bi-robot"></i>
                                    @else
                                        <i class="bi bi-dash-circle"></i>
                                    @endif

                                    {{ $submissionLabel }}

                                </span>

                            </td>


                            {{-- ACTION --}}
                            <td class="text-end pe-4">

                                <a class="btn view-btn"
                                   href="{{ route('admin.project-challenges.show', $challenge) }}">

                                    <i class="bi bi-eye"></i>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-kanban"></i>
                                    </div>

                                    <h5 class="empty-title">
                                        No Project Challenges Found
                                    </h5>

                                    <p class="empty-description">
                                        No challenges match the selected filters. Try changing your filter options.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if ($challenges->hasPages())

            <div class="panel-footer">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                    <div class="text-muted" style="font-size:14px;">
                        Showing
                        <strong>{{ $challenges->firstItem() }}</strong>
                        to
                        <strong>{{ $challenges->lastItem() }}</strong>
                        of
                        <strong>{{ $challenges->total() }}</strong>
                        challenges
                    </div>

                    {{ $challenges->withQueryString()->links('pagination::bootstrap-5') }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
