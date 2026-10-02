<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        {{ $aiProfile['dashboard_title'] ?? 'Student Dashboard' }}
        | EcoQuest
    </title>


    {{-- =====================================================
         BOOTSTRAP
    ====================================================== --}}

    <link rel="stylesheet"
          href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">


    {{-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== --}}

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    {{-- =====================================================
         GOOGLE FONTS
    ====================================================== --}}

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap"
          rel="stylesheet">


    {{-- =====================================================
         THEME DATA
    ====================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | SAFE AI PROFILE
        |--------------------------------------------------------------------------
        */

        $aiProfile = is_array($aiProfile ?? null)
            ? $aiProfile
            : [];


        /*
        |--------------------------------------------------------------------------
        | SAFE THEME CONFIG
        |--------------------------------------------------------------------------
        */

        $themeConfig = is_array($themeConfig ?? null)
            ? $themeConfig
            : [];


        /*
        |--------------------------------------------------------------------------
        | THEME KEY
        |--------------------------------------------------------------------------
        */

        $theme = strtolower(
            trim($themeKey ?? 'general')
        );


        /*
        |--------------------------------------------------------------------------
        | CHESS CHECK
        |--------------------------------------------------------------------------
        */

        $isChess = ($theme === 'chess');


        /*
        |--------------------------------------------------------------------------
        | THEME COLORS
        |
        | IMPORTANT:
        |
        | First use learning_themes.php
        | Then AI profile
        | Then fallback.
        |--------------------------------------------------------------------------
        */


        $primaryColor =
            $themeConfig['primary']
            ?? $aiProfile['primary_color']
            ?? ($isChess ? '#08080A' : '#0F172A');


        $secondaryColor =
            $themeConfig['secondary']
            ?? $aiProfile['secondary_color']
            ?? ($isChess ? '#E5E7EB' : '#38BDF8');


        $accentColor =
            $themeConfig['accent']
            ?? $aiProfile['accent_color']
            ?? ($isChess ? '#94A3B8' : '#22D3EE');


        $backgroundColor =
            $themeConfig['background']
            ?? $aiProfile['background_color']
            ?? ($isChess ? '#08080A' : '#0F172A');


        $surfaceColor =
            $themeConfig['surface']
            ?? $aiProfile['surface_color']
            ?? ($isChess ? '#121216' : '#1E293B');


        $surfaceAlt =
            $themeConfig['surface_alt']
            ?? $aiProfile['surface_alt_color']
            ?? ($isChess ? '#1A1A22' : '#27354D');


        $borderColor =
            $themeConfig['border']
            ?? $aiProfile['border_color']
            ?? (
                $isChess
                    ? '#2E2E3E'
                    : 'rgba(255,255,255,0.10)'
            );


        $textColor =
            $themeConfig['text']
            ?? $aiProfile['text_color']
            ?? '#F8FAFC';


        $mutedColor =
            $themeConfig['muted']
            ?? $aiProfile['muted_color']
            ?? '#94A3B8';


        $glowColor =
            $themeConfig['glow']
            ?? $aiProfile['glow_color']
            ?? 'rgba(56,189,248,0.25)';


        /*
        |--------------------------------------------------------------------------
        | ICON
        |--------------------------------------------------------------------------
        */

        $themeIcon =
            $themeConfig['icon']
            ?? $aiProfile['theme_icon']
            ?? ($isChess ? '♞' : '✦');


        /*
        |--------------------------------------------------------------------------
        | FONT
        |--------------------------------------------------------------------------
        */

        $fontDisplay =
            $themeConfig['font_display']
            ?? $aiProfile['font_display']
            ?? ($isChess ? 'Cinzel' : 'Plus Jakarta Sans');


        /*
        |--------------------------------------------------------------------------
        | AI CONTENT
        |--------------------------------------------------------------------------
        */

        $rankTitle =
            $aiProfile['rank_title']
            ?? $themeConfig['gamified_rank']
            ?? 'Explorer';


        $dashboardTitle =
            $aiProfile['dashboard_title']
            ?? $themeConfig['title']
            ?? 'Learning Arena';


        $tagline =
            $aiProfile['tagline']
            ?? $themeConfig['subtitle']
            ?? 'Your personalized learning world';


        $motto =
            $aiProfile['motto']
            ?? $themeConfig['gamified_motto']
            ?? 'Every step forward makes you stronger.';


        $welcomeMessage =
            $aiProfile['welcome_message']
            ?? 'Welcome to your personalized learning world!';


        $dailyQuest =
            $aiProfile['daily_quest']
            ?? "Complete today's learning challenge";


        $recommendedTopics =
            $aiProfile['recommended_topics']
            ?? [];


        $learningStyle =
            $aiProfile['learning_style']
            ?? 'Interactive';


        $difficulty =
            $aiProfile['difficulty']
            ?? ($preference->experience_level ?? 'beginner');


        /*
        |--------------------------------------------------------------------------
        | XP
        |--------------------------------------------------------------------------
        */

        $userXp =
            $progress->total_xp ?? 0;


        $userLevel =
            $progress->level ?? 1;


        $completedTasks =
            $progress->completed_tasks ?? 0;


        /*
        |--------------------------------------------------------------------------
        | LABELS
        |--------------------------------------------------------------------------
        */

        $labels =
            $themeConfig['gamified_labels']
            ?? [

                'level_prefix' => 'Level',

                'quest_title' => 'Daily Mission',

                'mission_title' => 'Learning Quests',

                'challenge_title' => 'Challenges',

                'exp_label' => 'Total XP',

            ];

    @endphp



    {{-- =====================================================
         THEME CSS
    ====================================================== --}}

    <style>

        :root {

            /* IMPORTANT:
               These MUST be normal Blade expressions.
            */

            --primary: {{ $primaryColor }};

            --secondary: {{ $secondaryColor }};

            --accent: {{ $accentColor }};

            --page-bg: {{ $backgroundColor }};

            --surface: {{ $surfaceColor }};

            --surface-alt: {{ $surfaceAlt }};

            --border: {{ $borderColor }};

            --text-main: {{ $textColor }};

            --text-muted: {{ $mutedColor }};

            --glow: {{ $glowColor }};


            --font-display:
                '{{ $fontDisplay }}',
                serif,
                sans-serif;


            --font-body:
                'Plus Jakarta Sans',
                system-ui,
                sans-serif;

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

            font-family: var(--font-body);

            color: var(--text-main);

            background:

                radial-gradient(
                    circle at top right,
                    var(--glow),
                    transparent 35%
                ),

                var(--page-bg);

            overflow-x: hidden;

        }


        a {
            text-decoration: none;
        }


        /* =====================================================
           CHESS BACKGROUND
        ====================================================== */

        .chess-grid-bg {

            position: fixed;

            inset: 0;

            pointer-events: none;

            z-index: 0;

            opacity: .045;

            background-image:

                linear-gradient(
                    45deg,
                    var(--secondary) 25%,
                    transparent 25%
                ),

                linear-gradient(
                    -45deg,
                    var(--secondary) 25%,
                    transparent 25%
                ),

                linear-gradient(
                    45deg,
                    transparent 75%,
                    var(--secondary) 75%
                ),

                linear-gradient(
                    -45deg,
                    transparent 75%,
                    var(--secondary) 75%
                );

            background-size: 60px 60px;

            background-position:
                0 0,
                0 30px,
                30px -30px,
                -30px 0;

        }


        /* =====================================================
           FLOATING CHESS PIECES
        ====================================================== */

        .floating-piece {

            position: fixed;

            pointer-events: none;

            z-index: 0;

            color: var(--secondary);

            opacity: .05;

            user-select: none;

        }


        .piece-1 {

            top: 8%;

            right: 5%;

            font-size: 8rem;

            transform: rotate(12deg);

        }


        .piece-2 {

            bottom: 10%;

            left: 20%;

            font-size: 7rem;

            transform: rotate(-12deg);

        }


        .piece-3 {

            top: 48%;

            right: 20%;

            font-size: 5rem;

        }


        .piece-4 {

            bottom: 25%;

            right: 5%;

            font-size: 8rem;

        }


        /* =====================================================
           SIDEBAR
        ====================================================== */

        .sidebar {

            position: fixed;

            top: 0;

            left: 0;

            bottom: 0;

            width: 260px;

            padding: 24px 18px;

            background:

                linear-gradient(
                    180deg,
                    var(--surface),
                    var(--page-bg)
                );

            border-right:
                1px solid var(--border);

            z-index: 1000;

            display: flex;

            flex-direction: column;

            overflow-y: auto;

            backdrop-filter: blur(18px);

        }


        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 5px 8px 22px;

            margin-bottom: 18px;

            color: var(--text-main);

            border-bottom:
                1px solid var(--border);

        }


        .brand-icon {

            width: 44px;

            height: 44px;

            border-radius: 13px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.4rem;

            color: var(--page-bg);

            background:

                linear-gradient(
                    135deg,
                    var(--secondary),
                    var(--accent)
                );

            box-shadow:
                0 8px 25px var(--glow);

        }


        .brand-title {

            font-weight: 900;

            font-size: 1.1rem;

        }


        .brand-title span {

            color: var(--secondary);

        }


        .brand-subtitle {

            font-size: .68rem;

            color: var(--text-muted);

            margin-top: 2px;

        }


        .nav-category {

            font-size: .68rem;

            text-transform: uppercase;

            letter-spacing: 1.3px;

            font-weight: 800;

            color: var(--text-muted);

            padding: 9px 12px;

        }


        .side-link {

            display: flex;

            align-items: center;

            gap: 12px;

            width: 100%;

            padding: 11px 13px;

            margin-bottom: 5px;

            border-radius: 10px;

            border: 0;

            background: transparent;

            color: var(--text-muted);

            font-size: .87rem;

            font-weight: 600;

            text-decoration: none;

            transition: .2s ease;

        }


        .side-link:hover {

            color: var(--secondary);

            background: var(--surface-alt);

            transform: translateX(2px);

        }


        .side-link.active {

            color: var(--page-bg);

            background:

                linear-gradient(
                    135deg,
                    var(--secondary),
                    var(--accent)
                );

            box-shadow:
                0 8px 20px var(--glow);

        }


        .side-link i {

            width: 20px;

            text-align: center;

            font-size: 1rem;

        }


        .identity-box {

            margin-top: 15px;

            padding: 13px;

            border-radius: 12px;

            background: var(--surface-alt);

            border:
                1px solid var(--border);

        }


        .identity-top {

            display: flex;

            align-items: center;

            gap: 8px;

        }


        .identity-icon {

            color: var(--secondary);

            font-size: 1.1rem;

        }


        .identity-rank {

            color: var(--secondary);

            font-size: .8rem;

            font-weight: 800;

        }


        .identity-theme {

            color: var(--text-muted);

            font-size: .7rem;

            margin-top: 5px;

        }


        .sidebar-bottom {

            margin-top: auto;

            padding-top: 15px;

        }


        .logout-link {

            color: #ef4444 !important;

            cursor: pointer;

            text-align: left;

        }


        .logout-link:hover {

            background: rgba(239,68,68,.08);

        }


        /* =====================================================
           MAIN
        ====================================================== */

        .main-content {

            margin-left: 260px;

            min-height: 100vh;

            padding:
                25px 35px 60px;

            position: relative;

            z-index: 1;

        }


        /* =====================================================
           TOPBAR
        ====================================================== */

        .topbar {

            min-height: 70px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            border-bottom:
                1px solid var(--border);

            margin-bottom: 30px;

        }


        .top-title {

            font-family: var(--font-display);

            font-size: 1.4rem;

            font-weight: 900;

            color: var(--secondary);

        }


        .top-subtitle {

            font-size: .78rem;

            color: var(--text-muted);

            margin-top: 3px;

        }


        .profile-btn {

            display: flex;

            align-items: center;

            gap: 9px;

            padding: 6px 10px 6px 7px;

            border-radius: 50px;

            border:
                1px solid var(--border);

            background: var(--surface);

            color: var(--text-main);

        }


        .profile-btn:hover {

            color: var(--text-main);

            border-color: var(--secondary);

            background: var(--surface-alt);

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
                    var(--secondary),
                    var(--accent)
                );

            color: var(--page-bg);

            font-weight: 900;

        }


        /* =====================================================
           DROPDOWN
        ====================================================== */

        .dropdown-menu {

            background: var(--surface) !important;

            border:
                1px solid var(--border) !important;

        }


        .dropdown-item {

            color: var(--text-main) !important;

        }


        .dropdown-item:hover {

            color: var(--secondary) !important;

            background: var(--surface-alt) !important;

        }


        .dropdown-divider {

            border-color: var(--border);

        }


        /* =====================================================
           HERO
        ====================================================== */

        .hero {

            position: relative;

            overflow: hidden;

            padding: 35px;

            margin-bottom: 28px;

            border-radius: 22px;

            background:

                linear-gradient(
                    135deg,
                    var(--surface),
                    var(--surface-alt)
                );

            border:
                1px solid var(--border);

            box-shadow:
                0 15px 40px rgba(0,0,0,.25);

        }


        .hero::after {

            content: "";

            position: absolute;

            width: 300px;

            height: 300px;

            right: -100px;

            top: -100px;

            border-radius: 50%;

            background:
                var(--secondary);

            opacity: .08;

            filter: blur(20px);

        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 7px 13px;

            border-radius: 30px;

            color: var(--secondary);

            background: var(--surface);

            border:
                1px solid var(--secondary);

            font-size: .75rem;

            font-weight: 800;

            margin-bottom: 14px;

        }


        .hero-title {

            position: relative;

            z-index: 2;

            font-family: var(--font-display);

            font-size: 2.25rem;

            font-weight: 900;

            color: var(--secondary);

            margin-bottom: 10px;

        }


        .hero-text {

            position: relative;

            z-index: 2;

            max-width: 700px;

            color: var(--text-muted);

            line-height: 1.7;

            font-size: .92rem;

        }


        .hero-icon {

            position: relative;

            z-index: 2;

            font-size: 6rem;

            color: var(--secondary);

            filter:
                drop-shadow(
                    0 0 25px var(--glow)
                );

        }


        .interest-pill {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 7px 13px;

            margin: 4px;

            border-radius: 9px;

            color: var(--text-main);

            background: var(--surface);

            border:
                1px solid var(--border);

            font-size: .78rem;

            font-weight: 700;

        }


        .interest-pill.active {

            color: var(--page-bg);

            background: var(--secondary);

            border-color: var(--secondary);

            box-shadow:
                0 0 18px var(--glow);

        }


        /* =====================================================
           BUTTON
        ====================================================== */

        .primary-btn {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 10px 20px;

            border-radius: 10px;

            background: var(--secondary);

            border:
                1px solid var(--secondary);

            color: var(--page-bg) !important;

            font-size: .82rem;

            font-weight: 800;

            transition: .2s ease;

        }


        .primary-btn:hover {

            background: var(--accent);

            border-color: var(--accent);

            transform: translateY(-2px);

            box-shadow:
                0 8px 25px var(--glow);

        }


        /* =====================================================
           CARD
        ====================================================== */

        .glass-card {

            height: 100%;

            padding: 22px;

            border-radius: 17px;

            background:

                linear-gradient(
                    145deg,
                    var(--surface),
                    var(--surface-alt)
                );

            border:
                1px solid var(--border);

            box-shadow:
                0 8px 25px rgba(0,0,0,.18);

            transition: .25s ease;

        }


        .glass-card:hover {

            transform: translateY(-3px);

            border-color: var(--secondary);

            box-shadow:
                0 12px 30px var(--glow);

        }


        /* =====================================================
           STATS
        ====================================================== */

        .stat-card {

            display: flex;

            align-items: center;

            gap: 15px;

        }


        .stat-icon {

            width: 50px;

            height: 50px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 13px;

            color: var(--secondary);

            background: var(--surface-alt);

            border:
                1px solid var(--secondary);

            box-shadow:
                0 0 15px var(--glow);

        }


        .stat-number {

            font-family: var(--font-display);

            font-size: 1.45rem;

            font-weight: 900;

            color: var(--secondary);

        }


        .stat-label {

            color: var(--text-muted);

            font-size: .7rem;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: .8px;

        }


        /* =====================================================
           SECTION
        ====================================================== */

        .section-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-top: 35px;

            margin-bottom: 18px;

        }


        .section-title {

            font-family: var(--font-display);

            font-size: 1.3rem;

            font-weight: 900;

        }


        .section-subtitle {

            color: var(--text-muted);

            font-size: .78rem;

            margin-top: 3px;

        }


        /* =====================================================
           DAILY QUEST
        ====================================================== */

        .daily-quest {

            padding: 25px;

            margin-bottom: 30px;

            border-radius: 16px;

            border-left:
                5px solid var(--secondary);

            background:

                linear-gradient(
                    135deg,
                    var(--surface-alt),
                    var(--surface)
                );

            border-top:
                1px solid var(--border);

            border-right:
                1px solid var(--border);

            border-bottom:
                1px solid var(--border);

            box-shadow:
                0 8px 25px var(--glow);

        }


        .quest-label {

            display: inline-block;

            color: var(--secondary);

            font-size: .7rem;

            font-weight: 900;

            text-transform: uppercase;

            letter-spacing: 1px;

            margin-bottom: 8px;

        }


        .quest-heading {

            font-family: var(--font-display);

            font-size: 1.2rem;

            font-weight: 900;

            margin-bottom: 5px;

        }


        .quest-description {

            color: var(--text-muted);

            font-size: .84rem;

        }


        /* =====================================================
           QUEST CARD
        ====================================================== */

        .quest-card {

            display: flex;

            flex-direction: column;

            justify-content: space-between;

        }


        .quest-top {

            display: flex;

            justify-content: space-between;

            gap: 10px;

            margin-bottom: 13px;

        }


        .quest-category {

            padding: 4px 8px;

            border-radius: 6px;

            background: var(--surface-alt);

            color: var(--secondary);

            border:
                1px solid var(--border);

            font-size: .65rem;

            font-weight: 900;

            text-transform: uppercase;

        }


        .quest-xp {

            color: var(--secondary);

            font-size: .78rem;

            font-weight: 900;

        }


        .quest-title {

            font-family: var(--font-display);

            font-size: 1.05rem;

            font-weight: 900;

            margin-bottom: 8px;

        }


        .quest-description-small {

            color: var(--text-muted);

            font-size: .82rem;

            line-height: 1.6;

            margin-bottom: 18px;

        }


        .start-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            min-width: 120px;

            padding: 8px 14px;

            border-radius: 9px;

            color: var(--page-bg) !important;

            background: var(--secondary);

            border:
                1px solid var(--secondary);

            font-size: .76rem;

            font-weight: 900;

        }


        .start-btn:hover {

            background: var(--accent);

            border-color: var(--accent);

        }


        /* =====================================================
           TOPICS
        ====================================================== */

        .topic-chip {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 8px 13px;

            border-radius: 9px;

            color: var(--text-main);

            background: var(--surface-alt);

            border:
                1px solid var(--border);

            font-size: .8rem;

            font-weight: 600;

        }


        .topic-chip i {

            color: var(--secondary);

        }


        /* =====================================================
           MOBILE
        ====================================================== */

        @media(max-width: 992px) {

            .sidebar {

                display: none;

            }


            .main-content {

                margin-left: 0;

                padding: 18px;

            }

        }


        @media(max-width: 768px) {

            .hero {

                padding: 25px;

            }


            .hero-title {

                font-size: 1.7rem;

            }


            .section-header {

                align-items: flex-start;

                flex-direction: column;

            }

        }


        @media(max-width: 576px) {

            .main-content {

                padding:
                    12px;

            }


            .hero {

                padding: 20px;

                border-radius: 15px;

            }


            .hero-title {

                font-size: 1.45rem;

            }


            .glass-card {

                padding: 17px;

            }


            .top-title {

                font-size: 1.1rem;

            }

        }

    </style>

</head>


<body>


    {{-- =====================================================
         CHESS BACKGROUND
    ====================================================== --}}

    @if($isChess)

        <div class="chess-grid-bg"></div>

        <div class="floating-piece piece-1">♚</div>

        <div class="floating-piece piece-2">♞</div>

        <div class="floating-piece piece-3">♝</div>

        <div class="floating-piece piece-4">♜</div>

    @endif



    {{-- =====================================================
         DYNAMIC SIDEBAR
    ====================================================== --}}

    @include('student.partials.sidebar')



    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <main class="main-content">


        {{-- =================================================
             TOPBAR
        ================================================== --}}

        <div class="topbar">


            <div>

                <div class="top-title">

                    {{ $dashboardTitle }}

                </div>


                <div class="top-subtitle">

                    {{ $tagline }}

                </div>

            </div>



            {{-- PROFILE --}}

            <div class="dropdown">


                <button
                    type="button"
                    class="profile-btn dropdown-toggle"
                    data-bs-toggle="dropdown"
                >


                    <div class="d-none d-sm-block text-end">

                        <div class="fw-bold small">

                            {{ $user->name }}

                        </div>


                        <div class="top-subtitle">

                            {{ $rankTitle }}

                        </div>

                    </div>



                    @if($user->profile_picture)

                        <img
                            src="{{ asset('storage/' . $user->profile_picture) }}"
                            class="profile-avatar"
                            alt="Profile Picture"
                        >

                    @else

                        <div class="profile-avatar">

                            {{ strtoupper(substr($user->name, 0, 1)) }}

                        </div>

                    @endif


                </button>



                <ul class="dropdown-menu dropdown-menu-end shadow">


                    <li>

                        <div class="px-3 py-2">

                            <strong>
                                {{ $user->name }}
                            </strong>

                            <br>

                            <small
                                style="color:var(--text-muted);"
                            >

                                {{ $user->email }}

                            </small>

                        </div>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>

                        <a
                            href="{{ route('student.profile') }}"
                            class="dropdown-item"
                        >

                            <i class="bi bi-person-circle me-2"></i>

                            My Profile

                        </a>

                    </li>


                    <li>

                        <a
                            href="{{ route('student.badges') }}"
                            class="dropdown-item"
                        >

                            <i class="bi bi-award-fill me-2"></i>

                            My Badges

                        </a>

                    </li>


                    <li>
                        <hr class="dropdown-divider">
                    </li>


                    <li>

                        <form
                            action="{{ route('logout-user') }}"
                            method="POST"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item text-danger"
                            >

                                <i class="bi bi-box-arrow-right me-2"></i>

                                Log Out

                            </button>

                        </form>

                    </li>


                </ul>

            </div>


        </div>



        {{-- =================================================
             HERO
        ================================================== --}}

        <section class="hero">


            <div class="row align-items-center">


                <div class="col-lg-8">


                    <div class="hero-badge">

                        <i class="bi bi-stars"></i>

                        AI Personalized

                        <span>•</span>

                        {{ ucfirst($theme) }} Theme

                    </div>



                    <h1 class="hero-title">

                        Welcome,
                        {{ $user->name }}!

                    </h1>



                    <p class="hero-text">

                        {{ $welcomeMessage }}

                    </p>



                    {{-- INTERESTS --}}

                    <div class="mb-3">


                        @forelse($interests as $interest)

                            @php

                                $cleanInterest =
                                    strtolower(trim($interest));

                                $active =
                                    $theme === $cleanInterest;

                                if(
                                    $theme === 'chess'
                                    &&
                                    (
                                        str_contains(
                                            $cleanInterest,
                                            'chess'
                                        )
                                        ||
                                        str_contains(
                                            $cleanInterest,
                                            'puzzle'
                                        )
                                    )
                                ) {
                                    $active = true;
                                }

                            @endphp


                            <a
                                href="?focus={{ urlencode($interest) }}"
                                class="interest-pill
                                {{ $active ? 'active' : '' }}"
                            >

                                @if(
                                    str_contains(
                                        $cleanInterest,
                                        'chess'
                                    )
                                )

                                    ♟️

                                @elseif(
                                    str_contains(
                                        $cleanInterest,
                                        'code'
                                    )
                                    ||
                                    str_contains(
                                        $cleanInterest,
                                        'program'
                                    )
                                )

                                    💻

                                @elseif(
                                    str_contains(
                                        $cleanInterest,
                                        'music'
                                    )
                                    ||
                                    str_contains(
                                        $cleanInterest,
                                        'song'
                                    )
                                )

                                    🎵

                                @elseif(
                                    str_contains(
                                        $cleanInterest,
                                        'sport'
                                    )
                                )

                                    ⚽

                                @elseif(
                                    str_contains(
                                        $cleanInterest,
                                        'art'
                                    )
                                )

                                    🎨

                                @elseif(
                                    str_contains(
                                        $cleanInterest,
                                        'read'
                                    )
                                )

                                    📚

                                @else

                                    ✦

                                @endif


                                {{ $interest }}

                            </a>

                        @empty

                            <span class="interest-pill active">

                                General Learning

                            </span>

                        @endforelse


                    </div>



                    <div
                        class="d-flex flex-wrap align-items-center gap-3"
                    >

                        <a
                            href="#quests"
                            class="primary-btn"
                        >

                            <i class="bi bi-play-circle-fill"></i>

                            Start Learning

                        </a>


                        <span
                            style="
                                color:var(--text-muted);
                                font-size:.78rem;
                                font-style:italic;
                            "
                        >

                            "{{ $motto }}"

                        </span>

                    </div>


                </div>



                <div
                    class="col-lg-4 text-center d-none d-lg-block"
                >

                    <div class="hero-icon">

                        {{ $themeIcon }}

                    </div>


                    <div
                        class="fw-bold"
                        style="
                            color:var(--secondary);
                            font-family:var(--font-display);
                        "
                    >

                        {{ $rankTitle }}

                    </div>

                </div>


            </div>


        </section>



        {{-- =================================================
             STATS
        ================================================== --}}

        <div class="row g-3">


            <div class="col-6 col-lg-3">

                <div class="glass-card stat-card">


                    <div class="stat-icon">

                        <i class="bi bi-lightning-charge-fill"></i>

                    </div>


                    <div>

                        <div class="stat-number">

                            {{ $userXp }}

                        </div>


                        <div class="stat-label">

                            {{ $labels['exp_label'] }}

                        </div>

                    </div>


                </div>

            </div>



            <div class="col-6 col-lg-3">

                <div class="glass-card stat-card">


                    <div class="stat-icon">

                        <i class="bi bi-shield-fill-check"></i>

                    </div>


                    <div>

                        <div class="stat-number">

                            {{ $userLevel }}

                        </div>


                        <div class="stat-label">

                            Current Level

                        </div>

                    </div>


                </div>

            </div>



            <div class="col-6 col-lg-3">

                <div class="glass-card stat-card">


                    <div class="stat-icon">

                        <i class="bi bi-check2-circle"></i>

                    </div>


                    <div>

                        <div class="stat-number">

                            {{ $completedTasks }}

                        </div>


                        <div class="stat-label">

                            Completed

                        </div>

                    </div>


                </div>

            </div>



            <div class="col-6 col-lg-3">

                <div class="glass-card stat-card">


                    <div class="stat-icon">

                        <i class="bi bi-fire"></i>

                    </div>


                    <div>

                        <div class="stat-number">

                            1

                        </div>


                        <div class="stat-label">

                            Day Streak

                        </div>

                    </div>


                </div>

            </div>


        </div>



        {{-- =================================================
             CHESS SPECIAL AREA
        ================================================== --}}

        @if($isChess)

            <div class="glass-card mt-4">


                <div
                    class="d-flex align-items-center justify-content-between"
                >


                    <div>

                        <div
                            class="fw-bold"
                            style="
                                color:var(--secondary);
                                font-family:var(--font-display);
                            "
                        >

                            ♟ Grandmaster Path

                        </div>


                        <div
                            class="small"
                            style="color:var(--text-muted);"
                        >

                            Your chess progression

                        </div>

                    </div>


                    <span
                        style="
                            color:var(--secondary);
                            font-weight:900;
                        "
                    >

                        Level {{ $userLevel }}

                    </span>


                </div>


                <div
                    class="d-flex flex-wrap gap-2 mt-3"
                >


                    <span class="interest-pill">

                        ♙ Pawn

                    </span>


                    <span class="interest-pill active">

                        ♘ {{ $rankTitle }}

                    </span>


                    <span class="interest-pill">

                        ♗ Bishop

                    </span>


                    <span class="interest-pill">

                        ♖ Rook

                    </span>


                    <span class="interest-pill">

                        ♕ Queen

                    </span>


                    <span class="interest-pill">

                        ♔ King

                    </span>


                </div>


            </div>

        @endif



        {{-- =================================================
             DAILY QUEST
        ================================================== --}}

        <div class="daily-quest mt-4">


            <div class="row align-items-center">


                <div class="col-lg-9">


                    <div class="quest-label">

                        <i class="bi bi-clock-history me-1"></i>

                        {{ $labels['quest_title'] }}

                    </div>


                    <div class="quest-heading">

                        {{ $dailyQuest }}

                    </div>


                    <div class="quest-description">

                        Complete this personalized AI challenge
                        and earn XP.

                    </div>


                </div>


                <div
                    class="col-lg-3 text-lg-end mt-3 mt-lg-0"
                >

                    <a
                        href="#quests"
                        class="primary-btn"
                    >

                        Start

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>


            </div>


        </div>



        {{-- =================================================
             QUESTS
        ================================================== --}}

        <div
            class="section-header"
            id="quests"
        >


            <div>

                <div class="section-title">

                    {{ $labels['mission_title'] }}

                </div>


                <div class="section-subtitle">

                    AI-generated learning quests based on your interests

                </div>

            </div>


            <span
                style="
                    color:var(--secondary);
                    font-size:.75rem;
                    font-weight:800;
                "
            >

                {{ $tasks->count() }} Available

            </span>


        </div>



        <div class="row g-4">


            @forelse($tasks as $task)


                <div class="col-md-6 col-xl-4">


                    <div class="glass-card quest-card">


                        <div>


                            <div class="quest-top">


                                <span class="quest-category">

                                    {{ $task->category ?? 'Quest' }}

                                </span>


                                <span class="quest-xp">

                                    <i class="bi bi-lightning-charge-fill"></i>

                                    +{{ $task->xp ?? 20 }} XP

                                </span>


                            </div>



                            <h4 class="quest-title">

                                {{ $task->title }}

                            </h4>



                            <p class="quest-description-small">

                                {{ $task->description
                                    ?? 'Complete this learning challenge.'
                                }}

                            </p>


                        </div>



                        <div
                            class="d-flex align-items-center justify-content-between pt-3"
                            style="
                                border-top:
                                    1px solid var(--border);
                            "
                        >


                            <span
                                class="small text-capitalize"
                                style="color:var(--text-muted);"
                            >

                                <i class="bi bi-bar-chart me-1"></i>

                                {{ $task->difficulty ?? $difficulty }}

                            </span>



                            <a
                                href="{{ route('student.game.play', $task->id) }}"
                                class="start-btn"
                            >

                                Start Quest

                                <i class="bi bi-chevron-right"></i>

                            </a>


                        </div>


                    </div>


                </div>


            @empty


                <div class="col-12">


                    <div
                        class="glass-card text-center py-5"
                    >


                        <div
                            style="
                                font-size:3rem;
                                color:var(--secondary);
                            "
                        >

                            {{ $themeIcon }}

                        </div>


                        <h4 class="mt-3">

                            No Pending Quests

                        </h4>


                        <p
                            style="color:var(--text-muted);"
                        >

                            You have completed all your current quests.

                        </p>


                    </div>


                </div>


            @endforelse


        </div>



        {{-- =================================================
             AI TOPICS
        ================================================== --}}

        <div
            class="section-header"
            id="topics"
        >


            <div>

                <div class="section-title">

                    AI Recommendations

                </div>


                <div class="section-subtitle">

                    Topics recommended from your interests

                </div>

            </div>


        </div>



        <div class="glass-card">


            <div
                class="d-flex align-items-center gap-3 mb-3"
            >


                <div class="stat-icon">

                    <i class="bi bi-stars"></i>

                </div>


                <div>

                    <div
                        class="small text-uppercase fw-bold"
                        style="color:var(--text-muted);"
                    >

                        Gemini AI

                    </div>


                    <div class="fw-bold">

                        Recommended Learning Topics

                    </div>

                </div>


            </div>



            <div
                class="d-flex flex-wrap gap-2"
            >


                @forelse($recommendedTopics as $topic)


                    <span class="topic-chip">

                        <i class="bi bi-lightbulb"></i>

                        {{ $topic }}

                    </span>


                @empty


                    @if($preference && !empty($preference->interests))


                        @foreach($preference->interests as $item)


                            <span class="topic-chip">

                                <i class="bi bi-lightbulb"></i>

                                {{ $item }} Mastery

                            </span>


                        @endforeach


                    @else


                        <span class="topic-chip">

                            <i class="bi bi-lightbulb"></i>

                            Personalized Learning

                        </span>


                    @endif


                @endforelse


            </div>


        </div>



        {{-- =================================================
             FOOTER
        ================================================== --}}

        <footer
            class="text-center mt-5 pt-4 pb-3"
            style="
                color:var(--text-muted);
                border-top:
                    1px solid var(--border);
                font-size:.75rem;
            "
        >

            {{ $themeIcon }}

            <strong style="color:var(--secondary);">
                EcoQuest
            </strong>

            • Gamified Learning Realm

            <div class="mt-1">

                AI Personalized Dashboard

            </div>

        </footer>


    </main>



    {{-- =====================================================
         BOOTSTRAP JS
    ====================================================== --}}

    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>


</body>

</html>