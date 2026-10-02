@extends('admin.layouts.app')

@section('title', 'Students')

@section('page-heading', 'Student Management')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="fw-bold mb-1">
            Students
        </h3>

        <p class="text-muted mb-0">
            Manage and monitor registered EcoQuest students.
        </p>
    </div>

    <div>
        <span class="badge bg-success-subtle text-success px-3 py-2">
            {{ $students->total() }} Students
        </span>
    </div>

</div>


<div class="card border-0 shadow-sm">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="px-4">
                            #
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Level
                        </th>

                        <th>
                            XP
                        </th>

                        <th>
                            Completed
                        </th>

                        <th>
                            Registered
                        </th>

                        <th class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($students as $student)

                    <tr>

                        <td class="px-4">
                            {{ $students->firstItem() + $loop->index }}
                        </td>


                        <td>

                            <div class="d-flex align-items-center gap-2">

                                <div class="admin-avatar"
                                    style="width:40px;height:40px;">

                                    {{ strtoupper(substr($student->name, 0, 1)) }}

                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        {{ $student->name }}
                                    </div>

                                    <small class="text-muted">
                                        Student
                                    </small>

                                </div>

                            </div>

                        </td>


                        <td>
                            {{ $student->email }}
                        </td>


                        <td>

                            <span class="badge bg-primary-subtle text-primary">

                                Level
                                {{ $student->progress->level ?? 1 }}

                            </span>

                        </td>


                        <td>

                            <span class="fw-semibold">

                                {{ $student->progress->total_xp ?? 0 }}

                                XP

                            </span>

                        </td>


                        <td>

                            {{ $student->progress->completed_tasks ?? 0 }}

                        </td>


                        <td>

                            {{ $student->created_at?->format('d M Y') }}

                        </td>


                        <td class="text-center">

                            <a href="{{ route('admin.students.show', $student->id) }}"
                                class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-eye me-1"></i>

                                View

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="8"
                            class="text-center py-5">

                            <div class="text-muted">

                                <i class="bi bi-people fs-1"></i>

                                <p class="mt-2 mb-0">
                                    No students found.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@if($students->hasPages())

<div class="mt-4">

    {{ $students->links() }}

</div>

@endif

@endsection