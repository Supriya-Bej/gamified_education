@extends('admin.layouts.app')

@section('title', 'Game Engines')

@section('page-heading', 'Game Engine Management')

@section('content')

<style>
    .engine-page {
        --navy: #17233e;
        --navy-light: #263c5d;
        --emerald: #14966c;
        --blue: #527be8;
        --purple: #8068d9;
        --text: #26364d;
        --muted: #8290a2;
        --border: #e8edf3;
    }

    .engine-page .hero-panel {
        position: relative;
        overflow: hidden;
        padding: 33px;
        border-radius: 23px;
        color: white;
        background: linear-gradient(115deg, #17233e 0%, #263d61 58%, #147b65 100%);
        box-shadow: 0 12px 30px rgba(23, 35, 62, .14);
    }

    .engine-page .hero-panel::before {
        content: "";
        position: absolute;
        width: 270px;
        height: 270px;
        right: -70px;
        top: -130px;
        border: 1px solid rgba(255, 255, 255, .13);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .035),
            0 0 0 70px rgba(255, 255, 255, .025);
    }

    .engine-page .hero-content {
        position: relative;
        z-index: 1;
    }

    .engine-page .hero-icon {
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

    .engine-page .hero-label {
        color: #a7e9d1;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
    }

    .engine-page .hero-title {
        font-size: 31px;
        font-weight: 850;
        letter-spacing: -.7px;
        margin-bottom: 8px;
    }

    .engine-page .hero-description {
        color: rgba(255, 255, 255, .76);
        font-size: 16px;
        margin: 0;
    }

    .engine-page .hero-count {
        padding: 13px 18px;
        border: 1px solid rgba(255, 255, 255, .23);
        border-radius: 13px;
        background: rgba(255, 255, 255, .12);
        text-align: center;
        min-width: 115px;
    }

    .engine-page .hero-count strong {
        display: block;
        font-size: 26px;
        font-weight: 850;
    }

    .engine-page .hero-count span {
        color: rgba(255, 255, 255, .76);
        font-size: 12px;
        font-weight: 700;
    }

    .engine-page .stat-card {
        height: 100%;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 17px;
        padding: 22px;
        box-shadow: 0 5px 20px rgba(31, 49, 75, .045);
        transition: .2s;
    }

    .engine-page .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(31, 49, 75, .08);
    }

    .engine-page .stat-icon {
        width: 47px;
        height: 47px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
        margin-bottom: 15px;
    }

    .engine-page .stat-number {
        color: var(--navy);
        font-size: 30px;
        font-weight: 850;
        line-height: 1.2;
    }

    .engine-page .stat-label {
        color: var(--muted);
        font-size: 14px;
        font-weight: 650;
        margin-top: 6px;
    }

    .engine-page .content-panel {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 19px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(31, 49, 75, .045);
    }

    .engine-page .panel-header {
        padding: 24px 27px;
        background: linear-gradient(110deg, #fbfcfe, #f5f9f7);
        border-bottom: 1px solid var(--border);
    }

    .engine-page .panel-title {
        font-size: 21px;
        font-weight: 850;
        color: var(--navy);
        margin-bottom: 5px;
    }

    .engine-page .panel-subtitle {
        font-size: 14px;
        color: var(--muted);
        margin: 0;
    }

    .engine-page .total-pill {
        padding: 9px 15px;
        background: #e6f5ed;
        color: #14734f;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .engine-page .table {
        margin: 0;
    }

    .engine-page .table thead th {
        padding: 17px 18px;
        background: #f1f6f3;
        color: #4c6658;
        border-bottom: 1px solid #e4ece6;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .7px;
        font-weight: 850;
        white-space: nowrap;
    }

    .engine-page .table tbody td {
        padding: 19px 18px;
        vertical-align: middle;
        border-bottom: 1px solid #edf1f4;
        color: #536276;
        font-size: 14px;
    }

    .engine-page .table tbody tr {
        transition: background .2s;
    }

    .engine-page .table tbody tr:hover {
        background: #f8fcf9;
    }

    .engine-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .engine-page .engine-identity {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 190px;
    }

    .engine-page .engine-symbol {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: #e9f1ff;
        color: #527be8;
        font-size: 21px;
        flex-shrink: 0;
    }

    .engine-page .engine-name {
        color: var(--navy);
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .engine-page .engine-caption {
        color: #99a4b1;
        font-size: 12px;
    }

    .engine-page .engine-key {
        display: inline-block;
        padding: 8px 11px;
        border-radius: 8px;
        background: #f0efff;
        color: #6955bd;
        border: 1px solid #e5e1ff;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap;
    }

    .engine-page .metric-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-width: 45px;
        padding: 8px 12px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 850;
    }

    .engine-page .games-pill {
        color: #426bd0;
        background: #eaf0ff;
    }

    .engine-page .tasks-pill {
        color: #13835b;
        background: #e5f5ec;
    }

    .engine-page .active-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #e5f7ed;
        color: #14774c;
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 850;
    }

    .engine-page .active-dot {
        width: 7px;
        height: 7px;
        background: #1ba66b;
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(27, 166, 107, .12);
    }

    .engine-page .view-btn {
        border: 1px solid #dce7f6;
        color: #456fc9;
        background: #f5f8ff;
        border-radius: 9px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 800;
        transition: .2s;
        white-space: nowrap;
    }

    .engine-page .view-btn:hover {
        background: #456fc9;
        color: #fff;
        border-color: #456fc9;
    }

    .engine-page .empty-state {
        padding: 65px 20px;
        text-align: center;
    }

    .engine-page .empty-icon {
        width: 76px;
        height: 76px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
        border-radius: 23px;
        background: #eaf4ef;
        color: #17825b;
        font-size: 34px;
    }

    .engine-page .empty-title {
        color: var(--navy);
        font-size: 20px;
        font-weight: 850;
        margin-bottom: 8px;
    }

    .engine-page .empty-description {
        color: var(--muted);
        font-size: 15px;
        margin: 0;
    }

    .engine-page .panel-footer {
        padding: 21px 25px;
        background: #fff;
        border-top: 1px solid var(--border);
    }

    .engine-page .pagination {
        gap: 5px;
        margin: 0;
    }

    .engine-page .page-link {
        border: 1px solid #e2e9ef;
        border-radius: 9px !important;
        padding: 8px 13px;
        color: #426b54;
        font-weight: 700;
        box-shadow: none;
    }

    .engine-page .page-item.active .page-link {
        background: #147b56;
        border-color: #147b56;
        color: #fff;
    }

    .engine-page .page-item.disabled .page-link {
        color: #aab5ae;
        background: #f8faf9;
    }

    @media(max-width: 767px) {
        .engine-page .hero-panel {
            padding: 23px 19px;
        }

        .engine-page .hero-title {
            font-size: 25px;
        }

        .engine-page .hero-top {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 20px;
        }

        .engine-page .hero-icon {
            width: 60px;
            height: 60px;
            font-size: 28px;
        }

        .engine-page .panel-header {
            padding: 21px 17px;
        }

        .engine-page .panel-footer {
            padding: 18px;
        }
    }
