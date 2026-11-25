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
            position: relative;
        }

        /* ── Per-theme watermark patterns ───────────────────────────────── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 1;
        }

        /* ─── CHESS — classic 8×8 chessboard squares ─────────────────── */
        body.student-theme-chess::before {
            background-image:
                linear-gradient(45deg, rgba(255,255,255,0.025) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(255,255,255,0.025) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(255,255,255,0.025) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(255,255,255,0.025) 75%);
            background-size: 56px 56px;
            background-position: 0 0, 0 28px, 28px -28px, -28px 0px;
        }

        /* ─── MUSIC — horizontal staff lines ─────────────────────────── */
        body.student-theme-music::before {
            background-image:
                repeating-linear-gradient(
                    to bottom,
                    transparent 0,
                    transparent 16px,
                    rgba(142,68,173,0.10) 17px,
                    transparent 18px
                ),
                repeating-linear-gradient(
                    to bottom,
                    transparent 0,
                    transparent 85px,
                    rgba(142,68,173,0.055) 86px,
                    transparent 87px
                );
        }

        /* ─── TECHNOLOGY — dot-matrix circuit board ───────────────────── */
        body.student-theme-technology::before {
            background-image:
                radial-gradient(circle, rgba(0,229,255,0.18) 1px, transparent 1px),
                linear-gradient(rgba(0,229,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,229,255,0.04) 1px, transparent 1px);
            background-size: 32px 32px, 32px 32px, 32px 32px;
        }

        /* ─── SCIENCE — atom-ring concentric dots ─────────────────────── */
        body.student-theme-science::before {
            background-image:
                radial-gradient(circle at 50% 50%, rgba(52,152,219,0.09) 0, transparent 60%),
                radial-gradient(circle, rgba(22,160,133,0.09) 1px, transparent 1px),
                linear-gradient(rgba(52,152,219,0.045) 1px, transparent 1px),
                linear-gradient(90deg, rgba(52,152,219,0.045) 1px, transparent 1px);
            background-size: 100% 100%, 28px 28px, 28px 28px, 28px 28px;
        }

        /* ─── MATHEMATICS — graph-paper grid ─────────────────────────── */
        body.student-theme-mathematics::before {
            background-image:
                linear-gradient(rgba(57,73,171,0.10) 1px, transparent 1px),
                linear-gradient(90deg, rgba(57,73,171,0.10) 1px, transparent 1px),
                linear-gradient(rgba(57,73,171,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(57,73,171,0.04) 1px, transparent 1px);
            background-size: 80px 80px, 80px 80px, 20px 20px, 20px 20px;
        }

        /* ─── SPORTS — diagonal speed lines ──────────────────────────── */
        body.student-theme-sports::before {
            background-image:
                repeating-linear-gradient(
                    -55deg,
                    transparent,
                    transparent 28px,
                    rgba(255,87,34,0.07) 28px,
                    rgba(255,87,34,0.07) 29px
                ),
                repeating-linear-gradient(
                    35deg,
                    transparent,
                    transparent 50px,
                    rgba(30,136,229,0.05) 50px,
                    rgba(30,136,229,0.05) 51px
                );
        }

        /* ─── CREATIVE / ART — paint-stroke diagonal stripes ─────────── */
        body.student-theme-creative::before,
        body.student-theme-art::before {
            background-image:
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 22px,
                    rgba(232,67,147,0.06) 22px,
                    rgba(232,67,147,0.06) 23px
                ),
                radial-gradient(circle at 20% 30%, rgba(255,196,74,0.12), transparent 35%),
                radial-gradient(circle at 80% 70%, rgba(108,92,231,0.10), transparent 30%),
                radial-gradient(circle at 60% 15%, rgba(232,67,147,0.09), transparent 22%);
        }

        /* ─── READING — soft horizontal ruled-paper lines ─────────────── */
        body.student-theme-reading::before {
            background-image:
                repeating-linear-gradient(
                    to bottom,
                    transparent 0,
                    transparent 27px,
                    rgba(161,136,127,0.13) 28px,
                    transparent 29px
                ),
                linear-gradient(90deg, rgba(121,85,72,0.07) 1px, transparent 1px);
            background-size: 100% 100%, 60px 100%;
        }

        /* ─── BUSINESS — subtle diagonal stripe watermark ─────────────── */
        body.student-theme-business::before {
            background-image:
                repeating-linear-gradient(
                    -45deg,
                    transparent,
                    transparent 36px,
                    rgba(21,101,192,0.05) 36px,
                    rgba(21,101,192,0.05) 37px
                ),
                linear-gradient(rgba(66,165,245,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(66,165,245,0.03) 1px, transparent 1px);
            background-size: 100% 100%, 48px 48px, 48px 48px;
        }

        /* ─── GAMING — neon diamond pixel grid ────────────────────────── */
        body.student-theme-gaming::before {
            background-image:
                linear-gradient(45deg, rgba(224,64,251,0.08) 25%, transparent 25%),
                linear-gradient(-45deg, rgba(124,77,255,0.08) 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, rgba(224,64,251,0.06) 75%),
                linear-gradient(-45deg, transparent 75%, rgba(124,77,255,0.06) 75%),
                radial-gradient(circle, rgba(224,64,251,0.12) 1px, transparent 1px);
            background-size: 36px 36px, 36px 36px, 36px 36px, 36px 36px, 36px 36px;
            background-position: 0 0, 0 18px, 18px -18px, -18px 0px, 0 0;
        }

        /* ─── ENVIRONMENT — organic leaf-vein radial blobs ────────────── */
        body.student-theme-environment::before {
            background-image:
                radial-gradient(ellipse at 15% 50%, rgba(76,175,80,0.11) 0, transparent 45%),
                radial-gradient(ellipse at 85% 20%, rgba(27,94,32,0.09) 0, transparent 40%),
                radial-gradient(ellipse at 55% 85%, rgba(139,195,74,0.10) 0, transparent 38%),
                radial-gradient(circle, rgba(76,175,80,0.07) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 40px 40px;
        }

        /* ─── HISTORY — parchment cross-hatch ────────────────────────── */
        body.student-theme-history::before {
            background-image:
                repeating-linear-gradient(
                    0deg,
                    transparent,
                    transparent 34px,
                    rgba(109,76,65,0.08) 35px,
                    transparent 36px
                ),
                repeating-linear-gradient(
                    90deg,
                    transparent,
                    transparent 54px,
                    rgba(109,76,65,0.055) 55px,
                    transparent 56px
                ),
                repeating-linear-gradient(
                    -30deg,
                    transparent,
                    transparent 60px,
                    rgba(161,136,127,0.04) 61px,
                    transparent 62px
                );
        }

        /* ─── GEOGRAPHY — longitude / latitude map grid ───────────────── */
        body.student-theme-geography::before {
            background-image:
                linear-gradient(rgba(21,101,192,0.09) 1px, transparent 1px),
                linear-gradient(90deg, rgba(21,101,192,0.09) 1px, transparent 1px),
                radial-gradient(circle at 50% 50%, rgba(41,182,246,0.06) 0, transparent 70%);
            background-size: 60px 60px, 60px 60px, 100% 100%;
        }

        /* ─── PSYCHOLOGY — concentric thought-wave rings ──────────────── */
        body.student-theme-psychology::before {
            background-image:
                radial-gradient(ellipse at 30% 40%, rgba(106,27,154,0.10) 0, transparent 55%),
                radial-gradient(ellipse at 70% 65%, rgba(171,71,188,0.09) 0, transparent 48%),
                radial-gradient(circle, rgba(171,71,188,0.07) 1px, transparent 1px),
                radial-gradient(circle, rgba(206,147,216,0.05) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 44px 44px, 22px 22px;
            background-position: 0 0, 0 0, 0 0, 11px 11px;
        }

        /* ─── SELF-IMPROVEMENT — rising diagonal momentum lines ──────── */
        body.student-theme-self_improvement::before {
            background-image:
                repeating-linear-gradient(
                    60deg,
                    transparent,
                    transparent 24px,
                    rgba(230,81,0,0.07) 24px,
                    rgba(230,81,0,0.07) 25px
                ),
                repeating-linear-gradient(
                    -30deg,
                    transparent,
                    transparent 48px,
                    rgba(255,138,101,0.05) 48px,
                    rgba(255,138,101,0.05) 49px
                );
        }

        /* ─── GENERAL — subtle floating dot field ─────────────────────── */
        body.student-theme-general::before {
            background-image:
                radial-gradient(circle, rgba(108,92,231,0.10) 1px, transparent 1px),
                radial-gradient(circle at 30% 60%, rgba(0,206,201,0.08) 0, transparent 50%),
                radial-gradient(circle at 75% 25%, rgba(108,92,231,0.07) 0, transparent 45%);
            background-size: 36px 36px, 100% 100%, 100% 100%;
        }

        /* ──────────────────────────────────────────────────────────────────
           SIDEBAR
        ────────────────────────────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: 260px;
            padding: 24px 18px;
            background: linear-gradient(180deg, var(--surface), var(--page-bg));
            border-right: 1px solid var(--border);
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
            color: var(--text-main);
            text-decoration: none;
            padding: 5px 8px 22px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 18px;
        }

        .brand-icon {
            width: 44px; height: 44px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 13px;
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            font-size: 1.4rem;
            color: var(--page-bg);
            flex-shrink: 0;
            box-shadow: 0 8px 25px var(--glow);
        }

        .brand-title {
            font-size: 1.1rem;
            font-weight: 900;
            color: var(--text-main);
            font-family: var(--font-display);
            line-height: 1.1;
        }

        .brand-title span { color: var(--secondary); }

        .brand-subtitle {
            font-size: 0.68rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .nav-category {
            color: var(--text-muted);
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.3px;
            padding: 9px 12px;
            margin-bottom: 4px;
        }

        .side-link {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 13px;
            margin-bottom: 5px;
            border-radius: 10px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.87rem;
            font-weight: 600;
            transition: all 0.2s ease;
            border: 0;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .side-link i {
            width: 20px;
            font-size: 1rem;
            text-align: center;
        }

        .side-link:hover {
            color: var(--secondary);
            background: var(--surface-alt);
            transform: translateX(2px);
        }

        .sidebar .side-link.active,
        .sidebar .side-link.active:hover {
            color: var(--page-bg);
            background: linear-gradient(135deg, var(--secondary), var(--accent));
            box-shadow: 0 8px 20px var(--glow);
            font-weight: 700;
        }

        @if($isChess)
        .side-link.active,
        .side-link.active:hover {
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
            padding: 13px;
            margin-top: 15px;
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
            font-size: 0.8rem;
            font-weight: 800;
        }

        .identity-theme {
            color: var(--text-muted);
            font-size: 0.7rem;
            margin-top: 5px;
        }

        /* ── Logout ───────────────────────────────────────────────────── */
        .sidebar-bottom {
            margin-top: auto;
            padding-top: 15px;
        }

        .sidebar .logout-link { color: #ef4444 !important; }

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

        /* Mobile sidebar toggle button */
        .layout-menu-toggle {
            display: none;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            width: 42px; height: 42px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            color: var(--text-main);
            font-size: 1.1rem;
            cursor: pointer;
        }
        .layout-menu-toggle:hover,
        .layout-menu-toggle:focus-visible {
            border-color: var(--secondary);
            color: var(--secondary);
        }

        .sidebar-backdrop {
            display: none;
        }

        @media (max-width: 992px) {
            .sidebar {
                width: min(290px, 86vw);
                transform: translateX(-105%);
                transition: transform 0.25s ease;
                box-shadow: 14px 0 40px rgba(0,0,0,0.35);
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
                background: rgba(0,0,0,0.58);
                backdrop-filter: blur(2px);
                cursor: pointer;
            }
            .layout-menu-toggle {
                display: inline-flex;
            }
            .student-main {
                margin-left: 0;
            }
        }

        @media (max-width: 767px) {
            .theme-page { padding: 16px 14px 40px; }
        }

        /* Mobile-only top bar */
        .layout-mobile-bar {
            display: none;
            align-items: center;
            gap: 14px;
            padding: 10px 16px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .layout-mobile-brand {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            font-family: var(--font-display);
        }

        .layout-mobile-brand strong { color: var(--secondary); }

        .layout-mobile-brand-icon {
            margin-right: 6px;
        }

        @media (max-width: 992px) {
            .layout-mobile-bar {
                display: flex;
            }
        }
    </style>

    @yield('head')
</head>


<body class="student-theme student-theme-{{ $themeKey }}">

    {{-- Mobile sidebar backdrop --}}
    <button
        type="button"
        class="sidebar-backdrop"
        aria-label="Close navigation menu"
        data-layout-sidebar-close
    ></button>


    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}
    @include('student.partials.sidebar')


    {{-- =====================================================
         MOBILE-ONLY TOP BAR (hamburger + brand)
         Only visible on screens ≤ 992px
    ====================================================== --}}
    <div class="layout-mobile-bar" aria-hidden="true">
        <button
            type="button"
            class="layout-menu-toggle"
            aria-label="Open navigation menu"
            aria-controls="studentSidebar"
            aria-expanded="false"
            data-layout-sidebar-toggle
        >
            <i class="bi bi-list" aria-hidden="true"></i>
        </button>
        <span class="layout-mobile-brand">
            <span class="layout-mobile-brand-icon">{{ $themeIcon ?? '✦' }}</span>
            Eco<strong>Quest</strong>
        </span>
    </div>


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

    {{-- Mobile sidebar toggle JS (shared layout) --}}
    <script>
    let revalidatingStudentPage = false;
    const revalidateStudentPage = () => {
        if (revalidatingStudentPage) return;
        revalidatingStudentPage = true;
        window.location.reload();
    };

    window.addEventListener('pageshow', event => {
        if (event.persisted) {
            revalidateStudentPage();
        }
    });
    window.addEventListener('popstate', revalidateStudentPage);

    (() => {
        const sidebar   = document.getElementById('studentSidebar');
        const toggle    = document.querySelector('[data-layout-sidebar-toggle]');
        const backdrop  = document.querySelector('[data-layout-sidebar-close]');

        if (!sidebar || !toggle || !backdrop) return;

        function setMenuOpen(isOpen) {
            sidebar.classList.toggle('is-open', isOpen);
            backdrop.classList.toggle('is-visible', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
            toggle.setAttribute('aria-label', isOpen ? 'Close navigation menu' : 'Open navigation menu');
            document.body.style.overflow = isOpen ? 'hidden' : '';
            if (isOpen) { sidebar.querySelector('a, button')?.focus(); }
            else        { toggle.focus(); }
        }

        toggle.addEventListener('click', () => setMenuOpen(toggle.getAttribute('aria-expanded') !== 'true'));
        backdrop.addEventListener('click', () => setMenuOpen(false));
        sidebar.addEventListener('click', e => { if (e.target.closest('a')) setMenuOpen(false); });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') setMenuOpen(false);
        });
    })();
    </script>

    @yield('scripts')

</body>

</html>