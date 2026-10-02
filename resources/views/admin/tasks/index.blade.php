@extends('admin.layouts.app')

@section('title', 'Learning Tasks')

@section('page-heading', 'Learning Task Management')

@section('content')

<div class="container-fluid">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}
        </div>
    @endif


    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="fw-bold mb-1">
                        Learning Tasks
                    </h4>

                    <p class="text-muted mb-0">
                        Monitor all student learning tasks and their progress.
                    </p>
                </div>

                <div>
                    <span class="badge bg-primary px-3 py-2">
                        Total: {{ $tasks->total() }}
                    </span>
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Task
                            </th>

                            <th>
                                Student
                            </th>

                            <th>
                                Game
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Difficulty
                            </th>

                            <th>
                                XP
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end px-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($tasks as $task)

                            <tr>

                                {{-- Task --}}
                                <td class="px-4">

                                    <div class="fw-semibold">
                                        {{ $task->title }}
                                    </div>

                                    <small class="text-muted">
                                        {{ Str::limit($task->description, 60) }}
                                    </small>

                                </td>


                                {{-- Student --}}
                                <td>

                                    @if($task->user)

                                        <div class="fw-semibold">
                                            {{ $task->user->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $task->user->email }}
                                        </small>

                                    @else

                                        <span class="text-muted">
                                            Student removed
                                        </span>

                                    @endif

                                </td>


                                {{-- Game --}}
                                <td>

                                    @if($task->game)

                                        <span class="fw-semibold">
                                            {{ $task->game->name }}
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            No Game
                                        </span>

                                    @endif

                                </td>


                                {{-- Game Type --}}
                                <td>

                                    <code>
                                        {{ $task->game_type ?? 'N/A' }}
                                    </code>

                                </td>


                                {{-- Difficulty --}}
                                <td>

                                    @php
                                        $difficultyClass = match(strtolower($task->difficulty ?? '')) {
                                            'easy' => 'success',
                                            'medium' => 'warning',
                                            'hard' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp

                                    <span class="badge bg-{{ $difficultyClass }}">
                                        {{ ucfirst($task->difficulty ?? 'N/A') }}
                                    </span>

                                </td>


                                {{-- XP --}}
                                <td>

                                    <span class="fw-bold text-primary">
                                        {{ $task->xp ?? 0 }} XP
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($task->status === 'completed')

                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Completed
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="text-end px-4">

                                    <a href="{{ route('admin.tasks.show', $task->id) }}"
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

                                    <i class="bi bi-journal-x fs-1 text-muted"></i>

                                    <h5 class="mt-3">
                                        No Learning Tasks Found
                                    </h5>

                                    <p class="text-muted mb-0">
                                        Student learning tasks will appear here.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($tasks->hasPages())

            <div class="card-footer bg-white border-0 p-4">

                {{ $tasks->links() }}

            </div>

        @endif

    </div>

</div>

@endsection