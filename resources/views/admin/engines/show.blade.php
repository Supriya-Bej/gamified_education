@extends('admin.layouts.app')

@section('title', 'Engine Details')

@section('page-heading', 'Game Engine Details')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <a href="{{ route('admin.engines.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-1"></i>
            Back to Engines

        </a>

    </div>


    {{-- Engine Header --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-3">

                <div class="bg-primary text-white rounded-3 p-3">

                    <i class="bi bi-controller fs-3"></i>

                </div>

                <div>

                    <h3 class="fw-bold mb-1">

                        @php
                        $engineName = match(strtolower($gameType)) {
                        'arithmetic_speed' => 'Arithmetic Speed',
                        'algorithm_race' => 'Algorithm Race',
                        'sorting_challenge' => 'Sorting Challenge',
                        'creative_coding' => 'Creative Coding',
                        'physics_quiz' => 'Physics Quiz',
                        default => ucwords(str_replace('_', ' ', $gameType)),
                        };
                        @endphp

                        {{ $engineName }}

                    </h3>

                    <code>
                        {{ $gameType }}
                    </code>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- Games --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-controller me-2"></i>
                        Games Using This Engine
                    </h5>

                    <p class="text-muted mb-0">
                        Game library entries connected to this engine.
                    </p>

                </div>


                <div class="card-body">

                    @forelse($games as $game)

                    <div class="border rounded-3 p-3 mb-3">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h6 class="fw-bold mb-1">
                                    {{ $game->name }}
                                </h6>

                                <small class="text-muted">
                                    {{ $game->category }}
                                </small>

                            </div>

                            <a href="{{ route('admin.games.show', $game->id) }}"
                                class="btn btn-sm btn-outline-primary">

                                View

                            </a>

                        </div>

                    </div>

                    @empty

                    <p class="text-muted mb-0">
                        No games are connected to this engine.
                    </p>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Tasks --}}
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 p-4">

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-journal-text me-2"></i>
                        Learning Tasks
                    </h5>

                    <p class="text-muted mb-0">
                        Tasks currently using this engine.
                    </p>

                </div>


                <div class="card-body">

                    @forelse($tasks as $task)

                    <div class="border rounded-3 p-3 mb-3">

                        <h6 class="fw-bold mb-1">
                            {{ $task->title }}
                        </h6>

                        @if($task->user)

                        <small class="text-muted">
                            Student:
                            {{ $task->user->name }}
                        </small>

                        @else

                        <small class="text-muted">
                            Student unavailable
                        </small>

                        @endif

                        <div class="mt-2">

                            @if($task->status === 'completed')

                            <span class="badge bg-success">
                                Completed
                            </span>

                            @else

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                            @endif

                            <span class="badge bg-primary">
                                {{ $task->xp }} XP
                            </span>

                        </div>

                    </div>

                    @empty

                    <p class="text-muted mb-0">
                        No learning tasks are using this engine.
                    </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>

@endsection