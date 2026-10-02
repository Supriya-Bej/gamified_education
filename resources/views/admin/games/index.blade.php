@extends('admin.layouts.app')

@section('title', 'Game Library')

@section('page-heading', 'Game Library')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h3 class="fw-bold mb-1">
            Game Library
        </h3>

        <p class="text-muted mb-0">
            Manage the reusable games available in EcoQuest.
        </p>

    </div>

    <div class="d-flex align-items-center gap-2">

        <span class="badge bg-primary-subtle text-primary px-3 py-2">

            {{ $games->total() }} Games

        </span>

        <a href="{{ route('admin.games.create') }}"
            class="btn btn-success">

            <i class="bi bi-plus-lg me-1"></i>

            Add Game

        </a>

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
                            Game
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Difficulty
                        </th>

                        <th>
                            Game Type
                        </th>

                        <th>
                            Tasks
                        </th>

                        <th class="text-center">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($games as $game)

                    <tr>

                        <td class="px-4">
                            {{ $games->firstItem() + $loop->index }}
                        </td>


                        <td>

                            <div class="d-flex align-items-center gap-3">

                                <div
                                    class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                                    style="width:42px;height:42px;">

                                    <i class="bi bi-controller fs-5"></i>

                                </div>

                                <div>

                                    <div class="fw-semibold">
                                        {{ $game->name }}
                                    </div>

                                    <small class="text-muted">

                                        {{ \Illuminate\Support\Str::limit($game->description ?? 'No description', 45) }}

                                    </small>

                                </div>

                            </div>

                        </td>


                        <td>

                            <span class="badge bg-success-subtle text-success">

                                {{ $game->category ?? '—' }}

                            </span>

                        </td>


                        <td>

                            <span class="badge bg-warning-subtle text-warning-emphasis">

                                {{ $game->difficulty ?? '—' }}

                            </span>

                        </td>


                        <td>

                            @if($game->game_type)

                            <code>
                                {{ $game->game_type }}
                            </code>

                            @else

                            <span class="text-muted">
                                —
                            </span>

                            @endif

                        </td>


                        <td>

                            {{ $game->learningTasks()->count() }}

                        </td>


                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-1">

                                <a href="{{ route('admin.games.show', $game->id) }}"
                                    class="btn btn-sm btn-outline-primary">

                                    <i class="bi bi-eye"></i>

                                </a>


                                <a href="{{ route('admin.games.edit', $game->id) }}"
                                    class="btn btn-sm btn-outline-warning">

                                    <i class="bi bi-pencil"></i>

                                </a>


                                <form action="{{ route('admin.games.destroy', $game->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this game?');">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                        class="btn btn-sm btn-outline-danger">

                                        <i class="bi bi-trash"></i>

                                    </button>

                                </form>

                            </div>
                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-5">

                            <i class="bi bi-controller fs-1 text-muted"></i>

                            <p class="text-muted mt-2 mb-0">
                                No games found.
                            </p>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@if($games->hasPages())

<div class="mt-4">

    {{ $games->links() }}

</div>

@endif

@endsection