</style>


<div class="engine-page">

    {{-- HERO HEADER --}}
    <div class="hero-panel mb-4">

        <div class="hero-content">

            <div class="d-flex justify-content-between align-items-center hero-top">

                <div class="d-flex align-items-center gap-3">

                    <div class="hero-icon">
                        <i class="bi bi-cpu"></i>
                    </div>

                    <div>
                        <div class="hero-label">
                            <i class="bi bi-lightning-charge-fill me-1"></i>
                            ECOQUEST / GAME INFRASTRUCTURE
                        </div>

                        <h2 class="hero-title">
                            Game Engine Management
                        </h2>

                        <p class="hero-description">
                            Monitor reusable engines powering your interactive learning games.
                        </p>
                    </div>

                </div>

                <div class="hero-count">
                    <strong>{{ $engines->total() }}</strong>
                    <span>REGISTERED ENGINES</span>
                </div>

            </div>

        </div>

    </div>


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e9efff;color:#527be8;">
                    <i class="bi bi-cpu"></i>
                </div>

                <div class="stat-number">
                    {{ $engines->total() }}
                </div>

                <div class="stat-label">
                    Total Game Engines
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#e5f5ec;color:#16835a;">
                    <i class="bi bi-controller"></i>
                </div>

                <div class="stat-number">
                    {{ $engines->getCollection()->sum('game_count') }}
                </div>

                <div class="stat-label">
                    Games Using Engines on This Page
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-icon"
                    style="background:#f0ebff;color:#8068d9;">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div class="stat-number">
                    {{ $engines->getCollection()->sum('task_count') }}
                </div>

                <div class="stat-label">
                    Learning Tasks on This Page
                </div>

            </div>

        </div>

    </div>


    {{-- ENGINE TABLE --}}
    <div class="content-panel">

        <div class="panel-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="d-flex align-items-center gap-3">

                    <div class="stat-icon mb-0"
                        style="background:#e5f5ec;color:#16835a;width:45px;height:45px;">
                        <i class="bi bi-diagram-3"></i>
                    </div>

                    <div>
                        <h4 class="panel-title">
                            Registered Game Engines
                        </h4>

                        <p class="panel-subtitle">
                            View engine details and their connected learning activities.
                        </p>
                    </div>

                </div>

                <span class="total-pill">
                    <i class="bi bi-layers me-1"></i>
                    {{ $engines->total() }} Engines
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th class="ps-4">#</th>

                        <th>Engine</th>

                        <th>Engine Key</th>

                        <th>Games</th>

                        <th>Learning Tasks</th>

                        <th>Status</th>

                        <th class="text-end pe-4">Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($engines as $engine)

                    @php
                    $engineName = match(strtolower($engine->game_type)) {
                    'arithmetic_speed' => 'Arithmetic Speed',
                    'algorithm_race' => 'Algorithm Race',
                    'sorting_challenge' => 'Sorting Challenge',
                    'creative_coding' => 'Creative Coding',
                    'physics_quiz' => 'Physics Quiz',
                    default => ucwords(str_replace('_', ' ', $engine->game_type)),
                    };

                    $engineIcon = match(strtolower($engine->game_type)) {
                    'arithmetic_speed' => 'bi-calculator',
                    'algorithm_race' => 'bi-code-slash',
                    'sorting_challenge' => 'bi-sort-down',
                    'creative_coding' => 'bi-braces',
                    'physics_quiz' => 'bi-atom',
                    default => 'bi-controller',
                    };
                    @endphp

                    <tr>

                        {{-- NUMBER --}}
                        <td class="ps-4">
                            <span class="fw-bold text-secondary">
                                {{ $engines->firstItem() + $loop->index }}
                            </span>
                        </td>


                        {{-- ENGINE IDENTITY --}}
                        <td>

                            <div class="engine-identity">

                                <div class="engine-symbol">
                                    <i class="bi {{ $engineIcon }}"></i>
                                </div>

                                <div>
                                    <div class="engine-name">
                                        {{ $engineName }}
                                    </div>

                                    <div class="engine-caption">
                                        Reusable game engine
                                    </div>
                                </div>

                            </div>

                        </td>


                        {{-- ENGINE KEY --}}
                        <td>
                            <code class="engine-key">
                                {{ $engine->game_type }}
                            </code>
                        </td>


                        {{-- GAMES --}}
                        <td>
                            <span class="metric-pill games-pill">
                                <i class="bi bi-controller"></i>
                                {{ $engine->game_count }}
                            </span>
                        </td>


                        {{-- TASKS --}}
                        <td>
                            <span class="metric-pill tasks-pill">
                                <i class="bi bi-journal-check"></i>
                                {{ $engine->task_count }}
                            </span>
                        </td>


                        {{-- STATUS --}}
                        <td>
                            <span class="active-pill">
                                <span class="active-dot"></span>
                                Active
                            </span>
                        </td>


                        {{-- ACTION --}}
                        <td class="text-end pe-4">

                            <a href="{{ route('admin.engines.show', $engine->game_type) }}"
                                class="btn view-btn">

                                <i class="bi bi-eye me-1"></i>
                                

                            </a>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="7">

                            <div class="empty-state">

                                <div class="empty-icon">
                                    <i class="bi bi-cpu"></i>
                                </div>

                                <h5 class="empty-title">
                                    No Game Engines Found
                                </h5>

                                <p class="empty-description">
                                    Add a game with a valid game type to see its engine here.
                                </p>

                            </div>

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($engines->hasPages())

        <div class="panel-footer">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div class="text-muted" style="font-size:14px;">
                    Showing
                    <strong>{{ $engines->firstItem() }}</strong>
                    to
                    <strong>{{ $engines->lastItem() }}</strong>
                    of
                    <strong>{{ $engines->total() }}</strong>
                    engines
                </div>

                {{ $engines->links('pagination::bootstrap-5') }}

            </div>

        </div>

        @endif

    </div>

</div>

@endsection