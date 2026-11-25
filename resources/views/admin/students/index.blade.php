
@extends('admin.layouts.app')

@section('title', 'Students')
@section('page-heading', 'Student Management')

@section('content')

<style>
    :root {
        --eco-primary: #198754;
        --eco-dark: #123c2b;
        --eco-light: #eaf7ef;
        --eco-border: #e5eee8;
        --eco-muted: #718078;
    }

    .student-page {
        padding: 20px 0 35px;
    }

    /* Hero */
    .student-hero {
        background: linear-gradient(120deg, #123c2b, #198754);
        border-radius: 19px;
        padding: 28px 30px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .student-hero::after {
        content: "\f4d7";
        font-family: "bootstrap-icons";
        position: absolute;
        right: 45px;
        top: 5px;
        font-size: 115px;
        opacity: .10;
    }

    .hero-tag {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.18);
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        margin-bottom: 14px;
    }

    .student-hero h2 {
        font-size: 25px;
        font-weight: 750;
        margin-bottom: 8px;
    }

    .student-hero p {
        font-size: 13px;
        color: rgba(255,255,255,.8);
        margin: 0;
        max-width: 550px;
        line-height: 1.7;
    }

    /* Stats */
    .student-stat {
        background: #fff;
        border: 1px solid var(--eco-border);
        border-radius: 15px;
        padding: 19px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
        box-shadow: 0 3px 13px rgba(20,70,40,.035);
    }

    .stat-icon {
        width: 47px;
        height: 47px;
        flex-shrink: 0;
        border-radius: 13px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 21px;
    }

    .stat-icon.green {
        background: #e3f5e9;
        color: #198754;
    }

    .stat-icon.blue {
        background: #e9f1ff;
        color: #3674d9;
    }

    .stat-icon.orange {
        background: #fff1df;
        color: #e48a25;
    }

    .stat-label {
        font-size: 12px;
        color: var(--eco-muted);
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 23px;
        font-weight: 750;
        color: var(--eco-dark);
        line-height: 1.2;
    }

    /* Table container */
    .student-table-card {
        background: #fff;
        border: 1px solid var(--eco-border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(20,70,40,.04);
    }

    .table-top {
        padding: 21px 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        border-bottom: 1px solid var(--eco-border);
    }

    .table-top h5 {
        font-size: 16px;
        font-weight: 750;
        color: var(--eco-dark);
        margin-bottom: 4px;
    }

    .table-top p {
        font-size: 12px;
        color: var(--eco-muted);
        margin: 0;
    }

    .total-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        background: #eaf7ef;
        color: #198754;
        font-size: 12px;
        font-weight: 700;
        border-radius: 9px;
    }

    .progress-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        background: #fff;
        color: #198754;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #bfe6cc;
        border-radius: 9px;
        text-decoration: none;
    }

    .progress-link:hover {
        background: #eaf7ef;
        color: #146c43;
    }

    /* Table */
    .student-table {
        min-width: 900px;
    }

    .student-table thead th {
        background: #f4faf6;
        color: #62746a;
        font-size: 11px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .45px;
        padding: 16px 14px;
        border-bottom: 1px solid var(--eco-border);
        white-space: nowrap;
    }

    .student-table tbody td {
        padding: 15px 14px;
        font-size: 13px;
        color: #405449;
        border-bottom: 1px solid #eff4f0;
        white-space: nowrap;
    }

    .student-table tbody tr {
        transition: background .2s ease;
    }

    .student-table tbody tr:hover {
        background: #f7fcf8;
    }

    .student-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Student avatar */
    .student-avatar {
        width: 42px;
        height: 42px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: linear-gradient(135deg, #d9f3e2, #b8e6c8);
        color: #167345;
        font-size: 16px;
        font-weight: 750;
        text-transform: uppercase;
    }

    .student-name {
        font-weight: 700;
        color: #243d2e;
        font-size: 13px;
    }

    .student-role {
        font-size: 11px;
        color: #8a9990;
        margin-top: 3px;
    }

    .student-email {
        color: #607369;
    }

    /* Level badge */
    .level-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #edf3ff;
        color: #3970c9;
        padding: 6px 10px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 700;
    }

    /* XP */
    .xp-value {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: #c18420;
        font-weight: 750;
    }

    .xp-value i {
        color: #e8a52d;
    }

    /* Completed tasks */
    .completed-value {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #198754;
        font-weight: 700;
    }

    /* Registered date */
    .date-value {
        color: #77877e;
        font-size: 12px;
    }

    /* View button */
    .view-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0f7f2;
        color: #198754;
        border: 1px solid #dcefe2;
        border-radius: 8px;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: .2s;
    }

    .view-btn:hover {
        background: #198754;
        color: #fff;
        border-color: #198754;
        transform: translateY(-1px);
    }

    /* Empty state */
    .empty-state {
        padding: 55px 20px !important;
        text-align: center;
        white-space: normal !important;
    }

    .empty-icon {
        width: 75px;
        height: 75px;
        background: #edf8f0;
        color: #198754;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        border-radius: 22px;
        font-size: 32px;
    }

    .empty-state h5 {
        font-weight: 750;
        color: var(--eco-dark);
        font-size: 16px;
    }

    .empty-state p {
        color: #849188;
        font-size: 13px;
        margin: 0;
    }

    /* Pagination */
    .pagination {
        gap: 5px;
    }

    .pagination .page-link {
        border: 1px solid #e2ece5;
        border-radius: 8px !important;
        color: #456252;
        font-size: 12px;
        padding: 8px 12px;
    }

    .pagination .page-item.active .page-link {
        background: #198754;
        border-color: #198754;
        color: white;
    }

    .pagination .page-link:hover {
        background: #eaf7ef;
        color: #198754;
    }

    @media(max-width: 576px) {
        .student-hero {
            padding: 23px 20px;
        }

        .student-hero h2 {
            font-size: 21px;
        }

        .student-hero::after {
            right: 0;
            font-size: 80px;
        }

        .table-top {
            padding: 17px;
        }
    }
