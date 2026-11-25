@extends('admin.layouts.app')

@section('title', 'Game Library')
@section('page-heading', 'Game Library')

@section('content')

{{-- =====================================================
     STYLES - grouped by page part
     ===================================================== --}}
<style>
    /* 1. COLORS - change them here and the whole page updates */
    .library-page {
        --green: #168653;
        --green-dark: #103d2b;
        --green-soft: #e8f5ed;
        --border: #e6eee8;
        --text: #203c2d;
        --muted: #78877e;
        color: var(--text);
        padding-bottom: 30px;
    }

    /* 2. TOP BANNER */
    .library-page .banner {
        position: relative;
        overflow: hidden;
        padding: 36px;
        margin-bottom: 24px;
        color: #fff;
        border-radius: 24px;
        background: linear-gradient(115deg, var(--green-dark), #176b46 65%, #239966);
        box-shadow: 0 14px 35px rgba(18, 61, 44, .22);
    }

    /* decorative rings in the corner */
    .library-page .banner::after {
        content: "";
        position: absolute;
        width: 300px;
        height: 300px;
        right: -90px;
        top: -160px;
        border: 1px solid rgba(255, 255, 255, .14);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
                    0 0 0 75px rgba(255, 255, 255, .025);
    }

    .library-page .banner-inner { position: relative; z-index: 1; }

    .library-page .banner-icon {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 76px;
        height: 76px;
        font-size: 36px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .22);
        border-radius: 22px;
    }

    .library-page .banner-tag {
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.8px;
        text-transform: uppercase;
        color: #c8ead7;
    }

    .library-page .banner h2 {
        margin-bottom: 8px;
        font-size: clamp(28px, 3vw, 36px);
        font-weight: 800;
        letter-spacing: -.8px;
    }

    .library-page .banner p {
        max-width: 600px;
        margin: 0;
        font-size: 15px;
        line-height: 1.7;
        color: rgba(255, 255, 255, .78);
    }

    .library-page .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 22px;
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
        text-decoration: none;
        color: #145536;
        background: #fff;
        border-radius: 13px;
        transition: .2s;
    }

    .library-page .btn-add:hover {
        color: var(--green-dark);
        background: #e6f5eb;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(0, 0, 0, .15);
    }

    /* 3. STAT CARDS (3 small boxes with numbers) */
    .library-page .stat-card {
        height: 100%;
        padding: 22px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(25, 60, 42, .045);
        transition: .2s;
    }

    .library-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(25, 60, 42, .09);
    }

    .library-page .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        margin-bottom: 16px;
        font-size: 23px;
        border-radius: 15px;
    }

    /* one color for each stat icon */
    .library-page .stat-icon.green  { color: #168653; background: #e6f5eb; }
    .library-page .stat-icon.yellow { color: #c58a20; background: #fff3dd; }
    .library-page .stat-icon.blue   { color: #5475ca; background: #e9efff; }

    .library-page .stat-value { font-size: 31px; font-weight: 800; line-height: 1.2; color: #193e2c; }
    .library-page .stat-label { margin-top: 6px; font-size: 14px; font-weight: 600; color: var(--muted); }

    /* 4. TABLE PANEL */
    .library-page .panel {
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 20px;
        box-shadow: 0 5px 22px rgba(25, 60, 42, .045);
    }

    .library-page .panel-head {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: center;
        justify-content: space-between;
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }

    .library-page .panel-title { margin: 0 0 4px; font-size: 21px; font-weight: 800; color: #193e2c; }
    .library-page .panel-subtitle { margin: 0; font-size: 14px; color: var(--muted); }

    .library-page .count-pill {
        padding: 9px 16px;
        font-size: 14px;
        font-weight: 800;
        color: #167345;
        background: var(--green-soft);
        border-radius: 30px;
    }

    .library-page .game-table { min-width: 950px; margin: 0; }

    .library-page .game-table thead th {
        padding: 17px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .65px;
        text-transform: uppercase;
        white-space: nowrap;
        color: #68796e;
        background: #f4f8f5;
        border-bottom: 1px solid var(--border);
    }

    .library-page .game-table tbody td {
        padding: 18px 17px;
        vertical-align: middle;
        font-size: 14px;
        color: #405648;
        border-color: #edf2ee;
    }

    /* row lights up when the mouse is over it */
    .library-page .game-table tbody tr { transition: background .2s; }
    .library-page .game-table tbody tr:hover { background: #f8fbf8; }

    /* 5. GAME CELL (icon + name + description) */
    .library-page .game-avatar {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        font-size: 24px;
        color: var(--green);
        background: linear-gradient(145deg, #e5f5e9, #d3eedd);
        border-radius: 16px;
    }

    .library-page .game-name { margin-bottom: 3px; font-size: 15px; font-weight: 800; color: #214632; }
    .library-page .game-description { font-size: 13px; line-height: 1.5; color: #839087; }

    /* 6. BADGES (category, difficulty, type) */
    .library-page .badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 13px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        border-radius: 30px;
    }

    .library-page .badge-category { color: #167345; background: #e7f5eb; }
    .library-page .badge-type     { color: #5266ad; background: #edf0ff; }

    /* difficulty has a color for each level */
    .library-page .badge-easy   { color: #167345; background: #e3f6e9; }
    .library-page .badge-medium { color: #94620e; background: #fff3d9; }
    .library-page .badge-hard   { color: #b0392f; background: #fde8e6; }
    .library-page .badge-other  { color: #5d6b63; background: #eef2ef; }

    .library-page .task-count {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 15px;
        font-weight: 800;
        color: var(--green);
    }

    /* 7. ACTION BUTTONS (view / edit / delete) */
    .library-page .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        font-size: 16px;
        border-radius: 11px;
        transition: .2s;
    }

    .library-page .action-btn:hover { transform: translateY(-2px); }

    /* 8. EMPTY STATE (when there are no games) */
    .library-page .empty-state { padding: 65px 20px; text-align: center; }

    .library-page .empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 82px;
        height: 82px;
        margin: 0 auto 20px;
        font-size: 37px;
        color: var(--green);
        background: var(--green-soft);
        border-radius: 24px;
    }

    /* 9. PAGINATION */
    .library-page .pagination-area { padding: 18px 24px; background: #f9fbf9; border-top: 1px solid var(--border); }
    .library-page .pagination { margin: 0; gap: 5px; }
    .library-page .pagination .page-link { padding: 8px 14px; font-weight: 600; color: #286344; border: 1px solid #e4ebe5; border-radius: 9px; }
    .library-page .pagination .active .page-link { color: #fff; background: var(--green); border-color: var(--green); }
    .library-page .pagination .disabled .page-link { color: #aab5ad; background: #f5f7f5; }

    /* 10. MOBILE */
    @media (max-width: 767px) {
        .library-page .banner { padding: 25px 20px; }
        .library-page .banner-icon { width: 62px; height: 62px; font-size: 29px; }
        .library-page .stat-value { font-size: 27px; }
        .library-page .panel-head { padding: 18px; }
        .library-page .pagination-area { padding: 16px; }
    }
</style>


<div class="library-page">

    {{-- 1. MESSAGES (shown after add / edit / delete) --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- 2. BANNER --}}
    <div class="banner">
        <div class="banner-inner d-flex flex-wrap justify-content-between align-items-center gap-4">

            <div class="d-flex align-items-center gap-3">
                <div class="banner-icon">
                    <i class="bi bi-controller"></i>
                </div>

                <div>
                    <div class="banner-tag">EcoQuest / Game Management</div>
                    <h2>Game Library</h2>
                    <p>
                        Manage playable games, explore game configurations
                        and keep your learning experience organized.
                    </p>
                </div>
            </div>

            <a href="{{ route('admin.games.create') }}" class="btn-add">
                <i class="bi bi-plus-circle-fill"></i>
                Add New Game
            </a>

        </div>
    </div>


    {{-- 3. STATISTICS (three number cards) --}}
    <div class="row g-3 mb-4">

        <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-controller"></i></div>
                <div class="stat-value">{{ $games->total() }}</div>
                <div class="stat-label">Total Games in Library</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="bi bi-journal-check"></i></div>
                <div class="stat-value">{{ $games->getCollection()->sum('learning_tasks_count') }}</div>
                <div class="stat-label">Tasks Linked on This Page</div>
            </div>
        </div>

        <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-grid-3x3-gap"></i></div>
                <div class="stat-value">{{ $games->count() }}</div>
                <div class="stat-label">Games Displayed on This Page</div>
            </div>
        </div>

    </div>


    {{-- 4. GAME TABLE --}}
    <div class="panel">

        {{-- Panel title --}}
        <div class="panel-head">
            <div>
                <h4 class="panel-title">
                    <i class="bi bi-collection-fill me-2" style="color: var(--green);"></i>
                    All Games
                </h4>
                <p class="panel-subtitle">Browse and manage all your game-library entries.</p>
            </div>

            <div class="count-pill">{{ $games->total() }} Games Available</div>
        </div>

        <div class="table-responsive">
            <table class="table game-table">

                <thead>
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Game Information</th>
                        <th>Category</th>
                        <th>Difficulty</th>
                        <th>Game Type</th>
                        <th>Tasks</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- One row for every game --}}
                    @forelse ($games as $game)

                        {{-- Pick a badge color from the difficulty text (easy / medium / hard) --}}
                        @php
                            $level = strtolower($game->difficulty ?? '');

                            if ($level === 'easy') {
                                $difficultyClass = 'badge-easy';
                            } elseif ($level === 'medium') {
                                $difficultyClass = 'badge-medium';
                            } elseif ($level === 'hard') {
                                $difficultyClass = 'badge-hard';
                            } else {
                                $difficultyClass = 'badge-other';
                            }
                        @endphp

                        <tr>

                            {{-- Row number (continues across pages) --}}
                            <td class="ps-4 fw-bold text-secondary">
                                {{ $games->firstItem() + $loop->index }}
                            </td>

                            {{-- Game icon, name and short description --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="game-avatar">
                                        <i class="bi bi-controller"></i>
                                    </div>

                                    <div>
                                        <div class="game-name">{{ $game->name }}</div>
                                        <div class="game-description">
                                            {{ \Illuminate\Support\Str::limit($game->description ?? 'No description available', 65) }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td>
                                <span class="badge-pill badge-category">
                                    <i class="bi bi-grid"></i>
                                    {{ $game->category ?? 'Uncategorized' }}
                                </span>
                            </td>

                            {{-- Difficulty (color depends on the level) --}}
                            <td>
                                <span class="badge-pill {{ $difficultyClass }}">
                                    <i class="bi bi-speedometer2"></i>
                                    {{ $game->difficulty ?? 'Not set' }}
                                </span>
                            </td>

                            {{-- Game type --}}
                            <td>
                                @if ($game->game_type)
                                    <span class="badge-pill badge-type">
                                        <i class="bi bi-code-slash"></i>
                                        {{ $game->game_type }}
                                    </span>
                                @else
                                    <span class="text-secondary">Not set</span>
                                @endif
                            </td>

                            {{-- Number of linked learning tasks --}}
                            <td>
                                <span class="task-count">
                                    <i class="bi bi-journal-check"></i>
                                    {{ $game->learning_tasks_count }}
                                </span>
                            </td>

                            {{-- Buttons: view, edit, delete --}}
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('admin.games.show', $game->id) }}"
                                       class="btn btn-outline-primary action-btn"
                                       title="View Game">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a href="{{ route('admin.games.edit', $game->id) }}"
                                       class="btn btn-outline-warning action-btn"
                                       title="Edit Game">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>

                                    {{-- Delete asks "Are you sure?" before sending --}}
                                    <form action="{{ route('admin.games.destroy', $game->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this game?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-outline-danger action-btn"
                                                title="Delete Game">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    {{-- Shown when there are no games --}}
                    @empty

                        <tr>
                            <td colspan="7">
                                <div class="empty-state">

                                    <div class="empty-icon">
                                        <i class="bi bi-controller"></i>
                                    </div>

                                    <h4 class="fw-bold mb-2" style="color: #214632;">
                                        Your Game Library is Empty
                                    </h4>

                                    <p class="text-secondary mb-4">
                                        No games have been added yet. Start building
                                        your library by adding your first game.
                                    </p>

                                    <a href="{{ route('admin.games.create') }}"
                                       class="btn btn-success px-4 py-2 rounded-3 fw-bold">
                                        <i class="bi bi-plus-lg me-2"></i>
                                        Add Your First Game
                                    </a>

                                </div>
                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>


        {{-- 5. PAGINATION (only if there is more than one page) --}}
        @if ($games->hasPages())
            <div class="pagination-area">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="text-secondary" style="font-size: 14px; font-weight: 600;">
                        Showing
                        <strong>{{ $games->firstItem() }}</strong> to
                        <strong>{{ $games->lastItem() }}</strong> of
                        <strong>{{ $games->total() }}</strong> games
                    </div>

                    <div>{{ $games->links() }}</div>

                </div>
            </div>
        @endif

    </div>

</div>

@endsection