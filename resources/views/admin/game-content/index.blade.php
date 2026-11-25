@extends('admin.layouts.app')

@section('title', 'Game Content')

@section('page-heading', 'Game Content Management')

@section('content')

<style>
    .game-content-page {
        --navy: #20264b;
        --indigo: #5558c9;
        --purple: #8268d8;
        --emerald: #15966c;
        --text: #303b56;
        --muted: #8791a5;
        --border: #e9ebf3;
    }

    .game-content-page .hero-panel {
        position: relative;
        overflow: hidden;
        padding: 34px;
        border-radius: 23px;
        color: white;
        background: linear-gradient(115deg, #20264b 0%, #414b91 58%, #17856e 100%);
        box-shadow: 0 12px 30px rgba(32, 38, 75, .15);
    }

    .game-content-page .hero-panel::before {
        content: "";
        position: absolute;
        width: 280px;
        height: 280px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        right: -70px;
        top: -130px;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .game-content-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .game-content-page .hero-icon {
        width: 76px;
        height: 76px;
        border-radius: 21px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
        flex-shrink: 0;
    }

    .game-content-page .hero-label {
        color: #c6c5ff;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .game-content-page .hero-title {
        font-size: 31px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 8px;
    }

    .game-content-page .hero-description {
        color: rgba(255, 255, 255, .76);
        font-size: 16px;
        margin: 0;
    }

    .game-content-page .hero-count {
        padding: 13px 19px;
        border: 1px solid rgba(255, 255, 255, .23);
        border-radius: 13px;
        background: rgba(255, 255, 255, .12);
        text-align: center;
        min-width: 115px;
    }

    .game-content-page .hero-count strong {
        display: block;
        font-size: 27px;
        font-weight: 850;
    }

    .game-content-page .hero-count span {
        color: rgba(255, 255, 255, .76);
        font-size: 12px;
        font-weight: 700;
    }

    .game-content-page .stat-card {
        height: 100%;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 22px;
        box-shadow: 0 5px 20px rgba(31, 49, 75, .045);
        transition: .2s;
    }

    .game-content-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(31, 49, 75, .08);
    }

    .game-content-page .stat-icon {
        width: 47px;
        height: 47px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
        margin-bottom: 15px;
    }

    .game-content-page .stat-number {
        color: var(--navy);
        font-size: 30px;
        font-weight: 850;
        line-height: 1.2;
    }

    .game-content-page .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 6px;
    }

    .game-content-page .content-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 19px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(31, 49, 75, .045);
    }

    .game-content-page .panel-header {
        padding: 24px 27px;
        background: linear-gradient(110deg, #fbfbff, #f5f5fc);
        border-bottom: 1px solid var(--border);
    }

    .game-content-page .panel-title {
        font-size: 21px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 5px;
    }

    .game-content-page .panel-subtitle {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }

    .game-content-page .total-pill {
        padding: 9px 15px;
        background: #eeedff;
        color: #6256ba;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .game-content-page .table {
        margin: 0;
    }

    .game-content-page .table thead th {
        padding: 17px 18px;
        background: #f4f4fb;
        color: #5e6382;
        border-bottom: 1px solid #e8e8f1;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .7px;
        font-weight: 850;
        white-space: nowrap;
    }

    .game-content-page .table tbody td {
        padding: 19px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #eff0f5;
        color: #536078;
        font-size: 14px;
    }

    .game-content-page .table tbody tr {
        transition: background .2s;
    }

    .game-content-page .table tbody tr:hover {
        background: #fafaff;
    }

    .game-content-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .game-content-page .game-identity {
        display: flex;
        align-items: center;
        gap: 13px;
        min-width: 230px;
    }

    .game-content-page .game-symbol {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #eeedff;
        color: #6256ba;
        font-size: 22px;
        flex-shrink: 0;
    }

    .game-content-page .game-name {
        color: var(--navy);
        font-size: 15px;
        font-weight: 850;
        margin-bottom: 5px;
    }

    .game-content-page .game-description {
        color: #929bb0;
        font-size: 12px;
        line-height: 1.5;
    }

    .game-content-page .engine-key {
        display: inline-block;
        padding: 8px 10px;
        border-radius: 8px;
        background: #edf1ff;
        color: #4d61b3;
        border: 1px solid #e1e7ff;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .game-content-page .category-pill {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border: 1px solid #e8e5fa;
        background: #f4f1ff;
        color: #7060bd;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .game-content-page .difficulty-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 11px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .game-content-page .difficulty-easy,
    .game-content-page .difficulty-beginner {
        background: #e4f7eb;
        color: #168052;
    }

    .game-content-page .difficulty-medium,
    .game-content-page .difficulty-intermediate {
        background: #fff3dc;
        color: #a36c12;
    }

    .game-content-page .difficulty-hard,
    .game-content-page .difficulty-advanced {
        background: #ffe9e8;
        color: #bd4844;
    }

    .game-content-page .difficulty-default {
        background: #eff1f5;
        color: #6d778a;
    }

    .game-content-page .task-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-width: 42px;
        padding: 8px 12px;
        border-radius: 9px;
        background: #e6f5ef;
        color: #13825a;
        font-size: 13px;
        font-weight: 850;
    }

    .game-content-page .status-available {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        background: #e5f7ed;
        color: #14774c;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .game-content-page .status-empty {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 12px;
        background: #eff1f4;
        color: #778294;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 850;
        white-space: nowrap;
    }

    .game-content-page .view-btn {
        border: 1px solid #dedcf7;
        color: #675bc1;
        background: #f7f6ff;
        border-radius: 9px;
        padding: 9px 13px;
        font-size: 13px;
        font-weight: 800;
        transition: .2s;
    }

    .game-content-page .view-btn:hover {
        background: #6559c1;
        color: #fff;
        border-color: #6559c1;
    }

    .game-content-page .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .game-content-page .empty-icon {
        width: 76px;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        border-radius: 23px;
        background: #eeedff;
        color: #675bc1;
        font-size: 34px;
    }

    .game-content-page .empty-title {
        color: var(--navy);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .game-content-page .empty-description {
        color: var(--muted);
        font-size: 15px;
        margin: 0;
    }

    .game-content-page .panel-footer {
        padding: 21px 25px;
        background: #fff;
        border-top: 1px solid var(--border);
    }

    .game-content-page .pagination {
        gap: 5px;
        margin: 0;
    }

    .game-content-page .page-link {
        border: 1px solid #e2e5ef;
        border-radius: 9px !important;
        padding: 8px 13px;
        color: #6259ae;
        font-weight: 700;
        box-shadow: none;
    }

    .game-content-page .page-item.active .page-link {
        background: #6259ae;
        border-color: #6259ae;
        color: #fff;
    }

    .game-content-page .page-item.disabled .page-link {
        color: #aab0bd;
        background: #f8f9fb;
    }

    @media(max-width: 767px) {
        .game-content-page .hero-panel {
            padding: 23px 19px;
        }

        .game-content-page .hero-title {
            font-size: 25px;
        }

        .game-content-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .game-content-page .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .game-content-page .panel-header {
            padding: 21px 17px;
        }

        .game-content-page .panel-footer {
            padding: 18px;
        }
    }
</style>


<div class="game-content-page">

    {{-- HERO HEADER --}}
    <div class="hero-panel mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-collection-play"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-stars me-1"></i>
                            ECOQUEST / DIGITAL LEARNING LIBRARY
                        </div>

                        <h2 class="hero-title">
                            Game Content Management
                        </h2>

                        <p class="hero-description">
                            Organize and inspect the learning content connected to your games.
                        </p>
                    </div>

                </div>

                <div class="hero-count">
                    <strong>{{ $games->total() }}</strong>
                    <span>REGISTERED GAMES</span>
                </div>

            </div>

        </div>

    </div>


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#eeedff;color:#675bc1;">
                    <i class="bi bi-controller"></i>
                </div>

                <div class="stat-number">
                    {{ $games->total() }}
                </div>

                <div class="stat-label">
                    Total Registered Games
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e5f5ec;color:#16835a;">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="stat-number">
                    {{ $games->getCollection()->sum('learning_tasks_count') }}
                </div>

                <div class="stat-label">
                    Learning Tasks on This Page
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#eaf0ff;color:#527be8;">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <div class="stat-number">
                    {{ $games->getCollection()->where('learning_tasks_count', '>', 0)->count() }}
                </div>

                <div class="stat-label">
                    Games With Available Content on This Page
                </div>

            </div>

        </div>

    </div>


    {{-- GAME CONTENT TABLE --}}
    <div class="content-panel">

        <div class="panel-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="stat-icon mb-0"
                        style="background:#eeedff;color:#675bc1;width:45px;height:45px;">
                        <i class="bi bi-folder2-open"></i>
                    </div>

                    <div>
                        <h4 class="panel-title">
                            Game Content Library
                        </h4>

                        <p class="panel-subtitle">
                            Inspect engine, category, difficulty and connected tasks.
                        </p>
                    </div>

                </div>

                <span class="total-pill">
                    <i class="bi bi-controller me-1"></i>
                    {{ $games->total() }} Games
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th class="ps-4">Game</th>
                        <th>Engine</th>
                        <th>Category</th>
                        <th>Difficulty</th>
                        <th>Learning Tasks</th>
                        <th>Content Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>

                </thead>


                <tbody>

                    @forelse($games as $game)

                    @php
                    $difficulty = strtolower($game->difficulty ?? '');

                    $difficultyClass = match($difficulty) {
                    'easy', 'beginner' => 'difficulty-easy',
                    'medium', 'intermediate' => 'difficulty-medium',
                    'hard', 'advanced' => 'difficulty-hard',
                    default => 'difficulty-default',
                    };
                    @endphp

                    <tr>

                        {{-- GAME --}}
                        <td class="ps-4">

                            <div class="game-identity">

                                <div class="game-symbol">
                                    <i class="bi bi-controller"></i>
                                </div>

                                <div>
                                    <div class="game-name">
                                        {{ $game->name }}
                                    </div>

                                    <div class="game-description">
                                        {{ Str::limit($game->description, 55) }}
                                    </div>
                                </div>

                            </div>

                        </td>


                        {{-- ENGINE --}}
                        <td>

                            <code class="engine-key">
                                {{ $game->game_type ?? 'N/A' }}
                            </code>

                        </td>


                        {{-- CATEGORY --}}
                        <td>

                            <span class="category-pill">
                                <i class="bi bi-grid me-1"></i>
                                {{ ucwords(str_replace('_', ' ', $game->category ?? 'N/A')) }}
                            </span>

                        </td>


                        {{-- DIFFICULTY --}}
                        <td>

                            <span class="difficulty-pill {{ $difficultyClass }}">

                                <i class="bi bi-speedometer2"></i>

                                {{ ucfirst($game->difficulty ?? 'N/A') }}

                            </span>

                        </td>


                        {{-- TASKS --}}
                        <td>

                            <span class="task-count">
                                <i class="bi bi-journal-check"></i>
                                {{ $game->learning_tasks_count }}
                            </span>

                        </td>


                        {{-- CONTENT STATUS --}}
                        <td>

                            @if($game->learning_tasks_count > 0)

                            <span class="status-available">
                                <i class="bi bi-check-circle-fill"></i>
                                Available
                            </span>

                            @else

                            <span class="status-empty">
                                <i class="bi bi-dash-circle-fill"></i>
                                No Tasks
                            </span>

                            @endif

                        </td>


                        {{-- ACTION --}}
                        <td class="text-end pe-4">

                            <a href="{{ route('admin.game-content.show', $game->id) }}"
                                class="btn view-btn"
                                title="View game content">

                                <i class="bi bi-eye me-1"></i>

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    <i class="bi bi-collection-play"></i>
                                </div>

                                <h5 class="empty-title">
                                    No Games Found
                                </h5>

                                <p class="empty-description">
                                    Game content will appear here after games are added.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($games->hasPages())

        <div class="panel-footer">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="text-muted" style="font-size:14px;">
                    Showing
                    <strong>{{ $games->firstItem() }}</strong>
                    to
                    <strong>{{ $games->lastItem() }}</strong>
                    of
                    <strong>{{ $games->total() }}</strong>
                    games
                </div>

                {{ $games->links('pagination::bootstrap-5') }}

            </div>

        </div>

        @endif

    </div>

</div>

@endsection