</style>


<div class="container-fluid student-page">

    {{-- HERO --}}
    <div class="student-hero">

        <div class="hero-tag">
            <i class="bi bi-mortarboard-fill"></i>
            ECOQUEST STUDENT CENTER
        </div>

        <h2>
            Student Management
        </h2>

        <p>
            Discover your learning community. Monitor student levels,
            track earned XP and explore their environmental learning journey.
        </p>

    </div>


    {{-- STATISTICS --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-md-6 col-xl-4">

            <div class="student-stat">

                <div class="stat-icon green">
                    <i class="bi bi-people-fill"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Total Registered Students
                    </div>

                    <div class="stat-value">
                        {{ number_format($students->total()) }}
                    </div>
                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 col-xl-4">

            <div class="student-stat">

                <div class="stat-icon blue">
                    <i class="bi bi-person-check-fill"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Student Records
                    </div>

                    <div class="stat-value">
                        {{ $students->count() }}
                    </div>
                </div>

            </div>

        </div>


        <div class="col-12 col-md-6 col-xl-4">

            <div class="student-stat">

                <div class="stat-icon orange">
                    <i class="bi bi-trophy-fill"></i>
                </div>

                <div>
                    <div class="stat-label">
                        Student Progress Tracking
                    </div>

                    <div class="stat-value">
                        <i class="bi bi-graph-up-arrow text-success"></i>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- STUDENT TABLE --}}
    <div class="student-table-card">

        <div class="table-top">

            <div>
                <h5>
                    <i class="bi bi-person-lines-fill me-2 text-success"></i>
                    Registered Students
                </h5>

                <p>
                    View student profiles and monitor their EcoQuest performance.
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.student-progress.index') }}" class="progress-link">
                    <i class="bi bi-bar-chart-fill"></i>
                    View Progress
                </a>

                <div class="total-badge">
                    <i class="bi bi-people-fill"></i>
                    {{ number_format($students->total()) }} Students
                </div>
            </div>

        </div>


        <div class="table-responsive">

            <table class="table student-table align-middle mb-0">

                <thead>

                    <tr>

                        <th class="ps-4">#</th>

                        <th>Student</th>

                        <th>Email Address</th>

                        <th>Level</th>

                        <th>Experience</th>

                        <th>Completed Tasks</th>

                        <th>Joined Date</th>

                        <th class="text-center">Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($students as $student)

                        <tr>

                            {{-- SERIAL NUMBER --}}
                            <td class="ps-4">
                                {{ $students->firstItem() + $loop->index }}
                            </td>


                            {{-- STUDENT INFO --}}
                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div class="student-avatar">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>

                                    <div>

                                        <div class="student-name">
                                            {{ $student->name }}
                                        </div>

                                        <div class="student-role">
                                            <i class="bi bi-mortarboard me-1"></i>
                                            Student
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- EMAIL --}}
                            <td class="student-email">
                                {{ $student->email }}
                            </td>


                            {{-- LEVEL --}}
                            <td>

                                <span class="level-badge">
                                    <i class="bi bi-award-fill"></i>
                                    Level {{ $student->progress->level ?? 1 }}
                                </span>

                            </td>


                            {{-- XP --}}
                            <td>

                                <span class="xp-value">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                    {{ number_format($student->progress->total_xp ?? 0) }} XP
                                </span>

                            </td>


                            {{-- COMPLETED TASKS --}}
                            <td>

                                <span class="completed-value">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ number_format($student->progress->completed_tasks ?? 0) }}
                                </span>

                            </td>


                            {{-- REGISTRATION DATE --}}
                            <td>

                                <span class="date-value">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    {{ $student->created_at?->format('d M Y') }}
                                </span>

                            </td>


                            {{-- ACTION --}}
                            <td class="text-center">

                                <a href="{{ route('admin.students.show', $student->id) }}"
                                   class="view-btn">

                                    <i class="bi bi-eye"></i>
                                    View Profile

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="empty-state">

                                <div class="empty-icon">
                                    <i class="bi bi-people"></i>
                                </div>

                                <h5>No Students Found</h5>

                                <p>
                                    Registered students will appear here
                                    when they join EcoQuest.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- PAGINATION --}}
    @if($students->hasPages())

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">

            <small class="text-muted">

                Showing
                <strong>{{ $students->firstItem() }}</strong>
                to
                <strong>{{ $students->lastItem() }}</strong>
                of
                <strong>{{ $students->total() }}</strong>
                students

            </small>

            <div>
                {{ $students->links() }}
            </div>

        </div>

    @endif

</div>

@endsection
