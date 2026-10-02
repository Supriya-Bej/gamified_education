@extends('admin.layouts.app')

@section('title', 'Game Details')

@section('page-heading', 'Game Details')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            {{ $game->name }}
        </h3>

        <p class="text-muted mb-0">
            Game configuration and activity
        </p>

    </div>

    <a href="{{ route('admin.games.index') }}"
        class="btn btn-outline-secondary">

        <i class="bi bi-arrow-left me-1"></i>

        Back to Games

    </a>

</div>


<!-- GAME INFORMATION -->

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body p-4">

        <div class="d-flex align-items-center gap-3 mb-4">

            <div
                class="rounded-4 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                style="width:65px;height:65px;">

                <i class="bi bi-controller fs-2"></i>

            </div>

            <div>

                <h4 class="fw-bold mb-1">
                    {{ $game->name }}
                </h4>

                <span class="badge bg-primary-subtle text-primary">

                    {{ $game->game_type ?? 'No game type' }}

                </span>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-md-6">

                <small class="text-muted">
                    Description
                </small>

                <div class="mt-1">
                    {{ $game->description ?? 'No description available.' }}
                </div>

            </div>


            <div class="col-md-3">

                <small class="text-muted">
                    Category
                </small>

                <div class="mt-1 fw-semibold">

                    {{ $game->category ?? '—' }}

                </div>

            </div>


            <div class="col-md-3">

                <small class="text-muted">
                    Difficulty
                </small>

                <div class="mt-1 fw-semibold">

                    {{ $game->difficulty ?? '—' }}

                </div>

            </div>

        </div>

    </div>

</div>


<!-- LEARNING TASKS -->

<div class="card border-0 shadow-sm">

    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>

                <h5 class="fw-bold mb-1">
                    Learning Tasks
                </h5>

                <p class="text-muted mb-0">
                    Tasks currently connected to this game.
                </p>

            </div>

            <span class="badge bg-secondary-subtle text-secondary">

                {{ $game->learningTasks->count() }} Tasks

            </span>

        </div>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Task
                        </th>

                        <th>
                            Student
                        </th>

                        <th>
                            XP
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($game->learningTasks as $task)

                    <tr>

                        <td>
                            {{ $task->title }}
                        </td>

                        <td>
                            Student #{{ $task->user_id }}
                        </td>

                        <td>
                            {{ $task->xp }} XP
                        </td>

                        <td>

                            @if($task->status === 'completed')

                            <span class="badge bg-success">
                                Completed
                            </span>

                            @else

                            <span class="badge bg-warning text-dark">
                                {{ ucfirst($task->status) }}
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4"
                            class="text-center text-muted py-4">

                            No learning tasks are connected to this game yet.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection