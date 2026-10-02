<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $aiProfile['dashboard_title'] ?? 'Student Dashboard' }} | EcoQuest
    </title>


    <!-- =========================================================
         BOOTSTRAP
    ========================================================== -->

    <link rel="stylesheet"
          href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">


    <!-- =========================================================
         BOOTSTRAP ICONS
    ========================================================== -->

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- =========================================================
         GOOGLE FONTS
    ========================================================== -->

    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap"
          rel="stylesheet">


    @if(!empty($themeConfig['font_url']))

        <link href="{{ $themeConfig['font_url'] }}"
              rel="stylesheet">

    @endif


    <!-- =========================================================
         THEME DATA
    ========================================================== -->

    @php

        $theme = $themeKey ?? 'general';

        $isChess = ($theme === 'chess');


        /*
        |--------------------------------------------------------------------------
        | THEME COLORS
        |--------------------------------------------------------------------------
        */

        $primaryColor =
            $themeConfig['primary']
            ?? ($aiProfile['primary_color'] ?? '#0F172A');


        $secondaryColor =
            $themeConfig['secondary']
            ?? ($aiProfile['secondary_color'] ?? '#38BDF8');


        $accentColor =
            $themeConfig['accent']
            ?? ($aiProfile['accent_color'] ?? '#22D3EE');


        $backgroundColor =
            $themeConfig['background']
            ?? ($isChess ? '#08080A' : '#0F172A');


        $surfaceColor =
            $themeConfig['surface']
            ?? ($isChess ? '#121216' : '#1E293B');


        $surfaceAlt =
            $themeConfig['surface_alt']
            ?? ($isChess ? '#1A1A22' : '#27354D');


        $borderColor =
            $themeConfig['border']
            ?? ($isChess ? '#2E2E3E' : 'rgba(255,255,255,0.10)');


        $textColor =
            $themeConfig['text']
            ?? '#F8FAFC';


        $mutedColor =
            $themeConfig['muted']
            ?? '#94A3B8';


        $glowColor =
            $themeConfig['glow']
            ?? 'rgba(56,189,248,0.25)';


        /*
        |--------------------------------------------------------------------------
        | THEME ICONS
        |--------------------------------------------------------------------------
        */

        $themeIcon =
            $themeConfig['icon']
            ?? ($isChess ? '♞' : '✦');


        $decorations =
            $themeConfig['decorations']
            ?? ($isChess
                ? ['♔','♕','♖','♗','♘','♙']
                : ['✦','★','◆']
            );


        /*
        |--------------------------------------------------------------------------
        | FONTS
        |--------------------------------------------------------------------------
        */

        $fontDisplay =
            $themeConfig['font_display']
            ?? ($isChess ? 'Cinzel' : 'Plus Jakarta Sans');


        /*
        |--------------------------------------------------------------------------
        | AI CONTENT
        |--------------------------------------------------------------------------
        */

        $rankTitle =
            $aiProfile['rank_title']
            ?? ($themeConfig['gamified_rank'] ?? 'Explorer');


        $dashboardTitle =
            $aiProfile['dashboard_title']
            ?? ($themeConfig['title'] ?? 'Learning Arena');


        $tagline =
            $aiProfile['tagline']
            ?? ($themeConfig['subtitle'] ?? 'Your personalized learning world');


        $motto =
            $aiProfile['motto']
            ?? ($themeConfig['gamified_motto']
                ?? 'Every step forward makes you stronger.'
            );


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
        | XP / PROGRESS
        |--------------------------------------------------------------------------
        */

        $userXp =
            $progress->total_xp
            ?? 0;


        $userLevel =
            $progress->level
            ?? 1;


        $completedTasks =
            $progress->completed_tasks
            ?? 0;


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



    <style>

        /* =========================================================
           ROOT THEME VARIABLES
        ========================================================== */

        :root {

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
                -apple-system,
                sans-serif;

        }



        /* =========================================================
           GLOBAL
        ========================================================== */

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



        /* =========================================================
           CHESS BACKGROUND
        ========================================================== */

        .chess-grid-bg {

            position: fixed;

            inset: 0;

            pointer-events: none;

            z-index: 0;

            opacity: 0.045;

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



        /* =========================================================
           FLOATING DECORATIONS
        ========================================================== */

        .floating-piece {

            position: fixed;

            pointer-events: none;

            z-index: 0;

            color: var(--secondary);

            opacity: 0.045;

            font-family: var(--font-display);

            user-select: none;

        }


        .piece-1 {

            top: 7%;

            right: 4%;

            font-size: 9rem;

            transform: rotate(12deg);

        }


        .piece-2 {

            bottom: 12%;

            left: 19%;

            font-size: 7.5rem;

            transform: rotate(-15deg);

        }


        .piece-3 {

            top: 48%;

            right: 22%;

            font-size: 5rem;

            transform: rotate(8deg);

        }


        .piece-4 {

            bottom: 25%;

            right: 6%;

            font-size: 8rem;

            transform: rotate(-10deg);

        }



        /* =========================================================
           AMBIENT GLOW
        ========================================================== */

        .ambient-glow {

            position: fixed;

            border-radius: 50%;

            pointer-events: none;

            filter: blur(120px);

            z-index: 0;

            opacity: 0.12;

        }


        .glow-1 {

            width: 450px;

            height: 450px;

            top: -120px;

            right: -80px;

            background:
                radial-gradient(
                    circle,
                    var(--secondary),
                    transparent 70%
                );

        }


        .glow-2 {

            width: 400px;

            height: 400px;

            bottom: -80px;

            left: 180px;

            background:
                radial-gradient(
                    circle,
                    var(--accent),
                    transparent 70%
                );

        }



        /* =========================================================
           SIDEBAR
        ========================================================== */

        .sidebar {

            position: fixed;

            top: 0;

            left: 0;

            bottom: 0;

            width: 260px;

            background:
                linear-gradient(
                    180deg,
                    var(--surface),
                    var(--page-bg)
                );

            border-right:
                1px solid var(--border);

            padding: 24px 18px;

            z-index: 100;

            display: flex;

            flex-direction: column;

            backdrop-filter: blur(18px);

        }



        /* =========================================================
           BRAND
        ========================================================== */

        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            text-decoration: none;

            color: var(--text-main);

            margin-bottom: 32px;

            padding: 8px 10px;

        }


        .brand-icon {

            width: 42px;

            height: 42px;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    var(--secondary),
                    var(--accent)
                );

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.35rem;

            color: var(--page-bg);

            box-shadow:
                0 8px 25px var(--glow);

        }


        .brand-text {

            font-size: 1.25rem;

            font-weight: 800;

        }


        .brand-text span {

            color: var(--secondary);

        }



        /* =========================================================
           SIDEBAR LINKS
        ========================================================== */

        .nav-category {

            font-size: 0.7rem;

            text-transform: uppercase;

            letter-spacing: 1.4px;

            font-weight: 700;

            color: var(--text-muted);

            padding: 8px 12px;

            margin-top: 10px;

        }


        .side-link {

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 11px 14px;

            border-radius: 11px;

            color: var(--text-muted);

            text-decoration: none;

            font-size: 0.92rem;

            font-weight: 600;

            transition: all 0.25s ease;

            margin-bottom: 4px;

        }


        .side-link:hover {

            color: var(--secondary);

            background: var(--surface-alt);

            transform: translateX(3px);

        }


        .side-link.active {

            color: var(--secondary);

            background: var(--surface-alt);

            border:
                1px solid var(--secondary);

            box-shadow:
                0 0 18px var(--glow);

        }


        .side-link i {

            font-size: 1.1rem;

        }



        /* =========================================================
           MAIN
        ========================================================== */

        .main-content {

            margin-left: 260px;

            min-height: 100vh;

            padding: 24px 36px 60px;

            position: relative;

            z-index: 1;

        }


        @media(max-width: 992px) {

            .sidebar {

                display: none;

            }

            .main-content {

                margin-left: 0;

                padding:
                    18px
                    18px
                    40px;

            }

        }



        /* =========================================================
           TOPBAR
        ========================================================== */

        .topbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding-bottom: 22px;

            border-bottom:
                1px solid var(--border);

            margin-bottom: 28px;

        }


        .top-title {

            font-family: var(--font-display);

            font-size: 1.5rem;

            font-weight: 800;

            color: var(--secondary);

        }


        .top-subtitle {

            font-size: 0.85rem;

            color: var(--text-muted);

            margin-top: 3px;

        }


        .profile-btn {

            background: var(--surface);

            border:
                1px solid var(--border);

            border-radius: 13px;

            padding: 6px 12px;

            display: flex;

            align-items: center;

            gap: 12px;

            color: var(--text-main);

        }


        .profile-btn:hover {

            background: var(--surface-alt);

            border-color: var(--secondary);

        }


        .avatar {

            width: 40px;

            height: 40px;

            border-radius: 50%;

            object-fit: cover;

            background: var(--surface-alt);

            border:
                2px solid var(--secondary);

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 800;

            color: var(--secondary);

        }



        /* =========================================================
           DROPDOWN
        ========================================================== */

        .dropdown-menu {

            background: var(--surface) !important;

            border:
                1px solid var(--border) !important;

        }


        .dropdown-item {

            color: var(--text-main);

        }


        .dropdown-item:hover {

            background: var(--surface-alt);

            color: var(--secondary);

        }



        /* =========================================================
           HERO
        ========================================================== */

        .theme-hero {

            background:
                linear-gradient(
                    135deg,
                    var(--surface),
                    var(--surface-alt)
                );

            border:
                1px solid var(--border);

            border-radius: 22px;

            padding: 36px 40px;

            position: relative;

            overflow: hidden;

            margin-bottom: 28px;

            box-shadow:
                0 18px 45px rgba(0,0,0,0.25);

        }


        .theme-hero-grid {

            position: absolute;

            inset: 0;

            opacity: 0.04;

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
                );

            background-size: 32px 32px;

        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 7px 14px;

            background: var(--surface);

            border:
                1px solid var(--secondary);

            border-radius: 30px;

            font-size: 0.78rem;

            font-weight: 700;

            text-transform: uppercase;

            color: var(--secondary);

            margin-bottom: 14px;

            box-shadow:
                0 0 15px var(--glow);

        }


        .hero-title {

            font-family: var(--font-display);

            font-size: 2.35rem;

            font-weight: 900;

            color: var(--secondary);

            margin-bottom: 10px;

        }


        .hero-motto {

            font-size: 0.98rem;

            color: var(--text-muted);

            max-width: 680px;

            line-height: 1.7;

            margin-bottom: 20px;

        }


        .interest-pill {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 7px 13px;

            border-radius: 9px;

            background: var(--surface);

            border:
                1px solid var(--border);

            color: var(--text-main);

            text-decoration: none;

            font-size: 0.82rem;

            font-weight: 600;

            margin-right: 7px;

            margin-bottom: 7px;

            transition: all 0.25s ease;

        }


        .interest-pill:hover {

            color: var(--secondary);

            border-color: var(--secondary);

            background: var(--surface-alt);

        }


        .interest-pill.active-pill {

            color: var(--page-bg);

            background: var(--secondary);

            border-color: var(--secondary);

            box-shadow:
                0 5px 20px var(--glow);

        }


        .hero-visual-icon {

            font-size: 6rem;

            color: var(--secondary);

            filter:
                drop-shadow(
                    0 8px 25px var(--glow)
                );

        }



        /* =========================================================
           BUTTONS
        ========================================================== */

        .action-btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            padding: 10px 22px;

            border-radius: 11px;

            border:
                1px solid var(--secondary);

            background: var(--secondary);

            color: var(--page-bg) !important;

            font-size: 0.88rem;

            font-weight: 800;

            text-decoration: none;

            cursor: pointer;

            transition: all 0.25s ease;

            box-shadow:
                0 6px 20px var(--glow);

        }


        .action-btn:hover {

            background: var(--accent);

            border-color: var(--accent);

            color: var(--page-bg) !important;

            transform: translateY(-2px);

            box-shadow:
                0 10px 28px var(--glow);

        }



        /* =========================================================
           START QUEST BUTTON
           VERY IMPORTANT
        ========================================================== */

        .quest-action-form {

            margin: 0;

            padding: 0;

            display: inline-block;

        }


        .action-btn-outline {

            appearance: none;

            -webkit-appearance: none;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 8px 15px;

            min-width: 120px;

            border-radius: 10px;

            border:
                2px solid var(--secondary) !important;

            background:
                var(--secondary) !important;

            color:
                var(--page-bg) !important;

            font-family: var(--font-body);

            font-size: 0.8rem;

            font-weight: 800;

            line-height: 1.2;

            text-decoration: none;

            cursor: pointer;

            opacity: 1 !important;

            transition:
                transform 0.2s ease,
                background 0.2s ease,
                border-color 0.2s ease,
                box-shadow 0.2s ease;

            box-shadow:
                0 5px 18px var(--glow);

        }


        .action-btn-outline:hover {

            background:
                var(--accent) !important;

            border-color:
                var(--accent) !important;

            color:
                var(--page-bg) !important;

            transform:
                translateY(-2px);

            box-shadow:
                0 9px 24px var(--glow);

        }


        .action-btn-outline:focus {

            outline: none !important;

            background:
                var(--secondary) !important;

            border-color:
                var(--secondary) !important;

            color:
                var(--page-bg) !important;

            box-shadow:
                0 0 0 4px var(--glow);

        }


        .action-btn-outline:active {

            transform:
                translateY(0);

        }



        /* =========================================================
           CARDS
        ========================================================== */

        .glass-card {

            background:
                linear-gradient(
                    145deg,
                    var(--surface),
                    var(--surface-alt)
                );

            border:
                1px solid var(--border);

            border-radius: 17px;

            padding: 24px;

            position: relative;

            overflow: hidden;

            box-shadow:
                0 8px 25px rgba(0,0,0,0.20);

            transition:
                transform 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease;

        }


        .glass-card:hover {

            transform:
                translateY(-3px);

            border-color:
                var(--secondary);

            box-shadow:
                0 14px 35px var(--glow);

        }



        /* =========================================================
           STATS
        ========================================================== */

        .stat-card {

            display: flex;

            align-items: center;

            gap: 17px;

            padding: 21px;

        }


        .stat-icon {

            width: 52px;

            height: 52px;

            border-radius: 14px;

            background:
                var(--surface-alt);

            border:
                1px solid var(--secondary);

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 1.4rem;

            color:
                var(--secondary);

            flex-shrink: 0;

            box-shadow:
                0 0 15px var(--glow);

        }


        .stat-number {

            font-family: var(--font-display);

            font-size: 1.65rem;

            font-weight: 900;

            color:
                var(--secondary);

            line-height: 1.1;

        }


        .stat-label {

            font-size: 0.75rem;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.8px;

            color:
                var(--text-muted);

            margin-top: 4px;

        }



        /* =========================================================
           CHESS RANK
        ========================================================== */

        .rank-tier-list {

            display: flex;

            align-items: center;

            gap: 12px;

            overflow-x: auto;

            padding: 10px 0;

        }


        .rank-node {

            display: flex;

            align-items: center;

            gap: 8px;

            padding: 9px 14px;

            border-radius: 10px;

            background: var(--surface);

            border:
                1px solid var(--border);

            color:
                var(--text-muted);

            font-size: 0.82rem;

            font-weight: 600;

            white-space: nowrap;

        }


        .rank-node.current {

            background:
                var(--secondary);

            border-color:
                var(--secondary);

            color:
                var(--page-bg);

            box-shadow:
                0 0 20px var(--glow);

        }


        .rank-divider {

            color:
                var(--secondary);

        }



        /* =========================================================
           GAMBIT
        ========================================================== */

        .gambit-card {

            background:
                linear-gradient(
                    135deg,
                    var(--surface-alt),
                    var(--surface)
                );

            border:
                1px solid var(--secondary);

            border-left:
                5px solid var(--secondary);

            padding:
                26px 30px;

            border-radius:
                16px;

            margin-bottom:
                28px;

            box-shadow:
                0 8px 25px var(--glow);

        }


        .gambit-badge {

            display: inline-block;

            padding:
                5px 10px;

            border-radius:
                7px;

            color:
                var(--secondary);

            background:
                var(--surface);

            border:
                1px solid var(--secondary);

            font-size:
                0.73rem;

            font-weight:
                800;

            text-transform:
                uppercase;

            letter-spacing:
                1px;

            margin-bottom:
                10px;

        }


        .gambit-title {

            font-family:
                var(--font-display);

            font-size:
                1.25rem;

            font-weight:
                800;

            color:
                var(--text-main);

            margin-bottom:
                8px;

        }


        .gambit-desc {

            color:
                var(--text-muted);

            font-size:
                0.9rem;

        }



        /* =========================================================
           QUEST CARDS
        ========================================================== */

        .quest-card {

            display:
                flex;

            flex-direction:
                column;

            justify-content:
                space-between;

            height:
                100%;

        }


        .quest-top {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            gap:
                10px;

            margin-bottom:
                14px;

        }


        .quest-category {

            font-size:
                0.7rem;

            font-weight:
                800;

            text-transform:
                uppercase;

            letter-spacing:
                1px;

            color:
                var(--secondary);

            background:
                var(--surface-alt);

            padding:
                4px 9px;

            border-radius:
                6px;

            border:
                1px solid var(--border);

        }


        .quest-xp {

            font-size:
                0.84rem;

            font-weight:
                900;

            color:
                var(--secondary);

            display:
                flex;

            align-items:
                center;

            gap:
                4px;

        }


        .quest-title {

            font-family:
                var(--font-display);

            font-size:
                1.1rem;

            font-weight:
                800;

            color:
                var(--text-main);

            margin-bottom:
                8px;

        }


        .quest-desc {

            font-size:
                0.85rem;

            color:
                var(--text-muted);

            line-height:
                1.55;

            margin-bottom:
                18px;

        }



        /* =========================================================
           SECTION HEADERS
        ========================================================== */

        .section-header {

            display:
                flex;

            align-items:
                center;

            justify-content:
                space-between;

            margin-top:
                36px;

            margin-bottom:
                20px;

        }


        .section-title {

            font-family:
                var(--font-display);

            font-size:
                1.35rem;

            font-weight:
                800;

            color:
                var(--text-main);

        }


        .section-subtitle {

            font-size:
                0.84rem;

            color:
                var(--text-muted);

        }



        /* =========================================================
           TOPIC CHIPS
        ========================================================== */

        .topic-chip {

            display:
                inline-flex;

            align-items:
                center;

            gap:
                6px;

            padding:
                8px 14px;

            border-radius:
                10px;

            background:
                var(--surface-alt);

            border:
                1px solid var(--border);

            color:
                var(--text-main);

            font-size:
                0.84rem;

            font-weight:
                600;

            transition:
                all 0.2s ease;

        }


        .topic-chip:hover {

            color:
                var(--secondary);

            border-color:
                var(--secondary);

            background:
                var(--surface);

        }



        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media(max-width: 768px) {

            .theme-hero {

                padding:
                    25px 22px;

            }


            .hero-title {

                font-size:
                    1.8rem;

            }


            .gambit-card {

                padding:
                    22px;

            }


            .section-header {

                align-items:
                    flex-start;

                gap:
                    12px;

                flex-direction:
                    column;

            }

        }

    </style>

