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

        $generatedThemeIcons = [
            'music' => ['🎼', '🎹', '🎸', '🎤', '🎧', '♫'],
            'creative' => ['🎨', '🖌️', '🖍️', '✏️', '🖼️'],
        ];
        $generatedTheme = strtolower(trim((string) ($aiProfile['visual_theme'] ?? '')));
        $generatedThemeIcon = $aiProfile['theme_icon'] ?? null;

        if (
            $generatedTheme === $theme &&
            is_string($generatedThemeIcon) &&
            in_array($generatedThemeIcon, $generatedThemeIcons[$theme] ?? [], true)
        ) {
            $themeIcon = $generatedThemeIcon;
        } else {
            $themeIcon = $themeConfig['icon']
                ?? ($isChess ? '♞' : '✦');
        }

        $profileMatchesTheme = $generatedTheme === $theme;

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
            ($profileMatchesTheme ? ($aiProfile['rank_title'] ?? null) : null)
            ?? $themeConfig['gamified_rank']
            ?? 'Explorer';


        $dashboardTitle =
            ($profileMatchesTheme ? ($aiProfile['dashboard_title'] ?? null) : null)
            ?? $themeConfig['title']
            ?? 'Learning Arena';


        $tagline =
            ($profileMatchesTheme ? ($aiProfile['tagline'] ?? null) : null)
            ?? $themeConfig['subtitle']
            ?? 'Your personalized learning world';


        $motto =
            ($profileMatchesTheme ? ($aiProfile['motto'] ?? null) : null)
            ?? $themeConfig['gamified_motto']
            ?? 'Every step forward makes you stronger.';


        $welcomeMessage =
            ($profileMatchesTheme ? ($aiProfile['welcome_message'] ?? null) : null)
            ?? $themeConfig['welcome_message']
            ?? 'Welcome to your personalized learning world!';


        $dailyQuest =
            ($profileMatchesTheme ? ($aiProfile['daily_quest'] ?? null) : null)
            ?? $themeConfig['daily_quest']
            ?? "Complete today's learning challenge";


        $recommendedTopics =
            $aiProfile['recommended_topics']
            ?? [];

        $recommendedTopics = is_array($recommendedTopics)
            ? array_values(array_filter(
                $recommendedTopics,
                fn ($topic) => is_string($topic) && trim($topic) !== ''
            ))
            : [];


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

        body.theme-creative {
            background:
                radial-gradient(circle at 8% 12%, rgba(255, 190, 65, .25), transparent 22%),
                radial-gradient(circle at 92% 24%, rgba(232, 67, 147, .2), transparent 25%),
                radial-gradient(circle at 76% 88%, rgba(62, 177, 210, .18), transparent 25%),
                linear-gradient(135deg, #fff8fc, #fffaf0 55%, #f5fbff);
        }

        body.theme-music {
            background:
                radial-gradient(circle at 88% 12%, rgba(142, 68, 173, .16), transparent 24%),
                radial-gradient(circle at 12% 82%, rgba(214, 162, 232, .2), transparent 24%),
                repeating-linear-gradient(
                    to bottom,
                    transparent 0,
                    transparent 54px,
                    rgba(142, 68, 173, .055) 55px,
                    transparent 56px
                ),
                linear-gradient(135deg, #faf9ff, #f8f2fc 55%, #fffafd);
        }

        body.theme-creative .hero {
            border-color: rgba(232, 67, 147, .24);
            background:
                radial-gradient(circle at 85% 20%, rgba(255, 196, 74, .22), transparent 24%),
                linear-gradient(120deg, #fff 0%, #fff7e9 48%, #fff0f8 100%);
        }

        body.theme-music .hero {
            border-color: rgba(142, 68, 173, .24);
            background:
                repeating-linear-gradient(
                    to bottom,
                    transparent 0,
                    transparent 15px,
                    rgba(142, 68, 173, .09) 16px,
                    transparent 17px
                ),
                linear-gradient(120deg, #fff 0%, #f8f1fc 55%, #f2f7ff 100%);
        }

        .theme-artwork {
            position: relative;
            width: 190px;
            height: 126px;
            margin: 12px auto 0;
        }

        .art-palette {
            position: absolute;
            right: 24px;
            bottom: 4px;
            width: 112px;
            height: 98px;
            border: 1px solid rgba(120, 74, 54, .15);
            border-radius: 48% 52% 50% 42%;
            background: #f5d7b4;
            box-shadow: 0 12px 25px rgba(104, 66, 78, .16);
            transform: rotate(-12deg);
        }

        .art-palette::before {
            position: absolute;
            top: 31px;
            left: 36px;
            width: 25px;
            height: 23px;
            border-radius: 50%;
            background: #fff8ed;
            content: "";
        }

        .paint-dot {
            position: absolute;
            width: 23px;
            height: 23px;
            border: 2px solid rgba(255, 255, 255, .75);
            border-radius: 50%;
            box-shadow: 0 2px 6px rgba(53, 38, 53, .14);
        }

        .paint-dot.one { top: 12px; left: 23px; background: #ef476f; }
        .paint-dot.two { top: 29px; right: 15px; background: #ffd166; }
        .paint-dot.three { right: 17px; bottom: 14px; background: #06a77d; }
        .paint-dot.four { bottom: 11px; left: 24px; background: #4895ef; }
        .paint-dot.five { top: 11px; right: 43px; background: #9b5de5; }

        .art-brush {
            position: absolute;
            top: 0;
            left: 15px;
            z-index: 1;
            color: #8c4f72;
            font-size: 4.5rem;
            filter: drop-shadow(0 5px 8px rgba(100, 57, 78, .18));
            transform: rotate(-28deg);
        }

        .music-staff {
            position: absolute;
            top: 24px;
            right: 4px;
            width: 178px;
            height: 76px;
            background: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent 13px,
                rgba(102, 52, 126, .38) 14px,
                transparent 15px
            );
            transform: rotate(-5deg);
        }

        .music-note {
            position: absolute;
            color: #8e44ad;
            font-family: Georgia, serif;
            font-size: 3.6rem;
            line-height: 1;
            text-shadow: 0 6px 12px rgba(90, 43, 111, .16);
        }

        .music-note.first { top: 4px; left: 24px; transform: rotate(-8deg); }
        .music-note.second { top: 32px; left: 82px; color: #e84393; transform: rotate(7deg); }
        .music-note.third { top: -4px; right: 12px; color: #d29ae4; transform: rotate(12deg); }

        .theme-feature {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-top: 20px;
            padding: 22px 26px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: linear-gradient(115deg, var(--surface), var(--surface-alt));
            box-shadow: 0 10px 28px rgba(71, 46, 79, .1);
        }

        .theme-feature-copy { position: relative; z-index: 1; }
        .theme-feature-kicker {
            margin: 0 0 5px;
            color: var(--secondary);
            font-size: .72rem;
            font-weight: 900;
            letter-spacing: .13em;
            text-transform: uppercase;
        }
        .theme-feature-title {
            margin: 0 0 5px;
            color: var(--text-main);
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 800;
        }
        .theme-feature-description { margin: 0; color: var(--text-muted); }
        .theme-feature-decoration {
            flex: 0 0 auto;
            color: var(--secondary);
            font-size: 2.8rem;
            letter-spacing: .14em;
            opacity: .8;
        }

        body.theme-creative .theme-feature {
            border-color: rgba(232, 67, 147, .22);
            background: linear-gradient(110deg, #fff, #fff4e2 52%, #ffeff8);
        }

        body.theme-music .theme-feature {
            border-color: rgba(142, 68, 173, .22);
            background:
                repeating-linear-gradient(to bottom, transparent 0 17px, rgba(142,68,173,.07) 18px, transparent 19px),
                linear-gradient(110deg, #fff, #f7effb 58%, #f0f7ff);
        }

        body.theme-creative .interest-pill.active,
        body.theme-creative .primary-btn,
        body.theme-music .interest-pill.active,
        body.theme-music .primary-btn {
            color: #fff !important;
        }

        @media (max-width: 767px) {
            .theme-artwork { margin-top: 6px; transform: scale(.85); }
            .theme-feature { align-items: flex-start; padding: 18px; }
            .theme-feature-decoration { font-size: 2rem; }
        }


        a {
            text-decoration: none;
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

        .dashboard-menu-toggle {

            display: none;

            flex: 0 0 auto;

            align-items: center;

            justify-content: center;

            width: 42px;

            height: 42px;

            border: 1px solid var(--border);

            border-radius: 12px;

            background: var(--surface);

            color: var(--text-main);

            font-size: 1.1rem;

        }


        .dashboard-menu-toggle:hover,
        .dashboard-menu-toggle:focus-visible {

            border-color: var(--secondary);

            color: var(--secondary);

        }


        .sidebar-backdrop {

            display: none;

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

                display: flex;

                width: min(290px, 86vw);

                transform: translateX(-105%);

                transition: transform .25s ease;

                box-shadow: 14px 0 40px rgba(0, 0, 0, .35);

                z-index: 1101;

            }


            .sidebar.is-open {

                transform: translateX(0);

            }


            .sidebar-backdrop.is-visible {

                display: block;

                position: fixed;

                inset: 0;

                z-index: 1100;

                border: 0;

                background: rgba(0, 0, 0, .58);

                backdrop-filter: blur(2px);

            }


            .dashboard-menu-toggle {

                display: inline-flex;

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


            .hero .row {

                row-gap: 18px;

            }


            .topbar {

                gap: 10px;

                margin-bottom: 22px;

            }


            .topbar > div:first-child {

                min-width: 0;

            }


            .top-title,
            .top-subtitle {

                overflow-wrap: anywhere;

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


<body class="theme-{{ $themeKey }}">

    <button
        type="button"
        class="sidebar-backdrop"
        aria-label="Close navigation menu"
        data-dashboard-sidebar-close
    ></button>


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


            <div class="d-flex align-items-center gap-3">

                <button
                    type="button"
                    class="dashboard-menu-toggle"
                    aria-label="Open navigation menu"
                    aria-controls="studentSidebar"
                    aria-expanded="false"
                    data-dashboard-sidebar-toggle
                >
                    <i class="bi bi-list" aria-hidden="true"></i>
                </button>

                <div>

                    <div class="top-title">

                        {{ $dashboardTitle }}

                    </div>


                    <div class="top-subtitle">

                        {{ $tagline }}

                    </div>

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

                                $themeInterestAliases = [
                                    'music' => [
                                        'music', 'song', 'songs', 'singing', 'guitar',
                                        'piano', 'violin', 'drums', 'instrument',
                                        'instruments', 'songwriting',
                                    ],
                                    'creative' => [
                                        'art', 'art & design', 'drawing', 'painting',
                                        'design', 'craft', 'crafts', 'color theory',
                                        'colour theory', 'illustration',
                                    ],
                                ];
                                $active = $theme === $cleanInterest
                                    || in_array($cleanInterest, $themeInterestAliases[$theme] ?? [], true);

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



                <div class="col-lg-4 text-center">

                    <div class="hero-icon">

                        {{ $themeIcon }}

                    </div>

                    @if($themeKey === 'creative')
                        <div class="theme-artwork" aria-hidden="true">
                            <span class="art-brush">🖌️</span>
                            <div class="art-palette">
                                <span class="paint-dot one"></span>
                                <span class="paint-dot two"></span>
                                <span class="paint-dot three"></span>
                                <span class="paint-dot four"></span>
                                <span class="paint-dot five"></span>
                            </div>
                        </div>
                    @elseif($themeKey === 'music')
                        <div class="theme-artwork" aria-hidden="true">
                            <div class="music-staff">
                                <span class="music-note first">♫</span>
                                <span class="music-note second">♪</span>
                                <span class="music-note third">♬</span>
                            </div>
                        </div>
                    @endif


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

                            {{ $tasks->count() }}

                        </div>


                        <div class="stat-label">

                            Available Quests

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

        @elseif($themeKey === 'creative')
            <section class="theme-feature mt-4" aria-label="Art studio">
                <div class="theme-feature-copy">
                    <p class="theme-feature-kicker">Your creative corner</p>
                    <h2 class="theme-feature-title">The Art Studio</h2>
                    <p class="theme-feature-description">Mix ideas, explore color, and make every quest your canvas.</p>
                </div>
                <div class="theme-feature-decoration" aria-hidden="true">✎ ✿</div>
            </section>
        @elseif($themeKey === 'music')
            <section class="theme-feature mt-4" aria-label="Music studio">
                <div class="theme-feature-copy">
                    <p class="theme-feature-kicker">Find your rhythm</p>
                    <h2 class="theme-feature-title">The Music Room</h2>
                    <p class="theme-feature-description">Tune in, follow the beat, and turn each quest into a new composition.</p>
                </div>
                <div class="theme-feature-decoration" aria-hidden="true">♫ ♪ ♬</div>
            </section>
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

                        {{ $dailyMissionTitle }}

                    </div>


                    <div class="quest-description">

                        {{ $dailyMissionDescription }}

                    </div>


                </div>


                <div
                    class="col-lg-3 text-lg-end mt-3 mt-lg-0"
                >

                    @if ($dailyMissionUrl)
                        <a
                            href="{{ $dailyMissionUrl }}"
                            class="primary-btn"
                        >

                            {{ $dailyMissionAction }}

                            <i class="bi bi-arrow-right"></i>

                        </a>
                    @elseif ($projectChallenge?->review_status === 'pending_review')
                        <span class="badge text-bg-warning">Waiting for admin approval</span>
                    @endif

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

                    Complete 25 quests at each level to earn a certificate for every interest.
                    Track each interest's stage in My Progress.

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
                @if ($pendingReviewCount > 0)
                    · {{ $pendingReviewCount }} Awaiting approval
                @endif

            </span>


        </div>

        @if ($pendingReviewCount > 0)
            <div class="alert alert-warning mt-3 mb-0" role="status">
                <strong>Your quests are being reviewed.</strong>
                You can see them below, but they will be playable after an admin approves them.
            </div>
        @endif



        <div class="row g-4">


            @forelse($learningQuests as $task)


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
                                @if ($task->review_status === 'pending_review')
                                    <span class="badge text-bg-warning">Awaiting approval</span>
                                @endif


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



                            @if ($task->review_status === 'approved')
                                <a
                                    href="{{ route('student.game.play', $task->id) }}"
                                    class="start-btn"
                                >

                                    Start Quest

                                    <i class="bi bi-chevron-right"></i>

                                </a>
                            @else
                                <span class="small text-warning-emphasis">Admin review in progress</span>
                            @endif


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


                        @if ($pendingReviewCount > 0)
                            <h4 class="mt-3">Your quests are being reviewed</h4>
                            <p style="color:var(--text-muted);">
                                Your game-based quests will appear here after an admin approves them.
                            </p>
                        @elseif ($questGenerationFailed)
                            <h4 class="mt-3">No new quests could be loaded</h4>
                            <p style="color:var(--text-muted);">
                                We couldn’t get a new interest-matched quest right now. Check your connection and try again.
                            </p>
                            <a href="{{ route('student.dashboard') }}" class="start-btn">
                                Try again <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @elseif ($playableGames->isEmpty())
                            <h4 class="mt-3">No playable games for these interests yet</h4>
                            <p style="color:var(--text-muted);">
                                There isn’t an enabled game for your registered interests right now.
                            </p>
                        @else
                            <h4 class="mt-3">Preparing your next quests</h4>
                            <p style="color:var(--text-muted);">
                                Your completed quests are saved. New interest-matched quests will appear here when they are ready.
                            </p>
                            <a href="{{ route('student.dashboard') }}" class="start-btn">
                                Check for new quests <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        @endif


                    </div>


                </div>


            @endforelse


        </div>


        <!-- <div class="section-header" id="project-challenges">
            <div>
                <div class="section-title">Project Challenges</div>
                <div class="section-subtitle">
                    Real-world projects created for your interests and reviewed by an admin
                </div>
            </div>
        </div> 

        @if ($projectChallenge)
            @php
                $latestProjectSubmission = $projectChallenge->submissions->first();
            @endphp
            @if ($projectChallenge->review_status === 'pending_review')
                <div class="glass-card text-center py-4 mb-5">
                    <h3 class="h4">Your project challenge is under admin review</h3>
                    <p class="mb-0" style="color:var(--text-muted);">
                        The project brief will appear here after it has been approved.
                    </p>
                </div>
            @else
            <div class="glass-card mb-5">
                <div class="quest-top mb-3">
                    <span class="quest-category">{{ $projectChallenge->interest }} project</span>
                    <span class="quest-xp">
                        <i class="bi bi-lightning-charge-fill"></i>
                        +{{ $projectChallenge->xp }} XP after approval
                    </span>
                </div>
                <h3 class="quest-title">{{ $projectChallenge->title }}</h3>
                <p class="quest-description-small">{{ $projectChallenge->project_brief }}</p>
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <h5 class="fw-bold">Project goals</h5>
                        <ul>
                            @foreach ($projectChallenge->objectives as $objective)
                                <li class="mb-2">{{ $objective }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold">What to deliver</h5>
                        <ul>
                            @foreach ($projectChallenge->deliverables as $deliverable)
                                <li class="mb-2">{{ $deliverable }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top">
                    <span class="small text-capitalize" style="color:var(--text-muted);">
                        {{ $projectChallenge->difficulty }} ·
                        @if ($projectChallenge->review_status === 'pending_review')
                            Waiting for admin approval
                        @elseif ($latestProjectSubmission?->status === 'submitted')
                            Submission under review
                        @elseif ($latestProjectSubmission?->status === 'revision_requested')
                            Changes requested
                        @else
                            Ready to start
                        @endif
                    </span>
                    @if ($projectChallenge->review_status === 'approved')
                        <a href="{{ route('student.project-challenges.show', $projectChallenge) }}" class="start-btn">
                            {{ $latestProjectSubmission?->status === 'revision_requested' ? 'Update submission' : ($latestProjectSubmission ? 'View submission' : 'View project brief') }}
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    @endif
                </div>
                @if ($projectChallenge->review_status === 'pending_review')
                    <p class="small mt-3 mb-0" style="color:var(--text-muted);">
                        Your project brief is being checked before it is published. This is separate from your game quests.
                    </p>
                @elseif ($latestProjectSubmission?->status === 'revision_requested')
                    <p class="small mt-3 mb-0" style="color:var(--text-muted);">
                        Admin feedback: {{ $latestProjectSubmission->review_note }}
                    </p>
                @elseif ($latestProjectSubmission?->status === 'submitted')
                    <p class="small mt-3 mb-0" style="color:var(--text-muted);">
                        Your project link is waiting for an admin to review it.
                    </p>
                @endif
            </div>
            @endif
        @elseif ($projectChallengeGenerationFailed)
            <div class="glass-card text-center py-4 mb-5">
                <h4>Project challenge is not available yet</h4>
                <p style="color:var(--text-muted);">
                    We could not generate a project challenge right now. Please try again later.
                </p>
            </div>
        @else
            <div class="glass-card text-center py-4 mb-5">
                <h4>Your project challenge is being prepared</h4>
                <p class="mb-0" style="color:var(--text-muted);">
                    An AI-generated project based on your interests will appear here after admin review.
                </p>
            </div>
        @endif
-->

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

            <a href="{{ route('student.learning-materials.index') }}" class="start-btn">
                <i class="bi bi-book-half"></i>
                Browse Learning Materials
            </a>


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

                    @if(!empty($interests))
                        @foreach($interests as $item)
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

            <hr class="my-4" style="border-color:var(--border);">

            <div class="fw-bold mb-2">
                <i class="bi bi-chat-dots me-2"></i>
                Ask your learning assistant
            </div>
            <p class="small mb-3" style="color:var(--text-muted);">
                Get help exploring your recommended topics and choosing what to learn next.
            </p>
            <div
                id="recommendationChatMessages"
                class="d-flex flex-column gap-2 mb-3"
                aria-live="polite"
                aria-label="Learning assistant conversation"
                style="max-height:260px;overflow-y:auto;"
            >
                <div class="topic-chip align-self-start">
                    Hi! Ask me about your recommended topics or learning interests.
                </div>
            </div>
            <form id="recommendationChatForm" class="d-flex gap-2">
                @csrf
                <label class="visually-hidden" for="recommendationChatInput">
                    Message the learning assistant
                </label>
                <input
                    id="recommendationChatInput"
                    class="form-control"
                    type="text"
                    maxlength="1000"
                    placeholder="Ask a learning question..."
                    autocomplete="off"
                    required
                >
                <button id="recommendationChatSend" class="btn btn-primary" type="submit">
                    Send
                </button>
            </form>
            <div id="recommendationChatStatus" class="small mt-2" role="status" style="color:var(--text-muted);"></div>
            <div id="recommendationChatError" class="small mt-2" role="status" style="color:#fca5a5;"></div>


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

    <script>
        (() => {
            const sidebar = document.getElementById('studentSidebar');
            const toggle = document.querySelector('[data-dashboard-sidebar-toggle]');
            const backdrop = document.querySelector('[data-dashboard-sidebar-close]');

            if (!sidebar || !toggle || !backdrop) return;

            function setMenuOpen(isOpen) {
                sidebar.classList.toggle('is-open', isOpen);
                backdrop.classList.toggle('is-visible', isOpen);
                toggle.setAttribute('aria-expanded', String(isOpen));
                toggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
                document.body.style.overflow = isOpen ? 'hidden' : '';

                if (isOpen) {
                    sidebar.querySelector('a, button')?.focus();
                } else {
                    toggle.focus();
                }
            }

            toggle.addEventListener('click', () => {
                setMenuOpen(toggle.getAttribute('aria-expanded') !== 'true');
            });
            backdrop.addEventListener('click', () => setMenuOpen(false));
            sidebar.addEventListener('click', (event) => {
                if (event.target.closest('a')) setMenuOpen(false);
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
                    setMenuOpen(false);
                }
            });
        })();

        (() => {
            const form = document.getElementById('recommendationChatForm');
            const input = document.getElementById('recommendationChatInput');
            const messagesElement = document.getElementById('recommendationChatMessages');
            const sendButton = document.getElementById('recommendationChatSend');
            const statusElement = document.getElementById('recommendationChatStatus');
            const errorElement = document.getElementById('recommendationChatError');
            const history = [];

            function appendMessage(role, content) {
                const message = document.createElement('div');
                message.className = 'topic-chip align-self-' + (role === 'user' ? 'end' : 'start');
                message.textContent = content;
                messagesElement.appendChild(message);
                messagesElement.scrollTop = messagesElement.scrollHeight;
            }

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const message = input.value.trim();
                if (!message) return;

                errorElement.textContent = '';
                statusElement.textContent = 'Sending your question…';
                appendMessage('user', message);
                input.value = '';
                sendButton.disabled = true;
                sendButton.setAttribute('aria-busy', 'true');

                try {
                    const response = await fetch(@json(route('student.ai-recommendations.chat')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message, history })
                    });
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'The learning assistant could not reply.');
                    }

                    appendMessage('assistant', data.reply);
                    statusElement.textContent = data.mode === 'offline'
                        ? 'Gemini is unreachable right now; the built-in learning helper is answering.'
                        : '';
                    history.push(
                        { role: 'user', content: message },
                        { role: 'assistant', content: data.reply }
                    );
                    if (history.length > 8) history.splice(0, history.length - 8);
                } catch (error) {
                    errorElement.textContent = error.message || 'The learning assistant could not reply. Please try again.';
                    statusElement.textContent = '';
                } finally {
                    sendButton.disabled = false;
                    sendButton.removeAttribute('aria-busy');
                    input.focus();
                }
            });
        })();
    </script>

</body>

</html>