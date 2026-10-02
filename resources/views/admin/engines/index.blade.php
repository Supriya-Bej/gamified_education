@extends('admin.layouts.app')

@section('title', 'Game Engines')

@section('page-heading', 'Game Engine Management')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="fw-bold mb-1">
                        Game Engines
                    </h4>

                    <p class="text-muted mb-0">
                        Monitor the reusable game engines used by EcoQuest games.
                    </p>
                </div>

                <div>
                    <span class="badge bg-dark px-3 py-2">
                        {{ $engines->count() }} Engines
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
                                #
                            </th>

                            <th>
                                Engine
                            </th>

                            <th>
                                Engine Key
                            </th>

                            <th>
                                Games
                            </th>

                            <th>
                                Learning Tasks
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

                        @forelse($engines as $index => $engine)

                        <tr>

                            {{-- Number --}}
                            <td class="px-4">

                                {{ $index + 1 }}

                            </td>


                            {{-- Engine --}}
                            <td>

                                <div class="fw-semibold">

                                    @php
                                    $engineName = match(strtolower($engine->game_type)) {
                                    'arithmetic_speed' => 'Arithmetic Speed',
                                    'algorithm_race' => 'Algorithm Race',
                                    'sorting_challenge' => 'Sorting Challenge',
                                    'creative_coding' => 'Creative Coding',
                                    'physics_quiz' => 'Physics Quiz',
                                    default => ucwords(str_replace('_', ' ', $engine->game_type)),
                                    };
                                    @endphp

                                    {{ $engineName }}

                                </div>

                            </td>


                            {{-- Engine Key --}}
                            <td>

                                <code>
                                    {{ $engine->game_type }}
                                </code>

                            </td>


                            {{-- Games --}}
                            <td>

                                <span class="badge bg-primary">
                                    {{ $engine->game_count }}
                                </span>

                            </td>


                            {{-- Tasks --}}
                            <td>

                                <span class="badge bg-info text-dark">
                                    {{ $engine->task_count }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td>

                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Active
                                </span>

                            </td>


                            {{-- Action --}}
                            <td class="text-end px-4">

                                <a href="{{ route('admin.engines.show', $engine->game_type) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye me-1"></i>
                                    View

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7"
                                class="text-center py-5">

                                <i class="bi bi-controller fs-1 text-muted"></i>

                                <h5 class="mt-3">
                                    No Game Engines Found
                                </h5>

                                <p class="text-muted mb-0">
                                    Add a game with a valid game type first.
                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection