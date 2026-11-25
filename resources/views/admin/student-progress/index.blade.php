@extends('admin.layouts.app')

@section('title', 'Student Progress')
@section('page-heading', 'Student Progress')

@section('content')

{{-- =====================================================
     STYLES - grouped by page part
     ===================================================== --}}
<style>
    /* 1. COLORS - change them here and the whole page updates */
    :root {
        --green: #198754;
        --green-dark: #123c2b;
        --green-soft: #eaf7ef;
        --border: #e3ece6;
        --text: #263d30;
        --muted: #78877e;
    }

    .progress-page {
        padding: 25px 0 40px;
    }

    /* 2. TOP BANNER */
    .banner {
        position: relative;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: center;
        justify-content: space-between;
        padding: 30px 32px;
        margin-bottom: 24px;
        color: #fff;
        border-radius: 22px;
        background: linear-gradient(120deg, var(--green-dark), var(--green));
        box-shadow: 0 12px 30px rgba(18, 60, 43, .25);
    }

    .banner::after {
        content: "🏆";
        position: absolute;
        right: 150px;
        top: -12px;
        font-size: 120px;
        opacity: .13;
        transform: rotate(-15deg);
    }

    .banner h2 {
        margin: 0 0 6px;
        font-size: 26px;
        font-weight: 750;
    }

    .banner p {
        margin: 0;
        max-width: 520px;
        font-size: 14px;
        line-height: 1.6;
        color: rgba(255, 255, 255, .82);
    }

    /* the "12 students" pill */
    .banner-count {
        position: relative;
        z-index: 1;
        padding: 12px 22px;
        text-align: center;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 16px;
    }

    .banner-count strong {
        display: block;
        font-size: 26px;
        line-height: 1.1;
    }

    .banner-count span {
        font-size: 11px;
        letter-spacing: .5px;
        opacity: .85;
    }

    /* 3. TABLE CARD */
    .table-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 3px 15px rgba(20, 70, 40, .04);
    }

    .table-card table {
        margin: 0;
    }

    .table-card thead th {
        padding: 15px 12px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: var(--muted);
        background: #f4faf6;
        border-bottom: 1px solid var(--border);
    }

    .table-card tbody td {
        padding: 16px 12px;
        border-color: #eef4f0;
    }

    /* row lights up when the mouse is over it */
    .table-card tbody tr {
        transition: background .15s;
    }

    .table-card tbody tr:hover {
        background: #f7fcf9;
    }

    /* 4. STUDENT CELL (avatar + name + email) */
    .student-cell {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .avatar {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        border-radius: 50%;
        background: linear-gradient(135deg, #2fb36f, var(--green-dark));
    }

    .student-name {
        font-size: 14px;
        font-weight: 650;
        color: var(--text);
    }

    .student-email {
        font-size: 12px;
        color: var(--muted);
    }

    /* 5. LEVEL BADGE + XP + TASKS */
    .level-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        font-size: 12px;
        font-weight: 700;
        color: #8a5a00;
        background: #fff6dd;
        border: 1px solid #f6e3a8;
        border-radius: 30px;
    }

    .xp-value {
        font-size: 14px;
        font-weight: 700;
        color: var(--green);
    }

    .tasks-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 13px;
        font-size: 12px;
        font-weight: 650;
        color: #315c43;
        background: var(--green-soft);
        border-radius: 30px;
    }

    /* 6. DETAILS BUTTON */
    .btn-details {
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 650;
        color: var(--green);
        background: #fff;
        border: 1px solid #cfe3d6;
        border-radius: 10px;
        transition: .2s;
    }

    .btn-details:hover {
        color: #fff;
        background: var(--green);
        border-color: var(--green);
        transform: translateY(-1px);
    }

    /* 7. EMPTY STATE */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: var(--muted);
    }

    .empty-state i {
        display: block;
        margin-bottom: 10px;
        font-size: 42px;
        color: #b9d8c6;
    }

    /* 8. PAGINATION AREA */
    .table-footer {
        padding: 14px 22px;
        background: #fff;
        border-top: 1px solid var(--border);
    }

    /* 9. MOBILE */
    @media (max-width: 576px) {
        .banner {
            padding: 24px 20px;
        }

        .banner h2 {
            font-size: 21px;
        }

        .banner::after {
            right: -10px;
            font-size: 90px;
        }
    }
</style>


<div class="container-fluid progress-page">

    {{-- 1. BANNER --}}
    <div class="banner">
        <div>
            <h2><i class="bi bi-bar-chart-fill me-2" aria-hidden="true"></i>Student Progress</h2>
            <p>
                A read-only overview of every student's level, XP and
                completed tasks. See who is leveling up!
            </p>
        </div>

        <div class="banner-count">
            <strong>{{ number_format($students->total()) }}</strong>
            <span>STUDENTS</span>
        </div>
    </div>


    {{-- 2. TABLE --}}
    <div class="table-card">

        <div class="table-responsive">
            <table class="table align-middle">

                <thead>
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Level</th>
                        <th>Experience</th>
                        <th>Completed tasks</th>
                        <th class="text-end pe-4">Details</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- One row for every student --}}
                    @forelse ($students as $student)
                    <tr>

                        {{-- Student: avatar with first letter, name, email --}}
                        <td class="ps-4">
                            <div class="student-cell">
                                <div class="avatar">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-email">{{ $student->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Level (shows 1 if the student has no progress yet) --}}
                        <td>
                            <span class="level-badge">
                                <i class="bi bi-star-fill" aria-hidden="true"></i>
                                Level {{ $student->progress->level ?? 1 }}
                            </span>
                        </td>

                        {{-- Total XP --}}
                        <td>
                            <span class="xp-value">
                                <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>
                                {{ number_format($student->progress->total_xp ?? 0) }} XP
                            </span>
                        </td>

                        {{-- Completed tasks --}}
                        <td>
                            <span class="tasks-pill">
                                <i class="bi bi-check2-circle" aria-hidden="true"></i>
                                {{ number_format($student->progress->completed_tasks ?? 0) }}
                            </span>
                        </td>

                        {{-- Link to the student's detail page --}}
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.students.show', $student) }}"
                                class="btn btn-details"
                                aria-label="View progress details for {{ $student->name }}">
                                View details
                                <i class="bi bi-arrow-up-right ms-1" aria-hidden="true"></i>
                            </a>
                        </td>

                    </tr>

                    {{-- Shown when there are no students --}}
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="bi bi-people" aria-hidden="true"></i>
                                No students are available yet.
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Page numbers (only if there is more than one page) --}}
        @if ($students->hasPages())
        <div class="table-footer">
            {{ $students->links() }}
        </div>
        @endif

    </div>

</div>

@endsection