</head>


<body>


    <!-- =========================================================
         CHESS BACKGROUND
    ========================================================== -->

    @if($isChess)

        <div class="chess-grid-bg"></div>

        <div class="floating-piece piece-1">♚</div>

        <div class="floating-piece piece-2">♞</div>

        <div class="floating-piece piece-3">♝</div>

        <div class="floating-piece piece-4">♜</div>

    @endif


    <div class="ambient-glow glow-1"></div>

    <div class="ambient-glow glow-2"></div>



    <!-- =========================================================
         SIDEBAR
    ========================================================== -->

    <aside class="sidebar">


        <!-- BRAND -->

        <a href="{{ route('student.dashboard') }}"
           class="brand">

            <div class="brand-icon">

                {{ $themeIcon }}

            </div>


            <div class="brand-text">

                Eco<span>Quest</span>

            </div>

        </a>



        <!-- MAIN MENU -->

        <div class="nav-category">

            Main Menu

        </div>


        <a href="{{ route('student.dashboard') }}"
           class="side-link active">

            <i class="bi bi-grid-1x2-fill"></i>

            Dashboard

        </a>


        <a href="{{ route('student.profile') }}"
           class="side-link">

            <i class="bi bi-person-circle"></i>

            My Profile

        </a>


        <a href="#quests"
           class="side-link">

            <i class="bi bi-trophy-fill"></i>

            {{ $labels['mission_title'] }}

        </a>


        <a href="#progress"
           class="side-link">

            <i class="bi bi-graph-up-arrow"></i>

            My Progress

        </a>


        <a href="#topics"
           class="side-link">

            <i class="bi bi-lightbulb-fill"></i>

            AI Topics

        </a>



        <!-- IDENTITY -->

        <div class="nav-category mt-3">

            Identity

        </div>


        <div class="p-2 px-3 mt-1 rounded"
             style="
                background: var(--surface-alt);
                border: 1px solid var(--border);
             ">

            <div class="d-flex align-items-center gap-2 mb-1">

                <span style="font-size:1.1rem;">
                    {{ $themeIcon }}
                </span>

                <span class="fw-bold small text-truncate"
                      style="color:var(--secondary);">

                    {{ $rankTitle }}

                </span>

            </div>


            <div class="text-muted"
                 style="font-size:0.72rem;">

                {{ ucfirst($theme) }}

                Theme •

                {{ ucfirst($difficulty) }}

            </div>

        </div>



        <!-- LOGOUT -->

        <div class="mt-auto">

            <form action="{{ route('logout-user') }}"
                  method="POST">

                @csrf

                <button type="submit"
                        class="side-link w-100 border-0"
                        style="
                            background:transparent;
                            color:#ef4444;
                        ">

                    <i class="bi bi-box-arrow-right"></i>

                    Log Out

                </button>

            </form>

        </div>

    </aside>



    <!-- =========================================================
         MAIN CONTENT
    ========================================================== -->

    <div class="main-content">


        <!-- =====================================================
             TOPBAR
        ====================================================== -->

        <header class="topbar">


            <div>

                <div class="top-title">

                    {{ $dashboardTitle }}

                </div>


                <div class="top-subtitle">

                    {{ $tagline }}

                </div>

            </div>



            <!-- PROFILE -->

            <div class="dropdown">


                <button class="profile-btn dropdown-toggle"
                        data-bs-toggle="dropdown"
                        type="button">


                    <div class="d-none d-sm-block text-end">

                        <div class="fw-bold small">

                            {{ $user->name }}

                        </div>


                        <div class="top-subtitle"
                             style="font-size:0.72rem;">

                            {{ $rankTitle }}

                        </div>

                    </div>



                    @if($user->profile_picture)

                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                             class="avatar"
                             alt="Profile Picture">

                    @else

                        <div class="avatar">

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

                            <small class="text-muted">

                                {{ $user->email }}

                            </small>

                        </div>

                    </li>


                    <li>

                        <hr class="dropdown-divider"
                            style="border-color:var(--border);">

                    </li>


                    <li>

                        <a href="{{ route('student.profile') }}"
                           class="dropdown-item">

                            <i class="bi bi-person me-2"></i>

                            My Profile

                        </a>

                    </li>


                    <li>

                        <div class="px-3 py-2">

                            <span class="badge"
                                  style="
                                    background:var(--secondary);
                                    color:var(--page-bg);
                                  ">

                                {{ $rankTitle }}

                            </span>

                        </div>

                    </li>


                    <li>

                        <hr class="dropdown-divider"
                            style="border-color:var(--border);">

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
             HERO
        ====================================================== -->

        <section class="theme-hero">


            <div class="theme-hero-grid"></div>


            <div class="row align-items-center position-relative">


                <div class="col-lg-8">


                    <div class="hero-badge">

                        <i class="bi bi-stars"></i>

                        AI Personalized

                        •

                        {{ ucfirst($theme) }} Arena

                    </div>



                    <h1 class="hero-title">

                        Welcome,
                        {{ $user->name }}!

                    </h1>



                    <p class="hero-motto">

                        {{ $welcomeMessage }}

                    </p>



                    <!-- INTERESTS -->

                    <div class="d-flex flex-wrap align-items-center mb-3">


                        @if(count($interests) > 1)

                            <span class="small me-2 text-muted fw-bold">

                                <i class="bi bi-shuffle me-1"></i>

                                FOCUS:

                            </span>

                        @endif



                        @foreach($interests as $interest)


                            @php

                                $cleanInt =
                                    strtolower(trim($interest));

                                $isActive = false;


                                if (
                                    $theme === 'chess'
                                    &&
                                    (
                                        stripos($cleanInt,'chess') !== false
                                        ||
                                        stripos($cleanInt,'puzzle') !== false
                                    )
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'technology'
                                    &&
                                    (
                                        stripos($cleanInt,'code') !== false
                                        ||
                                        stripos($cleanInt,'tech') !== false
                                        ||
                                        stripos($cleanInt,'program') !== false
                                    )
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'music'
                                    &&
                                    (
                                        stripos($cleanInt,'music') !== false
                                        ||
                                        stripos($cleanInt,'song') !== false
                                    )
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'sports'
                                    &&
                                    stripos($cleanInt,'sport') !== false
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'reading'
                                    &&
                                    stripos($cleanInt,'read') !== false
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'creative'
                                    &&
                                    (
                                        stripos($cleanInt,'art') !== false
                                        ||
                                        stripos($cleanInt,'design') !== false
                                    )
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'science'
                                    &&
                                    (
                                        stripos($cleanInt,'science') !== false
                                        ||
                                        stripos($cleanInt,'environ') !== false
                                    )
                                ) {

                                    $isActive = true;

                                }

                                elseif (
                                    $theme === 'mathematics'
                                    &&
                                    stripos($cleanInt,'math') !== false
                                ) {

                                    $isActive = true;

                                }

                            @endphp



                            @if(count($interests) > 1)

                                <a href="?focus={{ urlencode($interest) }}"
                                   class="interest-pill {{ $isActive ? 'active-pill' : '' }}">


                                    @if(stripos($interest,'chess') !== false)

                                        ♟️

                                    @elseif(
                                        stripos($interest,'code') !== false
                                        ||
                                        stripos($interest,'tech') !== false
                                    )

                                        💻

                                    @elseif(
                                        stripos($interest,'music') !== false
                                        ||
                                        stripos($interest,'song') !== false
                                    )

                                        🎵

                                    @elseif(
                                        stripos($interest,'sport') !== false
                                    )

                                        ⚽

                                    @elseif(
                                        stripos($interest,'art') !== false
                                    )

                                        🎨

                                    @elseif(
                                        stripos($interest,'read') !== false
                                    )

                                        📚

                                    @elseif(
                                        stripos($interest,'puzzle') !== false
                                    )

                                        🧩

                                    @else

                                        ✦

                                    @endif


                                    {{ $interest }}


                                    @if($isActive)

                                        <span class="badge ms-1 rounded-pill"
                                              style="
                                                background:var(--page-bg);
                                                color:var(--secondary);
                                              ">

                                            Active

                                        </span>

                                    @endif


                                </a>

                            @else

                                <span class="interest-pill active-pill">

                                    {{ $interest }}

                                </span>

                            @endif


                        @endforeach

                    </div>



                    <!-- HERO ACTION -->

                    <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">


                        <a href="#quests"
                           class="action-btn">

                            <i class="bi bi-play-circle-fill"></i>

                            Start Learning

                        </a>


                        <span class="small"
                              style="
                                color:var(--text-muted);
                                font-style:italic;
                              ">

                            "{{ $motto }}"

                        </span>

                    </div>

                </div>



                <!-- HERO ICON -->

                <div class="col-lg-4 text-center d-none d-lg-block">

                    <div class="hero-visual-icon">

                        {{ $themeIcon }}

                    </div>


                    <div class="fw-bold mt-2"
                         style="
                            font-family:var(--font-display);
                            letter-spacing:1px;
                            color:var(--secondary);
                         ">

                        {{ $rankTitle }}

                    </div>

                </div>


            </div>

        </section>



        <!-- =====================================================
             STATS
        ====================================================== -->

        <div class="row g-3 mb-4"
             id="progress">


            <!-- XP -->

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



            <!-- LEVEL -->

            <div class="col-6 col-lg-3">

                <div class="glass-card stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-shield-fill-check"></i>

                    </div>


                    <div>

                        <div class="stat-number">

                            {{ $labels['level_prefix'] }}
                            {{ $userLevel }}

                        </div>


                        <div class="stat-label">

                            Current Level

                        </div>

                    </div>

                </div>

            </div>



            <!-- QUESTS -->

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

                            Quests Solved

                        </div>

                    </div>

                </div>

            </div>



            <!-- STREAK -->

            <div class="col-6 col-lg-3">

                <div class="glass-card stat-card">

                    <div class="stat-icon">

                        <i class="bi bi-fire"></i>

                    </div>


                    <div>

                        <div class="stat-number">

                            1 Day

                        </div>


                        <div class="stat-label">

                            Streak Active

                        </div>

                    </div>

                </div>

            </div>


        </div>



        <!-- =====================================================
             CHESS PROGRESSION
        ====================================================== -->

        @if($isChess)

            <div class="glass-card mb-4">


                <div class="d-flex align-items-center justify-content-between mb-3">

                    <div class="d-flex align-items-center gap-2">

                        <span style="font-size:1.3rem;">

                            ♟️

                        </span>

                        <span class="fw-bold"
                              style="font-family:var(--font-display);">

                            Grandmaster Path

                        </span>

                    </div>


                    <span class="badge"
                          style="
                            background:var(--secondary);
                            color:var(--page-bg);
                          ">

                        Tier {{ $userLevel }}

                    </span>

                </div>



                <div class="rank-tier-list">


                    <div class="rank-node">

                        ♙ Pawn

                    </div>


                    <div class="rank-divider">

                        →

                    </div>


                    <div class="rank-node current">

                        ♘ {{ $rankTitle }}

                    </div>


                    <div class="rank-divider">

                        →

                    </div>


                    <div class="rank-node">

                        ♗ Bishop

                    </div>


                    <div class="rank-divider">

                        →

                    </div>


                    <div class="rank-node">

                        ♖ Rook

                    </div>


                    <div class="rank-divider">

                        →

                    </div>


                    <div class="rank-node">

                        ♕ Queen

                    </div>


                    <div class="rank-divider">

                        →

                    </div>


                    <div class="rank-node">

                        ♔ King

                    </div>


                </div>

            </div>

        @endif

        <!-- =====================================================
             DAILY QUEST
        ====================================================== -->
        

        <div class="gambit-card">


            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">


                <div>

                    <span class="gambit-badge">

                        <i class="bi bi-clock-history me-1"></i>

                        {{ $labels['quest_title'] }}

                    </span>


                    <h3 class="gambit-title">

                        {{ $dailyQuest }}

                    </h3>


                    <p class="gambit-desc mb-0">

                        Complete this personalized AI challenge
                        to earn XP and continue your learning journey.

                    </p>

                </div>


                <div>

                    <a href="#quests"
                       class="action-btn">

                        Make Move

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>


            </div>

        </div>



        <!-- =====================================================
             QUEST SECTION
        ====================================================== -->

        <div class="section-header"
             id="quests">


            <div>

                <h2 class="section-title">

                    {{ $labels['mission_title'] }}

                </h2>


                <div class="section-subtitle">

                    Learning missions generated dynamically by Gemini AI

                </div>

            </div>


            <span class="badge"
                  style="
                    background:var(--surface-alt);
                    border:1px solid var(--border);
                    color:var(--secondary);
                  ">

                {{ $tasks->count() }}

                Available Quests

            </span>

        </div>



        <div class="row g-4 mb-4">


            @forelse($tasks as $task)


                <div class="col-md-6 col-xl-4">


                    <div class="glass-card quest-card">


                        <!-- QUEST CONTENT -->

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



                            <p class="quest-desc">

                                {{ $task->description
                                    ?? 'Complete this learning objective to earn XP.'
                                }}

                            </p>


                        </div>



                        <!-- =================================================
                             QUEST FOOTER
                        ================================================== -->

                        <div class="d-flex align-items-center justify-content-between pt-3"
                             style="
                                border-top:
                                    1px solid var(--border);
                             ">


                            <span class="small text-capitalize"
                                  style="color:var(--text-muted);">

                                <i class="bi bi-bar-chart me-1"></i>

                                {{ $task->difficulty ?? $difficulty }}

                            </span>



                            <!-- =================================================
                                 REAL START QUEST FORM
                            ================================================== -->

                            <a href="{{ route('student.game.play', $task->id) }}"
                               class="action-btn-outline text-decoration-none">
                                <span>Start Quest</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>

                        </div>


                    </div>

                </div>


            @empty


                <div class="col-12">


                    <div class="glass-card text-center py-5">


                        <div style="
                            font-size:3rem;
                            color:var(--secondary);
                            margin-bottom:12px;
                        ">

                            {{ $themeIcon }}

                        </div>


                        <h4 class="fw-bold mb-2">

                            No Quests Pending

                        </h4>


                        <p class="text-muted">

                            You have completed all current
                            learning objectives.

                        </p>


                    </div>

                </div>


            @endforelse


        </div>



        <!-- =====================================================
             AI TOPICS
        ====================================================== -->

        <div class="section-header"
             id="topics">


            <div>

                <h2 class="section-title">

                    AI Recommendations

                </h2>


                <div class="section-subtitle">

                    Topics selected based on your interests

                </div>

            </div>

        </div>



        <div class="glass-card mb-4">


            <div class="d-flex align-items-center gap-3 mb-3">


                <div class="stat-icon"
                     style="
                        width:44px;
                        height:44px;
                        font-size:1.1rem;
                     ">

                    <i class="bi bi-stars"></i>

                </div>


                <div>

                    <div class="small text-muted text-uppercase fw-bold">

                        Gemini AI Intelligence

                    </div>


                    <h5 class="fw-bold mb-0">

                        Recommended for Your Learning Style

                        ({{ ucfirst($learningStyle) }})

                    </h5>

                </div>

            </div>



            <div class="d-flex flex-wrap gap-2 pt-2">


                @forelse($recommendedTopics as $topic)


                    <span class="topic-chip">

                        <i class="bi bi-lightbulb"
                           style="color:var(--secondary);">

                        </i>

                        {{ $topic }}

                    </span>


                @empty


                    @foreach($preference->interests as $item)


                        <span class="topic-chip">

                            <i class="bi bi-lightbulb"
                               style="color:var(--secondary);">

                            </i>

                            {{ $item }} Mastery

                        </span>


                    @endforeach


                @endforelse


            </div>

        </div>



        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <footer class="text-center pt-4 pb-2 small"
                style="
                    color:var(--text-muted);
                    border-top:
                        1px solid var(--border);
                ">


            <div>

                {{ $themeIcon }}

                <strong style="color:var(--secondary);">

                    EcoQuest

                </strong>

                •

                Gamified Learning Realm

            </div>


            <div class="mt-1">

                Powered dynamically by Gemini AI

                •

                Logged in as

                {{ $user->name }}

            </div>


        </footer>


    </div>



    <!-- =========================================================
         BOOTSTRAP JS
    ========================================================== -->

    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>


</body>

</html>