<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Admin Dashboard') | EcoQuest
    </title>

    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --admin-ink: #172b3a;
            --admin-muted: #718096;
            --admin-line: #e6edf1;
            --admin-canvas: #f3f7f8;
            --admin-primary: #167c65;
            --admin-primary-dark: #116550;
            --admin-sidebar: #102a24;
        }

        * {
            scrollbar-color: #b8c8c4 transparent;
        }

        body {
            background: var(--admin-canvas);
            color: var(--admin-ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: .94rem;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .admin-sidebar {
            width: 272px;
            min-height: 100vh;
            background: linear-gradient(180deg, #102a24 0%, #142f29 55%, #102721 100%);
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 12px 0 36px rgba(15, 42, 35, .1);
        }

        .brand {
            padding: 23px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(145deg, #45c59c, #168568);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            box-shadow: 0 8px 18px rgba(30, 176, 132, .2);
        }

        .brand-name {
            font-size: 20px;
            font-weight: 700;
        }

        .admin-label {
            font-size: 11px;
            color: #9ca3af;
            letter-spacing: 1px;
        }

        .sidebar-section {
            padding: 21px 18px 8px;
            color: #819a91;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 4px 12px;
            padding: 10px 14px;
            color: #c1d0ca;
            text-decoration: none;
            border-radius: 11px;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .08);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-link.active {
            background: linear-gradient(100deg, #168568, #209b78);
            color: #fff;
            box-shadow: 0 8px 18px rgba(12, 118, 89, .22);
        }

        .sidebar-link i {
            font-size: 18px;
        }

        /* =========================
           MAIN CONTENT
        ========================= */

        .admin-main {
            margin-left: 272px;
            min-height: 100vh;
        }

        .admin-navbar {
            min-height: 74px;
            background: rgba(255, 255, 255, .94);
            border-bottom: 1px solid var(--admin-line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px clamp(18px, 3vw, 36px);
            position: sticky;
            top: 0;
            z-index: 900;
            backdrop-filter: blur(14px);
        }

        .page-heading {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--admin-ink);
            letter-spacing: -.025em;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #e2f5ee;
            color: #14785d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .admin-name {
            font-size: 14px;
            font-weight: 600;
        }

        .admin-role {
            font-size: 12px;
            color: var(--admin-muted);
        }

        .dashboard-content {
            padding: clamp(18px, 3vw, 34px);
        }

        .dashboard-content > .container-fluid {
            padding-right: 0;
            padding-left: 0;
        }

        .dashboard-content .card {
            border-color: var(--admin-line) !important;
            border-radius: 16px;
            box-shadow: 0 8px 26px rgba(31, 57, 72, .055) !important;
        }

        .dashboard-content .card-header,
        .dashboard-content .card-footer {
            border-color: var(--admin-line) !important;
        }

        .dashboard-content .table {
            --bs-table-hover-bg: #f6faf8;
            color: #334b58;
        }

        .dashboard-content .table > :not(caption) > * > * {
            padding-top: .9rem;
            padding-bottom: .9rem;
            border-color: #edf1f3;
        }

        .dashboard-content .table thead th {
            background: #f7faf9;
            color: #617680;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .045em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .dashboard-content .form-control,
        .dashboard-content .form-select {
            min-height: 42px;
            border-color: #dce6e6;
            border-radius: 10px;
            color: var(--admin-ink);
        }

        .dashboard-content textarea.form-control {
            min-height: auto;
        }

        .dashboard-content .form-control:focus,
        .dashboard-content .form-select:focus {
            border-color: #70bea7;
            box-shadow: 0 0 0 .2rem rgba(22, 124, 101, .12);
        }

        .dashboard-content .btn {
            border-radius: 9px;
            font-weight: 600;
            transition: transform .18s ease, box-shadow .18s ease, background-color .18s ease;
        }

        .dashboard-content .btn:hover {
            transform: translateY(-1px);
        }

        .dashboard-content .btn-primary,
        .dashboard-content .btn-success {
            border-color: var(--admin-primary);
            background-color: var(--admin-primary);
        }

        .dashboard-content .btn-primary:hover,
        .dashboard-content .btn-success:hover {
            border-color: var(--admin-primary-dark);
            background-color: var(--admin-primary-dark);
            box-shadow: 0 6px 16px rgba(22, 124, 101, .18);
        }

        .dashboard-content .pagination {
            gap: 5px;
            margin-bottom: 0;
        }

        .dashboard-content .page-link {
            border: 1px solid var(--admin-line);
            border-radius: 8px !important;
            color: var(--admin-primary);
            font-size: .85rem;
        }

        .dashboard-content .page-item.active .page-link {
            border-color: var(--admin-primary);
            background: var(--admin-primary);
            color: #fff;
        }

        .dashboard-content .alert {
            border-radius: 12px;
        }

        .admin-profile {
            padding: 5px 8px 5px 12px;
            border: 1px solid #edf1f2;
            border-radius: 999px;
            background: #fff;
        }

        .admin-avatar {
            flex-shrink: 0;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 991px) {

            .admin-sidebar {
                transform: translateX(-100%);
                transition: .3s ease;
                box-shadow: 18px 0 40px rgba(15, 42, 35, .2);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .mobile-menu {
                display: block !important;
            }

        }

        @media (max-width: 575px) {
            .admin-navbar {
                min-height: 64px;
                padding: 10px 16px;
            }

            .page-heading {
                font-size: 1rem;
            }

            .dashboard-content {
                padding: 16px 12px 24px;
            }

            .admin-profile {
                gap: 7px;
                padding-left: 7px;
            }
        }

        .mobile-menu {
            display: none;
            border: none;
            background: transparent;
            font-size: 24px;
        }
    </style>

    @stack('styles')

</head>

<body>

    <div class="admin-wrapper">

        <!-- =========================
         SIDEBAR
    ========================== -->

        <aside class="admin-sidebar" id="adminSidebar">

            <div class="brand">

                <div class="d-flex align-items-center gap-3">

                    <div class="brand-icon">
                        <i class="bi bi-leaf-fill"></i>
                    </div>

                    <div>
                        <div class="brand-name">
                            EcoQuest
                        </div>

                        <div class="admin-label">
                            ADMIN PANEL
                        </div>
                    </div>

                </div>

            </div>


            <!-- MAIN -->

            <div class="sidebar-section">
                Main
            </div>

            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

                <i class="bi bi-grid-1x2-fill"></i>

                <span>Dashboard</span>

            </a>


            <!-- STUDENTS -->

            <div class="sidebar-section">
                Student Management
            </div>

            <a href="{{ route('admin.students.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">

                <i class="bi bi-people-fill"></i>

                <span>Students</span>

            </a>

            <a href="#"
                class="sidebar-link">

                <i class="bi bi-bar-chart-fill"></i>

                <span>Student Progress</span>

            </a>


            <!-- GAMES -->

            <div class="sidebar-section">
                Game Management
            </div>

            <a href="{{ route('admin.games.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">

                <i class="bi bi-controller"></i>

                <span>Game Library</span>

            </a>

            <a href="{{ route('admin.engines.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.engines.*') ? 'active' : '' }}">

                <i class="bi bi-cpu"></i>

                <span>Game Engines</span>

            </a>

            <a href="{{ route('admin.game-content.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.game-content.*') ? 'active' : '' }}">

                <i class="bi bi-collection-play"></i>

                <span>Game Content</span>

            </a>


            <!-- LEARNING -->

            <div class="sidebar-section">
                Learning
            </div>

            <!-- <a href="{{ route('admin.tasks.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">

                <i class="bi bi-journal-text"></i>

                <span>Game Quest Review</span>

            </a> -->

            <a href="{{ route('admin.project-challenges.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.project-challenges.*') ? 'active' : '' }}">

                <i class="bi bi-kanban"></i>

                <span>Project Challenges</span>

            </a>

            <a href="{{ route('admin.learning-materials.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.learning-materials.*') ? 'active' : '' }}">

                <i class="bi bi-journal-text"></i>
                <span>Learning Materials</span>

            </a>

            <a href="{{ route('admin.topics.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.topics.*') ? 'active' : '' }}">

                <i class="bi bi-bookmarks-fill"></i>
                <span>Topics</span>

            </a>

            <a href="#"
                class="sidebar-link">

                <i class="bi bi-robot"></i>

                <span>AI Tasks</span>

            </a>


            <!-- REWARDS -->

            <div class="sidebar-section">
                Rewards
            </div>

            <a href="{{ route('admin.rewards.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.rewards.*') ? 'active' : '' }}">
                <i class="bi bi-stars"></i>
                <span>XP & Rewards</span>
            </a>

            <a href="{{ route('admin.certificates.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.certificates.*') ? 'active' : '' }}">
                <i class="bi bi-award"></i>
                <span>Certificates</span>
            </a>

            <a href="{{ route('admin.badges.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}">

                <i class="bi bi-award-fill"></i>

                <span>Badges & Achievements</span>

            </a>

            <a href="{{ route('admin.leaderboard.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.leaderboard.*') ? 'active' : '' }}">

                <i class="bi bi-trophy-fill"></i>

                <span>Leaderboard</span>

            </a>


            <!-- AI -->

            <div class="sidebar-section">
                AI Monitoring
            </div>

            <a href="#"
                class="sidebar-link">

                <i class="bi bi-activity"></i>

                <span>AI Activity</span>

            </a>

            <a href="#"
                class="sidebar-link">

                <i class="bi bi-flag-fill"></i>

                <span>Flagged Activities</span>

            </a>


            <!-- REPORTS -->

            <div class="sidebar-section">
                Reports
            </div>

            <a href="#"
                class="sidebar-link">

                <i class="bi bi-graph-up-arrow"></i>

                <span>Analytics</span>

            </a>


            <!-- SYSTEM -->

            <div class="sidebar-section">
                System
            </div>

            <a href="#"
                class="sidebar-link">

                <i class="bi bi-gear-fill"></i>

                <span>Settings</span>

            </a>

            <form action="{{ route('admin.logout') }}"
                method="POST"
                class="px-2 mt-2">

                @csrf

                <button type="submit"
                    class="sidebar-link w-100 border-0 bg-transparent text-start">

                    <i class="bi bi-box-arrow-right"></i>

                    <span>Logout</span>

                </button>

            </form>

        </aside>


        <!-- =========================
         MAIN
    ========================== -->

        <main class="admin-main">

            <nav class="admin-navbar">

                <div class="d-flex align-items-center gap-3">

                    <button class="mobile-menu"
                        onclick="toggleSidebar()">

                        <i class="bi bi-list"></i>

                    </button>

                    <div class="page-heading">
                        @yield('page-heading', 'Dashboard')
                    </div>

                </div>


                <!-- ADMIN PROFILE -->

                @php
                $admin = Auth::guard('admin')->user();
                @endphp

                <div class="admin-profile">

                    <div class="text-end d-none d-sm-block">

                        <div class="admin-name">
                            {{ $admin->name ?? 'Admin' }}
                        </div>

                        <div class="admin-role">
                            Administrator
                        </div>

                    </div>

                    <div class="admin-avatar">

                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}

                    </div>

                </div>

            </nav>


            <section class="dashboard-content">

                @if(session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

                @endif

                @if(session('error'))

                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>

                @endif

                @yield('content')

            </section>

        </main>

    </div>


    <script>
        function toggleSidebar() {
            document
                .getElementById('adminSidebar')
                .classList.toggle('show');
        }
    </script>

    @stack('scripts')

</body>

</html>