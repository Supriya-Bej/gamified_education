@extends('admin.layouts.app')

@section('title', 'Game Content')

@section('page-heading', 'Game Content Management')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4 class="fw-bold mb-1">
                        Game Content
                    </h4>

                    <p class="text-muted mb-0">
                        Manage and inspect the content connected to EcoQuest games.
                    </p>

                </div>

                <span class="badge bg-primary px-3 py-2">
                    {{ $games->total() }} Games
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Game
                            </th>

                            <th>
                                Engine
                            </th>

                            <th>
                                Category
                            </th>

                            <th>
                                Difficulty
                            </th>

                            <th>
                                Learning Tasks
                            </th>

                            <th>
                                Content Status
                            </th>

                            <th class="text-end px-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($games as $game)

                        <tr>

                            {{-- Game --}}
                            <td class="px-4">

                                <div class="fw-semibold">
                                    {{ $game->name }}
                                </div>

                                <small class="text-muted">
                                    {{ Str::limit($game->description, 55) }}
                                </small>

                            </td>


                            {{-- Engine --}}
                            <td>

                                <code>
                                    {{ $game->game_type ?? 'N/A' }}
                                </code>

                            </td>


                            {{-- Category --}}
                            <td>

                                <span class="badge bg-light text-dark border">
                                    {{ ucfirst($game->category ?? 'N/A') }}
                                </span>

                            </td>


                            {{-- Difficulty --}}
                            <td>

                                @php

                                $difficultyClass = match(
                                strtolower($game->difficulty ?? '')
                                ) {
                                'easy' => 'success',
                                'medium' => 'warning',
                                'hard' => 'danger',
                                default => 'secondary'
                                };

                                @endphp

                                <span class="badge bg-{{ $difficultyClass }}">
                                    {{ ucfirst($game->difficulty ?? 'N/A') }}
                                </span>

                            </td>


                            {{-- Tasks --}}
                            <td>

                                <span class="badge bg-info text-dark">
                                    {{ $game->learning_tasks_count }}
                                </span>

                            </td>


                            {{-- Content Status --}}
                            <td>

                                @if($game->learning_tasks_count > 0)

                                <span class="badge bg-success">

                                    <i class="bi bi-check-circle me-1"></i>

                                    Available

                                </span>

                                @else

                                <span class="badge bg-secondary">

                                    <i class="bi bi-dash-circle me-1"></i>

                                    No Tasks

                                </span>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="text-end px-4">

                                <a href="{{ route('admin.game-content.show', $game->id) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye me-1"></i>

                                    View Content

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <i class="bi bi-collection fs-1 text-muted"></i>

                                <h5 class="mt-3">
                                    No Games Found
                                </h5>

                                <p class="text-muted mb-0">
                                    Game content will appear here after games are added.
                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($games->hasPages())

        <div class="card-footer bg-white border-0 p-4">

            {{ $games->links() }}

        </div>

        @endif

    </div>

</div>

@endsection