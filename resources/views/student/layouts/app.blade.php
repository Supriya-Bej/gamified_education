<!DOCTYPE html>
<html lang="en">

@php
    $student = auth('student')->user();
    $studentThemeService = app(\App\Services\StudentThemeService::class);

    $themeData = $student
        ? $studentThemeService->getTheme($student)
        : [
            'themeKey'   => 'general',
            'themeConfig' => config('learning_themes.general'),
            'theme'       => 'General',
            'themeIcon'   => '✦',
            'rankTitle'   => 'Explorer',
            'difficulty'  => 'beginner',
            'labels'      => [],
            'aiProfile'   => [],
            'interests'   => [],
        ];

    $themeKey    = $themeData['themeKey'];
    $themeConfig = $themeData['themeConfig'];
    $theme       = $themeData['theme'];
    $themeIcon   = $themeData['themeIcon'];
    $rankTitle   = $themeData['rankTitle'];
    $difficulty  = $themeData['difficulty'];
    $labels      = $themeData['labels'];
    $aiProfile   = $themeData['aiProfile'] ?? [];
    $interests   = $themeData['interests'] ?? [];

    $isChess = ($themeKey === 'chess');

    // ── Color tokens ──────────────────────────────────────────────────────────
    $primary    = $themeConfig['primary']     ?? ($aiProfile['primary_color']   ?? '#6C5CE7');
    $secondary  = $themeConfig['secondary']   ?? ($aiProfile['secondary_color'] ?? '#A29BFE');
    $accent     = $themeConfig['accent']      ?? ($aiProfile['accent_color']    ?? '#A29BFE');
    $pageBg     = $themeConfig['background']  ?? '#0F172A';
    $surface    = $themeConfig['surface']     ?? '#1E293B';
    $surfaceAlt = $themeConfig['surface_alt'] ?? '#27354D';
    $border     = $themeConfig['border']      ?? 'rgba(255,255,255,0.08)';
    $textMain   = $themeConfig['text']        ?? '#F8FAFC';
    $textMuted  = $themeConfig['muted']       ?? '#94A3B8';
    $glow       = $themeConfig['glow']        ?? 'rgba(108,92,231,0.4)';
    $fontDisplay = $themeConfig['font_display'] ?? 'Plus Jakarta Sans';
    $decorations = $themeConfig['decorations'] ?? ['✦','★','◆'];
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EcoQuest')</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    @if(!empty($themeConfig['font_url']))
        <link href="{{ $themeConfig['font_url'] }}" rel="stylesheet">
    @endif

    <style>
        /* ──────────────────────────────────────────────────────────────────
           CSS CUSTOM PROPERTIES — driven entirely by the active AI theme
        ────────────────────────────────────────────────────────────────── */
        :root {
            --primary:      {{ $primary }};
            --secondary:    {{ $secondary }};
            --accent:       {{ $accent }};
            --page-bg:      {{ $pageBg }};
            --surface:      {{ $surface }};
            --surface-alt:  {{ $surfaceAlt }};
            --border:       {{ $border }};
            --text-main:    {{ $textMain }};
            --text-muted:   {{ $textMuted }};
            --glow:         {{ $glow }};
            --font-display: '{{ $fontDisplay }}', serif, sans-serif;
            --font-body:    'Plus Jakarta Sans', system-ui, sans-serif;

            /* semantic shortcuts */
            --theme-primary:    {{ $primary }};
            --theme-secondary:  {{ $secondary }};
            --theme-background: {{ $pageBg }};
            --theme-surface:    {{ $surface }};
            --theme-surface-alt: {{ $surfaceAlt }};
            --theme-text:       {{ $textMain }};
            --theme-muted:      {{ $textMuted }};
            --theme-border:     {{ $border }};
            --theme-glow:       {{ $glow }};
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            min-height: 100vh;
            background: var(--page-bg);
            color: var(--text-main);
            font-family: var(--font-body);
            overflow-x: hidden;
        }

        /* ── Chessboard pattern watermark ─────────────────────────────── */
        @if($isChess)
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            background-image:
                linear-gradient(45deg, rgba(255,255,255,0.018) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(255,255,255,0.018) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.018) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.018) 75%);
            background-size: 48px 48px;
            background-position: 0 0, 0 24px, 24px -24px, -24px 0px;
        }
        @endif

        /* ──────────────────────────────────────────────────────────────────
           SIDEBAR
        ────────────────────────────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 260px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 22px 16px;
            overflow-y: auto;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-main);
            text-decoration: none;
            padding: 8px 10px 22px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 22px;
        }

        .brand-icon {
            width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            font-size: 22px; flex-shrink: 0;
            box-shadow: 0 6px 18px var(--glow);
        }

        .brand-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-main);
            font-family: var(--font-display);
            line-height: 1.1;
        }

        .brand-title span { color: var(--secondary); }

        .brand-subtitle {
            font-size: 0.68rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        .nav-category {
            color: var(--text-muted);
            font-size: 0.67rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 0 10px;
            margin-bottom: 8px;
        }

        .side-link {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 11px 13px;
            margin-bottom: 4px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 0;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .side-link i {
            width: 20px;
            font-size: 1.05rem;
            text-align: center;
        }

        .side-link:hover {
            color: var(--text-main);
            background: rgba(255,255,255,0.06);
            transform: translateX(2px);
        }

        .side-link.active {
            color: {{ $isChess ? '#000' : 'var(--text-main)' }};
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            box-shadow: 0 6px 18px var(--glow);
            font-weight: 700;
        }

        @if($isChess)
        .side-link.active {
            color: #0A0A0C !important;
            background: #FFFFFF !important;
            box-shadow: 0 4px 16px rgba(255,255,255,0.25) !important;
        }
        @endif

        /* ── Identity box ─────────────────────────────────────────────── */
        .identity-box {
            background: var(--surface-alt);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 13px 14px;
            margin-top: 8px;
        }

        .identity-top {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .identity-icon { font-size: 1.1rem; }

        .identity-rank {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--secondary);
        }

        .identity-theme {
            color: var(--text-muted);
            font-size: 0.7rem;
        }

        /* ── Logout ───────────────────────────────────────────────────── */
        .sidebar-bottom {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .logout-link { color: #fca5a5 !important; }
        .logout-link:hover { background: rgba(239,68,68,0.15) !important; color: #fff !important; }

        /* ──────────────────────────────────────────────────────────────────
           MAIN CONTENT AREA
        ────────────────────────────────────────────────────────────────── */
        .student-main {
            margin-left: 260px;
            min-height: 100vh;
            padding: 0;
            position: relative;
            z-index: 1;
        }

        /* ──────────────────────────────────────────────────────────────────
           SHARED THEME HELPER CLASSES
           (available to all child pages via @section('head') or inline)
        ────────────────────────────────────────────────────────────────── */

        /* Cards */
        .theme-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .theme-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.3);
        }

        /* Hero / header banner */
        .theme-hero {
            background: linear-gradient(135deg, var(--surface), var(--surface-alt));
            border: 1px solid var(--border);
            border-radius: 22px;
            padding: 32px 36px;
            position: relative;
            overflow: hidden;
        }
        .theme-hero::before {
            content: '{{ $themeIcon }}';
            position: absolute;
            right: 32px; top: 8px;
            font-size: 110px;
            opacity: 0.05;
            pointer-events: none;
        }

        /* Page wrapper */
        .theme-page {
            min-height: 100vh;
            background: var(--page-bg);
            padding: 28px 30px 50px;
        }

        /* Stat cards */
        .theme-stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 22px 20px;
            transition: transform 0.2s ease;
        }
        .theme-stat-card:hover { transform: translateY(-3px); }

        .theme-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--secondary);
            font-family: var(--font-display);
        }

        .theme-stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .theme-stat-icon {
            width: 48px; height: 48px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0.9;
        }

        /* Buttons */
        .btn-theme {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: {{ $isChess ? '#000' : '#fff' }};
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 9px 18px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px var(--glow);
        }
        .btn-theme:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px var(--glow);
            color: {{ $isChess ? '#000' : '#fff' }};
        }

        .btn-theme-outline {
            background: transparent;
            color: var(--secondary);
            border: 1px solid var(--secondary);
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 9px 18px;
            transition: all 0.2s ease;
        }
        .btn-theme-outline:hover {
            background: var(--secondary);
            color: #000;
        }

        /* Badges / pills */
        .theme-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            font-size: 0.72rem;
            font-weight: 700;
            color: {{ $isChess ? '#000' : '#fff' }};
        }

        /* XP progress bar */
        .theme-progress-track {
            height: 10px;
            background: var(--surface-alt);
            border-radius: 50px;
            overflow: hidden;
        }
        .theme-progress-fill {
            height: 100%;
            border-radius: 50px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            transition: width 0.7s ease;
        }

        /* Section title */
        .theme-section-title {
            font-family: var(--font-display);
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--secondary);
            letter-spacing: 0.5px;
        }

        /* Topbar */
        .theme-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 30px;
            border-bottom: 1px solid var(--border);
            background: var(--surface);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .theme-topbar-title {
            font-family: var(--font-display);
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-main);
        }

        .theme-topbar-subtitle {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .theme-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: {{ $isChess ? '#000' : '#fff' }};
            font-weight: 800;
            font-size: 1rem;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--surface); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* ──────────────────────────────────────────────────────────────────
           RESPONSIVE
        ────────────────────────────────────────────────────────────────── */
        @media (max-width: 991px) {
            .sidebar   { width: 230px; }
            .student-main { margin-left: 230px; }
        }

        @media (max-width: 767px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .student-main { margin-left: 0; }
            .theme-page { padding: 16px 14px 40px; }
        }
    </style>

    @yield('head')
</head>


<body class="student-theme student-theme-{{ $themeKey }}">


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    @include('student.partials.sidebar')


    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}
    <main class="student-main">
        @yield('content')
    </main>


    {{-- =====================================================
         BOOTSTRAP JS
    ====================================================== --}}
    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>

    @yield('scripts')

</body>

</html>