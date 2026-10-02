@extends('admin.layouts.app')

@section('title', 'Game Content Details')

@section('page-heading', 'Game Content Details')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <a href="{{ route('admin.game-content.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Game Content

        </a>

    </div>


    {{-- Game Information --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <span class="badge bg-primary mb-2">
                        Game Content
                    </span>

                    <h3 class="fw-bold mb-1">
                        {{ $game->name }}
                    </h3>

                    <p class="text-muted mb-0">
                        {{ $game->description ?? 'No description available.' }}
                    </p>

                </div>


                <div class="text-end">

                    <small class="text-muted d-block">
                        Game Engine
                    </small>

                    <code>
                        {{ $game->game_type ?? 'N/A' }}
                    </code>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- Game Details --}}

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-0">
                        Game Information
                    </h5>

                </div>


                <div class="card-body p-4">

                    <div class="mb-3">

                        <small class="text-muted">
                            Category
                        </small>

                        <div class="fw-semibold">
                            {{ ucfirst($game->category ?? 'N/A') }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Difficulty
                        </small>

                        <div class="fw-semibold">
                            {{ ucfirst($game->difficulty ?? 'N/A') }}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Engine
                        </small>

                        <div>

                            <code>
                                {{ $game->game_type ?? 'N/A' }}
                            </code>

                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Connected Tasks
                        </small>

                        <div class="fw-bold text-primary fs-5">
                            {{ $game->learningTasks->count() }}
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Connected Content --}}

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">
                        Learning Content
                    </h5>

                    <p class="text-muted mb-0">
                        Tasks currently connected to this game.
                    </p>

                </div>


                <div class="card-body p-0">

                    @forelse($game->learningTasks as $task)

                    <div class="border-bottom p-4">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="fw-bold mb-1">
                                    {{ $task->title }}
                                </h6>

                                <p class="text-muted mb-2">
                                    {{ Str::limit($task->description, 120) }}
                                </p>

                                <span class="badge bg-primary">
                                    {{ $task->xp }} XP
                                </span>

                                <span class="badge bg-light text-dark border">
                                    {{ ucfirst($task->difficulty) }}
                                </span>

                            </div>


                            <div>

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

                            </div>

                        </div>

                    </div>

                    @empty

                    <div class="text-center py-5">

                        <i class="bi bi-file-earmark-x fs-1 text-muted"></i>

                        <h5 class="mt-3">
                            No Learning Content Yet
                        </h5>

                        <p class="text-muted mb-0">
                            This game currently has no connected learning tasks.
                        </p>

                    </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection