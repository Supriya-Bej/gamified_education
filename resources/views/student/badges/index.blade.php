<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>My Badges | EcoQuest</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">


    <style>

        :root {
            --page-bg: #0f172a;
            --surface: #1e293b;
            --surface-alt: #27354d;
            --border: rgba(255,255,255,0.10);

            --primary: #38bdf8;
            --secondary: #22d3ee;

            --text-main: #f8fafc;
            --text-muted: #94a3b8;

            --success: #22c55e;
            --warning: #facc15;
        }


        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {

            margin: 0;

            min-height: 100vh;

            font-family:
                'Plus Jakarta Sans',
                system-ui,
                sans-serif;

            color: var(--text-main);

            background:
                radial-gradient(
                    circle at top right,
                    rgba(56,189,248,0.15),
                    transparent 35%
                ),
                var(--page-bg);

            overflow-x: hidden;
        }


        /* =====================================================
           SIDEBAR
        ====================================================== */

        .sidebar {

            position: fixed;

            top: 0;
            left: 0;

            width: 260px;

            height: 100vh;

            background:
                linear-gradient(
                    180deg,
                    #111827,
                    #0f172a
                );

            border-right:
                1px solid var(--border);

            padding: 24px 16px;

            display: flex;

            flex-direction: column;

            z-index: 1000;

            overflow-y: auto;
        }


        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            padding:
                4px 10px 25px;

            border-bottom:
                1px solid var(--border);

            margin-bottom: 22px;
        }


        .brand-icon {

            width: 44px;
            height: 44px;

            border-radius: 14px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 23px;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--secondary)
                );

            color: #082f49;

            box-shadow:
                0 8px 25px
                rgba(34,211,238,0.20);
        }


        .brand-text {

            line-height: 1.1;
        }


        .brand-title {

            font-size: 1.05rem;

            font-weight: 800;

            color: white;
        }


        .brand-subtitle {

            font-size: 0.68rem;

            color: var(--text-muted);

            margin-top: 4px;
        }


        .nav-category {

            color: #64748b;

            font-size: 0.68rem;

            text-transform: uppercase;

            letter-spacing: 1px;

            font-weight: 800;

            padding:
                0 12px 8px;
        }


        .side-link {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding:
                11px 13px;

            margin-bottom: 5px;

            border-radius: 10px;

            text-decoration: none;

            color: #cbd5e1;

            font-size: 0.88rem;

            font-weight: 600;

            transition:
                0.2s ease;
        }


        .side-link i {

            font-size: 1.05rem;

            width: 20px;

            text-align: center;
        }


        .side-link:hover {

            color: white;

            background:
                rgba(255,255,255,0.06);

            transform:
                translateX(2px);
        }


        .side-link.active {

            color: #07111f;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--secondary)
                );

            box-shadow:
                0 8px 20px
                rgba(34,211,238,0.15);
        }


        /* =====================================================
           IDENTITY BOX
        ====================================================== */

        .identity-box {

            margin-top: 18px;

            padding: 13px;

            border-radius: 12px;

            background:
                rgba(255,255,255,0.035);

            border:
                1px solid var(--border);
        }


        .identity-title {

            color: var(--secondary);

            font-size: 0.78rem;

            font-weight: 800;

            margin-bottom: 4px;
        }


        .identity-text {

            color: var(--text-muted);

            font-size: 0.72rem;

            line-height: 1.5;
        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main-content {

            margin-left: 260px;

            min-height: 100vh;

            padding: 0 30px 40px;
        }


        /* =====================================================
           TOPBAR
        ====================================================== */

        .topbar {

            min-height: 82px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            border-bottom:
                1px solid var(--border);

            margin-bottom: 35px;
        }


        .top-title {

            font-size: 1.25rem;

            font-weight: 800;

            color: white;
        }


        .top-subtitle {

            color: var(--text-muted);

            font-size: 0.78rem;

            margin-top: 4px;
        }


        .profile-btn {

            border: 1px solid var(--border);

            background:
                rgba(255,255,255,0.04);

            color: white;

            border-radius: 50px;

            padding: 6px 10px 6px 7px;

            display: flex;

            align-items: center;

            gap: 8px;
        }


        .profile-btn:hover {

            background:
                rgba(255,255,255,0.08);

            color: white;
        }


        .profile-avatar {

            width: 38px;
            height: 38px;

            border-radius: 50%;

            object-fit: cover;

            display: flex;

            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--secondary)
                );

            color: #082f49;

            font-weight: 800;
        }


        /* =====================================================
           PAGE HEADER
        ====================================================== */

        .badge-hero {

            position: relative;

            overflow: hidden;

            border-radius: 22px;

            padding: 35px;

            margin-bottom: 28px;

            background:
                linear-gradient(
                    135deg,
                    rgba(56,189,248,0.12),
                    rgba(34,211,238,0.05)
                );

            border:
                1px solid rgba(56,189,248,0.18);
        }


        .badge-hero::before {

            content: "🏆";

            position: absolute;

            right: 35px;
            top: 10px;

            font-size: 110px;

            opacity: 0.06;

            transform:
                rotate(12deg);
        }


        .hero-icon {

            width: 60px;
            height: 60px;

            border-radius: 18px;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 29px;

            background:
                rgba(250,204,21,0.12);

            border:
                1px solid rgba(250,204,21,0.25);

            margin-bottom: 18px;
        }


        .badge-hero h1 {

            font-size: clamp(
                1.6rem,
                4vw,
                2.2rem
            );

            font-weight: 800;

            margin-bottom: 8px;
        }


        .badge-hero p {

            margin: 0;

            color: var(--text-muted);

            max-width: 650px;

            font-size: 0.9rem;

            line-height: 1.7;
        }


        /* =====================================================
           BADGE CARDS
        ====================================================== */

        .badge-card {

            height: 100%;

            position: relative;

            overflow: hidden;

            border-radius: 20px;

            background:
                linear-gradient(
                    145deg,
                    rgba(30,41,59,0.98),
                    rgba(15,23,42,0.98)
                );

            border:
                1px solid var(--border);

            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;
        }


        .badge-card:hover {

            transform:
                translateY(-7px);

            border-color:
                rgba(250,204,21,0.30);

            box-shadow:
                0 18px 40px
                rgba(0,0,0,0.25);
        }


        .badge-card::before {

            content: "";

            position: absolute;

            width: 130px;
            height: 130px;

            right: -55px;
            top: -55px;

            border-radius: 50%;

            background:
                rgba(250,204,21,0.06);
        }


        .badge-body {

            padding: 28px 24px;

            text-align: center;

            position: relative;

            z-index: 1;
        }


        .badge-icon {

            width: 92px;
            height: 92px;

            margin:
                0 auto 18px;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 47px;

            background:
                radial-gradient(
                    circle,
                    rgba(250,204,21,0.16),
                    rgba(250,204,21,0.04)
                );

            border:
                1px solid
                rgba(250,204,21,0.25);

            box-shadow:
                0 0 35px
                rgba(250,204,21,0.07);
        }


        .badge-name {

            font-size: 1.05rem;

            font-weight: 800;

            color: white;

            margin-bottom: 9px;
        }


        .badge-description {

            color: var(--text-muted);

            font-size: 0.78rem;

            line-height: 1.6;

            min-height: 42px;

            margin-bottom: 18px;
        }


        .earned-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding:
                7px 11px;

            border-radius: 50px;

            background:
                rgba(34,197,94,0.10);

            border:
                1px solid
                rgba(34,197,94,0.18);

            color:
                #86efac;

            font-size: 0.7rem;

            font-weight: 700;
        }


        /* =====================================================
           EMPTY STATE
        ====================================================== */

        .empty-card {

            text-align: center;

            padding: 70px 25px;

            border-radius: 22px;

            background:
                rgba(255,255,255,0.025);

            border:
                1px dashed
                rgba(255,255,255,0.15);
        }


        .empty-icon {

            font-size: 65px;

            margin-bottom: 18px;

            opacity: 0.75;
        }


        .empty-card h3 {

            font-weight: 800;

            margin-bottom: 10px;
        }


        .empty-card p {

            color: var(--text-muted);

            font-size: 0.85rem;

            margin-bottom: 24px;
        }


        .back-btn {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding:
                10px 17px;

            border-radius: 10px;

            text-decoration: none;

            color: #07111f;

            background:
                linear-gradient(
                    135deg,
                    var(--primary),
                    var(--secondary)
                );

            font-size: 0.8rem;

            font-weight: 800;

            transition:
                0.2s ease;
        }


        .back-btn:hover {

            color: #07111f;

            transform:
                translateY(-2px);
        }


        /* =====================================================
           FOOTER
        ====================================================== */

        .footer {

            text-align: center;

            margin-top: 45px;

            padding-top: 22px;

            border-top:
                1px solid var(--border);

            color: var(--text-muted);

            font-size: 0.7rem;
        }


        .footer strong {

            color: var(--secondary);
        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media (max-width: 991.98px) {

            .sidebar {

                width: 230px;
            }

            .main-content {

                margin-left: 230px;

                padding:
                    0 20px 35px;
            }

        }


        @media (max-width: 767.98px) {

            .sidebar {

                position: relative;

                width: 100%;

                height: auto;

                min-height: auto;

                border-right: none;

                border-bottom:
                    1px solid var(--border);
            }


            .main-content {

                margin-left: 0;

                padding:
                    0 15px 30px;
            }


            .topbar {

                padding:
                    18px 0;

                margin-bottom: 25px;
            }


            .top-title {

                font-size: 1rem;
            }


            .top-subtitle {

                font-size: 0.7rem;
            }


            .badge-hero {

                padding: 25px 20px;
            }


            .badge-hero::before {

                right: 10px;

                font-size: 75px;
            }


            .brand {

                margin-bottom: 18px;
            }


            .side-link {

                padding: 10px 12px;
            }

        }


        @media (max-width: 480px) {

            .profile-name {

                display: none;
            }


            .badge-body {

                padding:
                    25px 18px;
            }


            .badge-icon {

                width: 82px;
                height: 82px;

                font-size: 40px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     SIDEBAR
========================================================== -->

<aside class="sidebar">


    <!-- BRAND -->

    <div class="brand">

        <div class="brand-icon">

            🌱

        </div>

        <div class="brand-text">

            <div class="brand-title">
                EcoQuest
            </div>

            <div class="brand-subtitle">
                Gamified Learning Realm
            </div>

        </div>

    </div>


    <!-- MAIN MENU -->

    <div class="nav-category">
        Main Menu
    </div>


    <a href="{{ route('student.dashboard') }}"
       class="side-link">

        <i class="bi bi-grid-1x2-fill"></i>

        <span>
            Dashboard
        </span>

    </a>


    <a href="{{ route('student.profile') }}"
       class="side-link">

        <i class="bi bi-person-circle"></i>

        <span>
            My Profile
        </span>

    </a>


    <a href="{{ route('student.badges') }}"
       class="side-link active">

        <i class="bi bi-award-fill"></i>

        <span>
            My Badges
        </span>

    </a>


    <a href="{{ route('student.dashboard') }}#quests"
       class="side-link">

        <i class="bi bi-trophy-fill"></i>

        <span>
            Learning Quests
        </span>

    </a>


    <a href="{{ route('student.dashboard') }}#progress"
       class="side-link">

        <i class="bi bi-graph-up-arrow"></i>

        <span>
            My Progress
        </span>

    </a>


    <a href="{{ route('student.dashboard') }}#topics"
       class="side-link">

        <i class="bi bi-lightbulb-fill"></i>

        <span>
            AI Topics
        </span>

    </a>


    <!-- IDENTITY -->

    <div class="nav-category mt-3">
        Identity
    </div>


    <div class="identity-box">

        <div class="identity-title">

            <i class="bi bi-stars me-1"></i>

            EcoQuest Student

        </div>

        <div class="identity-text">

            {{ $user->name }}

            <br>

            {{ $user->email }}

            @if($preference && !empty($preference->interests))

                <br>

                Interest:

                {{ is_array($preference->interests)
                    ? implode(', ', $preference->interests)
                    : $preference->interests }}

            @endif

        </div>

    </div>


    <!-- LOGOUT -->

    <div class="mt-4">

        <form action="{{ route('logout-user') }}"
              method="POST">

            @csrf

            <button type="submit"
                    class="side-link w-100 border-0"
                    style="
                        background:transparent;
                        color:#ef4444;
                        text-align:left;
                    ">

                <i class="bi bi-box-arrow-right"></i>

                <span>
                    Log Out
                </span>

            </button>

        </form>

    </div>


</aside>


<!-- =========================================================
     MAIN CONTENT
========================================================== -->

<main class="main-content">


    <!-- =====================================================
         TOPBAR
    ====================================================== -->

    <header class="topbar">


        <div>

            <div class="top-title">

                <i class="bi bi-award-fill me-2"
                   style="color:var(--warning);"></i>

                My Badges

            </div>


            <div class="top-subtitle">

                Your achievements earned throughout EcoQuest.

            </div>

        </div>


        <!-- PROFILE -->

        <div class="dropdown">


            <button class="profile-btn dropdown-toggle"
                    data-bs-toggle="dropdown"
                    type="button">


                @if($user->profile_picture)

                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                         alt="Profile"
                         class="profile-avatar">

                @else

                    <div class="profile-avatar">

                        {{ strtoupper(substr($user->name, 0, 1)) }}

                    </div>

                @endif


                <span class="profile-name small fw-bold">

                    {{ $user->name }}

                </span>


            </button>


            <ul class="dropdown-menu dropdown-menu-end shadow border-0">


                <li>

                    <div class="px-3 py-2">

                        <strong>

                            {{ $user->name }}

                        </strong>

                        <br>

                        <small class="text-muted">

                            {{ $user->email }}

                        </small>

                    </div>

                </li>


                <li>

                    <hr class="dropdown-divider">

                </li>


                <li>

                    <a href="{{ route('student.profile') }}"
                       class="dropdown-item">

                        <i class="bi bi-person me-2"></i>

                        My Profile

                    </a>

                </li>


                <li>

                    <a href="{{ route('student.badges') }}"
                       class="dropdown-item">

                        <i class="bi bi-award me-2"></i>

                        My Badges

                    </a>

                </li>


                <li>

                    <hr class="dropdown-divider">

                </li>


                <li>

                    <form action="{{ route('logout-user') }}"
                          method="POST">

                        @csrf

                        <button type="submit"
                                class="dropdown-item text-danger">

                            <i class="bi bi-box-arrow-right me-2"></i>

                            Log Out

                        </button>

                    </form>

                </li>


            </ul>

        </div>


    </header>


    <!-- =====================================================
         BADGE HERO
    ====================================================== -->

    <section class="badge-hero">


        <div class="hero-icon">

            🏆

        </div>


        <h1>

            Your Achievement Vault

        </h1>


        <p>

            Every badge represents a milestone in your
            EcoQuest journey. Complete quests, earn XP,
            and unlock new achievements as you progress.

        </p>


    </section>


    <!-- =====================================================
         BADGES
    ====================================================== -->

    @if($badges->count() > 0)


        <div class="row g-4">


            @foreach($badges as $studentBadge)


                @php

                    $badge = $studentBadge->badge;

                @endphp


                @if($badge)


                    <div class="col-sm-6 col-xl-4">


                        <div class="badge-card">


                            <div class="badge-body">


                                <!-- ICON -->

                                <div class="badge-icon">

                                    {{ $badge->icon ?? '🏆' }}

                                </div>


                                <!-- NAME -->

                                <div class="badge-name">

                                    {{ $badge->name }}

                                </div>


                                <!-- DESCRIPTION -->

                                <div class="badge-description">

                                    {{ $badge->description
                                        ?? 'Achievement unlocked in EcoQuest.' }}

                                </div>


                                <!-- EARNED -->

                                <div class="earned-badge">

                                    <i class="bi bi-check-circle-fill"></i>

                                    Earned

                                    @if($studentBadge->awarded_at)

                                        {{ $studentBadge->awarded_at->format('d M Y') }}

                                    @endif

                                </div>


                            </div>


                        </div>


                    </div>


                @endif


            @endforeach


        </div>


    @else


        <!-- =================================================
             EMPTY STATE
        ================================================== -->

        <div class="empty-card">


            <div class="empty-icon">

                🏆

            </div>


            <h3>

                No Badges Yet

            </h3>


            <p>

                Your achievement collection is waiting
                for its first badge.

                Complete your learning quests and
                start earning achievements!

            </p>


            <a href="{{ route('student.dashboard') }}"
               class="back-btn">

                <i class="bi bi-arrow-left"></i>

                Back to Dashboard

            </a>


        </div>


    @endif


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer class="footer">

        <div>

            🌱

            <strong>
                EcoQuest
            </strong>

            • Gamified Learning Realm

        </div>


        <div class="mt-1">

            Keep learning • Keep playing • Keep growing 🌍

        </div>

    </footer>


</main>


<!-- Bootstrap JS -->

<script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>


</body>

</html>