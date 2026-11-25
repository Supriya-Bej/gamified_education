@extends('admin.layouts.app')

@section('title', 'Certificate Management')
@section('page-heading', 'Certificate Management')

@push('styles')
<style>
    .certs-page {
        --forest: #12382a;
        --green: #167345;
        --amber: #c2851a;
        --blue: #2d5faf;
        --muted: #7a8c82;
        --border: #e5eee8;
    }

    /* HERO */
    .certs-hero {
        position: relative;
        overflow: hidden;
        padding: 36px;
        border-radius: 24px;
        color: white;
        background: linear-gradient(115deg, #152966 0%, #2d5faf 55%, #3a7bd5 100%);
        box-shadow: 0 14px 35px rgba(21, 41, 102, .18);
        margin-bottom: 24px;
    }

    .certs-hero::before {
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

    .certs-hero::after {
        content: "\F3FD";
        font-family: "bootstrap-icons";
        position: absolute;
        right: 36px;
        bottom: -30px;
        font-size: 160px;
        opacity: .07;
        line-height: 1;
    }

    .certs-hero .hero-content { position: relative; z-index: 1; }

    .certs-hero .hero-icon {
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

    .certs-hero .hero-label {
        color: #adc8ff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    .certs-hero .hero-title {
        font-size: 29px;
        font-weight: 850;
        letter-spacing: -.6px;
        margin-bottom: 7px;
    }

    .certs-hero .hero-description {
        color: rgba(255,255,255,.75);
        font-size: 15px;
        margin: 0;
        max-width: 540px;
        line-height: 1.65;
    }

    /* STAT CARDS */
    .cert-stat {
        background: white;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 22px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 4px 16px rgba(20,55,40,.04);
        transition: .2s;
    }

    .cert-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(20,55,40,.08);
    }

    .cert-stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
        flex-shrink: 0;
    }

    .cert-stat-value {
        font-size: 26px;
        font-weight: 800;
        color: var(--forest);
        line-height: 1.1;
    }

    .cert-stat-label {
        color: var(--muted);
        font-size: 13px;
        font-weight: 600;
        margin-top: 4px;
    }

    /* CONTENT PANELS */
    .certs-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 22px rgba(20,55,40,.045);
    }

    .certs-panel-header {
        padding: 22px 26px;
        background: linear-gradient(110deg, #fbfdfb, #f1f5ff);
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .certs-panel-title {
        font-size: 18px;
        font-weight: 800;
        color: var(--forest);
        margin-bottom: 4px;
    }

    .certs-panel-subtitle {
        font-size: 13px;
        color: var(--muted);
        margin: 0;
    }

    /* TABLE */
    .certs-table { margin: 0; }

    .certs-table thead th {
        padding: 15px 15px;
        background: #f4f7ff;
        color: #4a5a90;
        border-bottom: 1px solid #e5e9f7;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .6px;
        white-space: nowrap;
    }

    .certs-table tbody td {
        padding: 17px 15px;
        border-bottom: 1px solid #edf2ee;
        vertical-align: middle;
        color: #3a4a40;
        font-size: 14px;
    }

    .certs-table tbody tr { transition: background .2s; }
    .certs-table tbody tr:hover { background: #f7faff; }
    .certs-table tbody tr:last-child td { border-bottom: 0; }

    .student-name { font-weight: 700; color: #1a3828; font-size: 14px; margin-bottom: 2px; }
    .student-email { color: #8a9990; font-size: 12px; }

    /* STATUS BADGES */
    .cert-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .cert-status-pending { background: #fff8e0; color: #c2851a; }
    .cert-status-issued  { background: #e5f5ec; color: #167345; }
    .cert-status-revoked { background: #fff0ef; color: #c24b43; }
    .cert-status-none    { background: #f0f2f1; color: #677370; }

    .cert-num {
        font-size: 11px;
        color: #9aacaa;
        margin-top: 3px;
        font-family: monospace;
    }

    .score-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #e5f5ec;
        color: #167345;
        padding: 7px 11px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 800;
    }

    /* ACTION BUTTONS */
    .approve-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #2d5faf;
        color: white;
        border: 0;
        border-radius: 9px;
        padding: 8px 15px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
        white-space: nowrap;
        cursor: pointer;
    }

    .approve-btn:hover {
        background: #1d4a8a;
        transform: translateY(-1px);
        color: white;
    }

    .reissue-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #167345;
        color: white;
        border: 0;
        border-radius: 9px;
        padding: 8px 15px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
        white-space: nowrap;
        cursor: pointer;
    }

    .reissue-btn:hover {
        background: #115535;
        transform: translateY(-1px);
        color: white;
    }

    .revoke-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff0ef;
        color: #c24b43;
        border: 1px solid #f7dedd;
        border-radius: 9px;
        padding: 8px 15px;
        font-size: 13px;
        font-weight: 700;
        transition: .2s;
        white-space: nowrap;
        cursor: pointer;
    }

    .revoke-btn:hover {
        background: #c24b43;
        color: white;
        border-color: #c24b43;
        transform: translateY(-1px);
    }

    @media(max-width: 767px) {
        .certs-hero { padding: 24px 20px; }
        .certs-hero .hero-title { font-size: 23px; }
        .certs-panel-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="certs-page">

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


    {{-- HERO --}}
    <div class="certs-hero">
        <div class="hero-content">
            <div class="d-flex align-items-center gap-3">
                <div class="hero-icon">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <div class="hero-label">
                        <i class="bi bi-award me-1"></i> EcoQuest / Achievement System
                    </div>
                    <h2 class="hero-title">Certificate Management</h2>
                    <p class="hero-description">
                        Approve interest-course certificates after verifying that students completed all three game stages.
                        Issue or revoke perfect-quest certificates with a full audit trail.
                    </p>
                </div>
            </div>
        </div>
    </div>


    {{-- STAT CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="cert-stat">
                <div class="cert-stat-icon" style="background:#fff8e0;color:#c2851a;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="cert-stat-value">{{ $courseCertificates->where('status', 'pending_review')->count() }}</div>
                    <div class="cert-stat-label">Pending Approval</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="cert-stat">
                <div class="cert-stat-icon" style="background:#e5f5ec;color:#167345;">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <div>
                    <div class="cert-stat-value">
                        {{ $courseCertificates->where('status', 'issued')->count() + ($attempts->filter(fn($a) => $a->certificate?->status === 'issued')->count()) }}
                    </div>
                    <div class="cert-stat-label">Issued Certificates</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="cert-stat">
                <div class="cert-stat-icon" style="background:#edf0ff;color:#2d5faf;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <div>
                    <div class="cert-stat-value">{{ $attempts->total() }}</div>
                    <div class="cert-stat-label">Perfect Quest Attempts</div>
                </div>
            </div>
        </div>

    </div>


    {{-- INTEREST COURSE CERTIFICATES --}}
    <div class="certs-panel mb-4">

        <div class="certs-panel-header">
            <div>
                <div class="certs-panel-title">
                    <i class="bi bi-journal-bookmark-fill me-2" style="color:#2d5faf;"></i>
                    Interest Course Certificates
                </div>
                <p class="certs-panel-subtitle">Approve certificates for students who completed all three game stages in an interest course.</p>
            </div>
            <span style="padding:9px 15px;background:#e9efff;color:#2d5faf;border-radius:30px;font-size:13px;font-weight:800;">
                <i class="bi bi-collection me-1"></i>
                {{ $courseCertificates->count() }} Records
            </span>
        </div>

        <div class="table-responsive">
            <table class="table certs-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Interest</th>
                        <th>Course</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courseCertificates as $certificate)
                    <tr>
                        <td class="ps-4">
                            <div class="student-name">{{ $certificate->user->name }}</div>
                            <div class="student-email">{{ $certificate->user->email }}</div>
                        </td>
                        <td>
                            <span style="font-weight:700;color:#2d5faf;">
                                <i class="bi bi-bookmark-fill me-1"></i>
                                {{ ucfirst($certificate->interest) }}
                            </span>
                        </td>
                        <td>
                            <span style="color:#526d5c;font-weight:600;">{{ $certificate->title }}</span>
                        </td>
                        <td>
                            @if($certificate->status === 'pending_review')
                                <span class="cert-status cert-status-pending">
                                    <i class="bi bi-hourglass-split"></i> Awaiting Approval
                                </span>
                            @elseif($certificate->status === 'issued')
                                <span class="cert-status cert-status-issued">
                                    <i class="bi bi-patch-check-fill"></i> Issued
                                </span>
                                <div class="cert-num">{{ $certificate->issued_at?->format('M d, Y') }}</div>
                            @else
                                <span class="cert-status cert-status-revoked">
                                    <i class="bi bi-x-circle-fill"></i>
                                    {{ $certificate->status === 'revoked' ? 'Revoked' : 'Not Issued' }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if(in_array($certificate->status, ['pending_review', 'revoked'], true))
                                <form method="POST" action="{{ route('admin.certificates.approve', $certificate) }}">
                                    @csrf
                                    <button type="submit" class="approve-btn">
                                        <i class="bi bi-patch-check-fill"></i>
                                        {{ $certificate->status === 'revoked' ? 'Re-issue' : 'Approve & Issue' }}
                                    </button>
                                </form>
                            @elseif($certificate->status === 'issued')
                                <form method="POST" action="{{ route('admin.certificates.revoke', $certificate) }}"
                                      onsubmit="return confirm('Revoke this certificate? The student will no longer be able to view it.')">
                                    @csrf
                                    <button type="submit" class="revoke-btn">
                                        <i class="bi bi-x-circle"></i> Revoke
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding:45px 20px;color:#8a9990;font-size:14px;">
                            <i class="bi bi-journal-x" style="font-size:32px;display:block;margin-bottom:12px;opacity:.5;"></i>
                            No interest-course certificates are awaiting approval.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>


    {{-- PERFECT QUEST CERTIFICATES --}}
    <div class="certs-panel">

        <div class="certs-panel-header">
            <div>
                <div class="certs-panel-title">
                    <i class="bi bi-stars me-2" style="color:#c2851a;"></i>
                    Perfect Quest Certificates
                </div>
                <p class="certs-panel-subtitle">Students who achieved 100% on a game quest are eligible for a certificate.</p>
            </div>
            <span style="padding:9px 15px;background:#fff8e0;color:#c2851a;border-radius:30px;font-size:13px;font-weight:800;">
                <i class="bi bi-stars me-1"></i>
                {{ $attempts->total() }} Attempts
            </span>
        </div>

        <div class="table-responsive">
            <table class="table certs-table align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Quest</th>
                        <th>Score</th>
                        <th>Attempt Date</th>
                        <th>Certificate</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                    <tr>
                        <td class="ps-4">
                            <div class="student-name">{{ $attempt->user->name }}</div>
                            <div class="student-email">{{ $attempt->user->email }}</div>
                        </td>
                        <td>
                            <span style="color:#526d5c;font-weight:700;font-size:14px;">
                                <i class="bi bi-controller me-1" style="color:#3f78db;"></i>
                                {{ $attempt->task->title }}
                            </span>
                        </td>
                        <td>
                            <span class="score-badge">
                                <i class="bi bi-star-fill" style="color:#c2851a;"></i>
                                {{ $attempt->score }}%
                            </span>
                        </td>
                        <td>
                            <span style="color:#526d5c;font-size:13px;">
                                <i class="bi bi-calendar3 me-1" style="color:#15966b;"></i>
                                {{ $attempt->created_at->format('M d, Y') }}
                            </span>
                        </td>
                        <td>
                            @if($attempt->certificate?->status === 'issued')
                                <span class="cert-status cert-status-issued">
                                    <i class="bi bi-patch-check-fill"></i> Issued
                                </span>
                                <div class="cert-num">{{ $attempt->certificate->certificate_number }}</div>
                            @elseif($attempt->certificate?->status === 'revoked')
                                <span class="cert-status cert-status-revoked">
                                    <i class="bi bi-x-circle-fill"></i> Revoked
                                </span>
                            @else
                                <span class="cert-status cert-status-none">
                                    <i class="bi bi-dash-circle"></i> Not Issued
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @if($attempt->certificate?->status === 'issued')
                                <form method="POST" action="{{ route('admin.certificates.revoke', $attempt->certificate) }}"
                                      onsubmit="return confirm('Revoke this certificate? The student will no longer be able to view it.')">
                                    @csrf
                                    <button type="submit" class="revoke-btn">
                                        <i class="bi bi-x-circle"></i> Revoke
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.certificates.issue', $attempt) }}">
                                    @csrf
                                    <button type="submit" class="{{ $attempt->certificate?->status === 'revoked' ? 'reissue-btn' : 'approve-btn' }}">
                                        <i class="bi bi-patch-check-fill"></i>
                                        {{ $attempt->certificate?->status === 'revoked' ? 'Re-issue' : 'Issue Certificate' }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:50px 20px;color:#8a9990;font-size:14px;">
                            <i class="bi bi-stars" style="font-size:36px;display:block;margin-bottom:14px;opacity:.4;"></i>
                            No students have achieved a 100% quest score yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attempts->hasPages())
        <div style="padding:18px 25px;border-top:1px solid var(--border);background:white;">
            {{ $attempts->links() }}
        </div>
        @endif

    </div>

</div>

@endsection
