@extends('admin.layouts.app')

@section('title', 'Task Details')

@section('page-heading', 'Learning Task Details')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <a href="{{ route('admin.tasks.index') }}"
           class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Tasks

        </a>

    </div>


    <div class="row g-4">

        {{-- Task Information --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">

                        <div>

                            <span class="badge bg-primary mb-2">
                                Learning Task
                            </span>

                            <h3 class="fw-bold mb-1">
                                {{ $task->title }}
                            </h3>

                            <p class="text-muted mb-0">
                                Created {{ $task->created_at?->format('d M Y, h:i A') }}
                            </p>

                        </div>


                        @if($task->status === 'completed')

                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>
                                Completed
                            </span>

                        @else

                            <span class="badge bg-warning text-dark fs-6">
                                <i class="bi bi-hourglass-split me-1"></i>
                                Pending
                            </span>

                        @endif

                    </div>


                    <hr>


                    <h5 class="fw-bold mb-3">
                        Task Description
                    </h5>

                    <p class="text-muted">
                        {{ $task->description ?? 'No description available.' }}
                    </p>


                    <div class="row g-3 mt-3">

                        <div class="col-md-6">

                            <div class="bg-light rounded p-3">

                                <small class="text-muted">
                                    Category
                                </small>

                                <div class="fw-bold">
                                    {{ $task->category ?? 'N/A' }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="bg-light rounded p-3">

                                <small class="text-muted">
                                    Difficulty
                                </small>

                                <div class="fw-bold">
                                    {{ ucfirst($task->difficulty ?? 'N/A') }}
                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="bg-light rounded p-3">

                                <small class="text-muted">
                                    Game Type
                                </small>

                                <div class="fw-bold">

                                    <code>
                                        {{ $task->game_type ?? 'N/A' }}
                                    </code>

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="bg-light rounded p-3">

                                <small class="text-muted">
                                    Reward
                                </small>

                                <div class="fw-bold text-primary">
                                    {{ $task->xp ?? 0 }} XP
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Student + Game --}}
        <div class="col-lg-4">


            {{-- Student Card --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">

                        <i class="bi bi-person-circle me-2"></i>
                        Student

                    </h5>


                    @if($task->user)

                        <h6 class="fw-bold">
                            {{ $task->user->name }}
                        </h6>

                        <p class="text-muted mb-0">
                            {{ $task->user->email }}
                        </p>

                    @else

                        <p class="text-muted mb-0">
                            Student no longer exists.
                        </p>

                    @endif

                </div>

            </div>


            {{-- Game Card --}}
            <div class="card border-0 shadow-sm">

                <div class="card-body p-4">

                    <h5 class="fw-bold mb-3">

                        <i class="bi bi-controller me-2"></i>
                        Connected Game

                    </h5>


                    @if($task->game)

                        <h6 class="fw-bold">
                            {{ $task->game->name }}
                        </h6>

                        <p class="text-muted mb-2">
                            {{ $task->game->description }}
                        </p>

                        <div class="mt-3">

                            <span class="badge bg-dark">
                                {{ $task->game->game_type }}
                            </span>

                        </div>

                    @else

                        <p class="text-muted mb-0">
                            No game connected.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection