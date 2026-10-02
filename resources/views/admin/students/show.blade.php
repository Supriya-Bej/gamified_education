@extends('admin.layouts.app')

@section('title', 'Student Details')

@section('page-heading', 'Student Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            {{ $student->name }}
        </h3>

        <p class="text-muted mb-0">
            Student learning profile
        </p>
    </div>

    <a href="{{ route('admin.students.index') }}"
       class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>

        Back to Students

    </a>

</div>


<!-- BASIC INFORMATION -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-4">

        <h5 class="fw-bold mb-4">
            <i class="bi bi-person-circle me-2"></i>
            Basic Information
        </h5>

        <div class="row g-4">

            <div class="col-md-6">

                <small class="text-muted">
                    Name
                </small>

                <div class="fw-semibold">
                    {{ $student->name }}
                </div>

            </div>

            <div class="col-md-6">

                <small class="text-muted">
                    Email
                </small>

                <div class="fw-semibold">
                    {{ $student->email }}
                </div>

            </div>

            <div class="col-md-6">

                <small class="text-muted">
                    Registered On
                </small>

                <div class="fw-semibold">
                    {{ $student->created_at?->format('d M Y, h:i A') }}
                </div>

            </div>

            <div class="col-md-6">

                <small class="text-muted">
                    Role
                </small>

                <div>
                    <span class="badge bg-success">
                        Student
                    </span>
                </div>

            </div>

        </div>

    </div>

</div>


<!-- PROGRESS -->

<div class="row g-4 mb-4">

    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <small class="text-muted">
                    Total XP
                </small>

                <h2 class="fw-bold mt-2 mb-0">
                    {{ $student->progress->total_xp ?? 0 }}
                </h2>

                <span class="text-success">
                    XP
                </span>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <small class="text-muted">
                    Current Level
                </small>

                <h2 class="fw-bold mt-2 mb-0">
                    {{ $student->progress->level ?? 1 }}
                </h2>

                <span class="text-primary">
                    Level
                </span>

            </div>

        </div>

    </div>


    <div class="col-md-4">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body p-4">

                <small class="text-muted">
                    Completed Tasks
                </small>

                <h2 class="fw-bold mt-2 mb-0">
                    {{ $student->progress->completed_tasks ?? 0 }}
                </h2>

                <span class="text-success">
                    Completed
                </span>

            </div>

        </div>

    </div>

</div>


<!-- COMPLETED TASKS -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            Completed Tasks
        </h5>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Task</th>
                        <th>Game</th>
                        <th>XP</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($completedTasks as $task)

                        <tr>

                            <td>
                                {{ $task->title }}
                            </td>

                            <td>
                                {{ $task->game_type ?? '—' }}
                            </td>

                            <td>
                                <strong>
                                    {{ $task->xp }} XP
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-success">
                                    Completed
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">

                                No completed tasks yet.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- PENDING TASKS -->

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <h5 class="fw-bold mb-3">
            <i class="bi bi-hourglass-split text-warning me-2"></i>
            Pending Tasks
        </h5>

        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Task</th>
                        <th>Game</th>
                        <th>XP</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($pendingTasks as $task)

                        <tr>

                            <td>
                                {{ $task->title }}
                            </td>

                            <td>
                                {{ $task->game_type ?? '—' }}
                            </td>

                            <td>
                                <strong>
                                    {{ $task->xp }} XP
                                </strong>
                            </td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    Pending
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="text-center text-muted py-4">

                                No pending tasks.

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection