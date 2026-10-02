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
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .admin-wrapper {
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: #111827;
            color: #fff;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1000;
            overflow-y: auto;
        }

        .brand {
            padding: 25px 22px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            background: #22c55e;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
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
            padding: 20px 18px 8px;
            color: #6b7280;
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
            padding: 11px 14px;
            color: #d1d5db;
            text-decoration: none;
            border-radius: 10px;
            transition: .2s ease;
        }

        .sidebar-link:hover {
            background: #1f2937;
            color: #fff;
        }

        .sidebar-link.active {
            background: #22c55e;
            color: #fff;
        }

        .sidebar-link i {
            font-size: 18px;
        }

        /* =========================
           MAIN CONTENT
        ========================= */

        .admin-main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .admin-navbar {
            height: 75px;
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .page-heading {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
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
            background: #dcfce7;
            color: #15803d;
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
            color: #6b7280;
        }

        .dashboard-content {
            padding: 30px;
        }

        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 991px) {

            .admin-sidebar {
                transform: translateX(-100%);
                transition: .3s ease;
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

            <a href="{{ route('admin.tasks.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.tasks.*') ? 'active' : '' }}">

                <i class="bi bi-journal-text"></i>

                <span>Learning Tasks</span>

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

            <a href="{{ route('admin.badges.index') }}"
                class="sidebar-link {{ request()->routeIs('admin.badges.*') ? 'active' : '' }}">

                <i class="bi bi-award-fill"></i>

                <span>Badges & Achievements</span>

            </a>

            <a href="#"
                class="sidebar-link">

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