{{-- =========================================================
     EcoQuest — Student Profile Page (AI Theme)
     ========================================================= --}}

@extends('student.layouts.app')

@section('title', 'My Profile | EcoQuest')

@section('head')
<style>
    /* ── Page ─────────────────────────────────────────────── */
    .profile-page {
        padding: 28px 30px 60px;
        min-height: 100vh;
        background: var(--page-bg);
    }

    /* ── Profile card ─────────────────────────────────────── */
    .profile-hero-card {
        position: relative;
        overflow: hidden;
        border-radius: 22px;
        border: 1px solid var(--border);
        margin-bottom: 26px;
        background: var(--surface);
    }

    /* Cover banner */
    .profile-cover {
        height: 180px;
        position: relative;
        overflow: hidden;
        background: linear-gradient(120deg, var(--primary), var(--secondary));
    }

    .profile-cover::before {
        content: '';
        position: absolute; inset: 0;
        opacity: 0.15;
        background-image: radial-gradient(circle, white 1.5px, transparent 1.5px);
        background-size: 22px 22px;
        animation: coverMove 25s linear infinite;
    }

    @keyframes coverMove {
        from { transform: translate(0,0); }
        to   { transform: translate(44px,44px); }
    }

    .cover-decoration {
        position: absolute;
        right: 40px; top: 38px;
        display: flex; gap: 20px;
        font-size: 2rem;
        color: rgba(255,255,255,0.22);
    }

    .cover-decoration span {
        animation: iconFloat 4s ease-in-out infinite;
    }

    .cover-decoration span:nth-child(2) { animation-delay: 1.2s; }
    .cover-decoration span:nth-child(3) { animation-delay: 2.4s; }

    @keyframes iconFloat {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-10px); }
    }

    .cover-label {
        position: absolute;
        left: 34px; bottom: 24px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 10px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.22);
        backdrop-filter: blur(8px);
        font-size: 0.72rem;
        font-weight: 700;
        color: white;
        letter-spacing: 0.5px;
        font-family: var(--font-display);
    }

    /* Avatar + basic info */
    .profile-body {
        padding: 0 34px 30px;
    }

    .avatar-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: -64px;
        position: relative;
        z-index: 5;
        margin-bottom: 20px;
    }

    .avatar-left { display: flex; align-items: flex-end; gap: 18px; }

    .avatar-wrap {
        position: relative;
        width: 130px; height: 130px;
        flex-shrink: 0;
    }

    .profile-pic, .profile-letter {
        width: 130px; height: 130px;
        border-radius: 50%;
        border: 5px solid var(--surface);
        box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    }

    .profile-pic { object-fit: cover; display: block; }

    .profile-letter {
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #000;
        font-size: 52px; font-weight: 800;
        font-family: var(--font-display);
    }

    .camera-btn {
        position: absolute;
        right: 2px; bottom: 4px;
        width: 42px; height: 42px;
        border-radius: 50%;
        border: 3px solid var(--surface);
        display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #000;
        font-size: 1rem;
        cursor: pointer;
        box-shadow: 0 4px 12px var(--glow);
        transition: all 0.2s ease;
    }

    .camera-btn:hover { transform: scale(1.1); }

    .profile-name {
        font-family: var(--font-display);
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 4px;
    }

    .profile-email {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .role-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 13px;
        border-radius: 50px;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: #000;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .mini-stats-row {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }

    .mini-stat {
        min-width: 100px;
        padding: 11px 14px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--surface-alt);
    }

    .mini-stat-num {
        display: block;
        font-family: var(--font-display);
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--secondary);
    }

    .mini-stat-label {
        display: block;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        margin-top: 2px;
    }

    /* ── Content cards ────────────────────────────────────── */
    .profile-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .profile-grid .full { grid-column: span 2; }

    .pcard {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 26px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .pcard:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.25);
    }

    .pcard-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
    }

    .pcard-icon {
        width: 40px; height: 40px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        flex-shrink: 0;
        box-shadow: 0 4px 10px var(--glow);
    }

    .pcard-title {
        font-family: var(--font-display);
        font-size: 1rem;
        font-weight: 800;
        color: var(--secondary);
        margin: 0;
    }

    .pcard-subtitle {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin: 3px 0 0;
    }

    /* ── Form ─────────────────────────────────────────────── */
    .form-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 7px;
    }

    .form-control {
        background: var(--surface-alt) !important;
        border: 1px solid var(--border) !important;
        border-radius: 10px !important;
        padding: 11px 14px !important;
        font-size: 0.88rem !important;
        color: var(--text-main) !important;
        transition: all 0.2s ease !important;
    }

    .form-control:focus {
        border-color: var(--secondary) !important;
        box-shadow: 0 0 0 3px var(--glow) !important;
        background: var(--surface) !important;
    }

    .form-control::placeholder { color: var(--text-muted) !important; }

    .file-hint {
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-top: 4px;
    }

    /* ── Info rows ────────────────────────────────────────── */
    .info-row {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px 0;
        border-bottom: 1px solid var(--border);
    }

    .info-row:last-child { border-bottom: 0; }

    .info-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.95rem;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        color: var(--secondary);
        flex-shrink: 0;
    }

    .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Interest tags */
    .interest-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 50px;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--secondary);
        margin: 3px 4px 3px 0;
    }

    /* Badge grid */
    .badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 16px;
    }

    .badge-mini-card {
        text-align: center;
        padding: 20px 14px;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        border-radius: 16px;
        transition: transform 0.2s ease;
    }

    .badge-mini-card:hover {
        transform: translateY(-4px);
        border-color: var(--secondary);
        box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    }

    .badge-mini-icon {
        font-size: 2.4rem;
        margin-bottom: 10px;
    }

    .badge-mini-name {
        font-family: var(--font-display);
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
    }

    .badge-mini-date {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Alerts */
    .alert-themed-success {
        background: rgba(34,197,94,0.10);
        border: 1px solid rgba(34,197,94,0.22);
        border-radius: 12px;
        color: #4ade80;
        padding: 12px 16px;
        font-size: 0.88rem;
        margin-bottom: 20px;
    }

    .alert-themed-danger {
        background: rgba(239,68,68,0.10);
        border: 1px solid rgba(239,68,68,0.22);
        border-radius: 12px;
        color: #fca5a5;
        padding: 12px 16px;
        font-size: 0.88rem;
        margin-bottom: 20px;
    }

    /* Footer */
    .profile-footer {
        text-align: center;
        padding: 26px 0 0;
        font-size: 0.78rem;
        color: var(--text-muted);
        border-top: 1px solid var(--border);
        margin-top: 22px;
    }

    /* ── Responsive ───────────────────────────────────────── */
    @media (max-width: 900px) {
        .profile-grid { grid-template-columns: 1fr; }
        .profile-grid .full { grid-column: span 1; }
        .mini-stats-row { flex-wrap: wrap; }
    }

    @media (max-width: 600px) {
        .profile-page { padding: 14px 14px 40px; }
        .profile-cover { height: 140px; }
        .profile-body { padding: 0 18px 22px; }
        .avatar-left { flex-direction: column; align-items: flex-start; }
        .profile-name { font-size: 1.2rem; margin-top: 12px; }
    }
</style>
@endsection

@section('content')
<div class="profile-page">

    {{-- ── TOPBAR ────────────────────────────────────────────────────── --}}
    <div class="theme-topbar mb-0" style="margin: -28px -30px 28px; padding: 18px 30px;">
        <div>
            <div class="theme-topbar-title">
                <i class="bi bi-person-circle me-2" style="color: var(--secondary);"></i>
                My Profile
            </div>
            <div class="theme-topbar-subtitle">
                Manage your EcoQuest account
            </div>
        </div>

        <a href="{{ route('student.dashboard') }}" class="btn-theme text-decoration-none d-inline-flex align-items-center gap-2">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
    </div>

    {{-- ── ALERTS ────────────────────────────────────────────────────── --}}
    @if(session('success'))
        <div class="alert-themed-success">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert-themed-danger">
            <div class="fw-bold mb-1">
                <i class="bi bi-exclamation-circle-fill me-2"></i>Please check the following:
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── PROFILE HERO ──────────────────────────────────────────────── --}}
    <div class="profile-hero-card">

        {{-- Cover --}}
        <div class="profile-cover">
            <div class="cover-decoration">
                <span>{{ $themeIcon ?? '✦' }}</span>
                <span>🏆</span>
                <span>⭐</span>
            </div>
            <div class="cover-label">
                {{ $themeIcon ?? '✦' }} {{ $rankTitle ?? 'Explorer' }} — EcoQuest Learner
            </div>
        </div>

        {{-- Body --}}
        <div class="profile-body">

            <div class="avatar-row">
                <div class="avatar-left">

                    {{-- Avatar --}}
                    <div class="avatar-wrap">
                        @if($user->profile_picture)
                            <img id="profilePreview"
                                 src="{{ asset('storage/' . $user->profile_picture) }}"
                                 class="profile-pic" alt="Profile Picture">
                        @else
                            <div id="profileLetter" class="profile-letter">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="profilePreview" src="" class="profile-pic d-none" alt="Preview">
                        @endif

                        <label for="profile_picture" class="camera-btn" title="Change photo">
                            <i class="bi bi-camera-fill"></i>
                        </label>

                        <input type="file" id="profile_picture" name="profile_picture"
                               accept=".jpg,.jpeg,.png,.webp" hidden form="profileUpdateForm">
                    </div>

                    {{-- Info --}}
                    <div style="padding-bottom: 6px;">
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-email">
                            <i class="bi bi-envelope me-1"></i>{{ $user->email }}
                        </div>
                        <div class="role-pill">
                            <i class="bi bi-person-check-fill"></i>
                            {{ ucfirst($user->role ?? 'Student') }}
                        </div>
                    </div>

                </div>

                {{-- Mini stats --}}
                <div class="mini-stats-row">
                    <div class="mini-stat">
                        <span class="mini-stat-num">
                            {{ $progress->level ?? 1 }}
                        </span>
                        <span class="mini-stat-label">Level</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-num">
                            {{ $progress->total_xp ?? 0 }}
                        </span>
                        <span class="mini-stat-label">{{ $labels['exp_label'] ?? 'Total XP' }}</span>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-num">
                            {{ $progress->completed_tasks ?? 0 }}
                        </span>
                        <span class="mini-stat-label">Quests Done</span>
                    </div>
                </div>
            </div>

            {{-- Delete photo --}}
            @if($user->profile_picture)
                <div style="margin-left: 148px; margin-top: -4px; margin-bottom: 8px;">
                    <form action="{{ route('student.profile.picture.delete') }}" method="POST"
                          onsubmit="return confirm('Delete your profile picture?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="display:inline-flex;align-items:center;gap:6px;background:rgba(239,68,68,0.12);border:1px solid rgba(239,68,68,0.25);color:#fca5a5;border-radius:9px;padding:7px 13px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">
                            <i class="bi bi-trash3"></i> Delete Picture
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>


    {{-- ── CONTENT GRID ──────────────────────────────────────────────── --}}
    <div class="profile-grid">

        {{-- ── EDIT FORM ──────────────────────────────────────────────── --}}
        <div class="pcard">
            <div class="pcard-header">
                <div class="pcard-icon">✏️</div>
                <div>
                    <div class="pcard-title">Edit Profile</div>
                    <div class="pcard-subtitle">Keep your account details up to date</div>
                </div>
            </div>

            <form id="profileUpdateForm"
                  action="{{ route('student.profile.update') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-4">
                    <div class="file-hint">
                        <i class="bi bi-camera me-1"></i>
                        To change your picture, click the camera button on your avatar.
                    </div>
                    <div class="file-hint mt-1">JPG, PNG or WEBP — max 2MB</div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn-theme d-inline-flex align-items-center gap-2">
                        <i class="bi bi-check2-circle"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>


        {{-- ── ACCOUNT SUMMARY ────────────────────────────────────────── --}}
        <div class="pcard">
            <div class="pcard-header">
                <div class="pcard-icon">🪪</div>
                <div>
                    <div class="pcard-title">Account Summary</div>
                    <div class="pcard-subtitle">Your basic EcoQuest information</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-person-fill"></i></div>
                <div>
                    <div class="info-label">Name</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="info-label">Account Role</div>
                    <div class="info-value">{{ ucfirst($user->role ?? 'Student') }}</div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon" style="font-size:1.1rem;">{{ $themeIcon ?? '✦' }}</div>
                <div>
                    <div class="info-label">Active Theme</div>
                    <div class="info-value">
                        {{ ucfirst($theme ?? 'General') }} — <span style="color:var(--secondary);">{{ $rankTitle ?? 'Explorer' }}</span>
                    </div>
                </div>
            </div>
        </div>


        {{-- ── LEARNING INFORMATION ────────────────────────────────────── --}}
        <div class="pcard full">
            <div class="pcard-header">
                <div class="pcard-icon">⭐</div>
                <div>
                    <div class="pcard-title">My EcoQuest Information</div>
                    <div class="pcard-subtitle">Learning data collected during registration</div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-mortarboard-fill"></i></div>
                        <div>
                            <div class="info-label">Education Level</div>
                            <div class="info-value">{{ $preference->education_level ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                        <div>
                            <div class="info-label">Class / Semester</div>
                            <div class="info-value">{{ $preference->class_semester ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-building-fill"></i></div>
                        <div>
                            <div class="info-label">Institution</div>
                            <div class="info-value">{{ $preference->institution ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-bar-chart-fill"></i></div>
                        <div>
                            <div class="info-label">Experience Level</div>
                            <div class="info-value">{{ $preference->experience_level ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-flag-fill"></i></div>
                        <div>
                            <div class="info-label">Learning Goal</div>
                            <div class="info-value">{{ $preference->learning_goal ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-sliders"></i></div>
                        <div>
                            <div class="info-label">Experience Preference</div>
                            <div class="info-value">{{ $preference->experience_preference ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Interests --}}
                <div class="col-12">
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-lightbulb-fill"></i></div>
                        <div>
                            <div class="info-label">Interests</div>
                            <div class="info-value" style="margin-top: 6px;">
                                @if($preference && $preference->interests)
                                    @if(is_array($preference->interests))
                                        @foreach($preference->interests as $interest)
                                            <span class="interest-tag">
                                                <i class="bi bi-check-circle-fill"></i>
                                                {{ $interest }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="interest-tag">
                                            <i class="bi bi-check-circle-fill"></i>
                                            {{ $preference->interests }}
                                        </span>
                                    @endif
                                @else
                                    <span style="color:var(--text-muted);">Not provided</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ── ACHIEVEMENTS ────────────────────────────────────────────── --}}
        <div class="pcard full">
            <div class="pcard-header">
                <div class="pcard-icon">🏆</div>
                <div>
                    <div class="pcard-title">
                        My Achievements
                        <span style="margin-left:8px;font-size:0.72rem;padding:3px 10px;border-radius:50px;background:linear-gradient(135deg,var(--primary),var(--secondary));color:#000;font-weight:800;">
                            {{ $badges->count() }} {{ Str::plural('Badge', $badges->count()) }}
                        </span>
                    </div>
                    <div class="pcard-subtitle">Badges earned through your EcoQuest journey</div>
                </div>
            </div>

            @if($badges->count() > 0)
                <div class="badges-grid">
                    @foreach($badges as $studentBadge)
                        @php $badge = $studentBadge->badge; @endphp
                        @if($badge)
                            <div class="badge-mini-card">
                                <div class="badge-mini-icon">{{ $badge->icon ?? '🏅' }}</div>
                                <div class="badge-mini-name">{{ $badge->name }}</div>
                                <div class="badge-mini-date">
                                    {{ $badge->description ?? 'Achievement unlocked!' }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:40px 20px;">
                    <div style="font-size:3rem;margin-bottom:12px;opacity:0.5;">🏆</div>
                    <div style="font-weight:700;color:var(--text-main);margin-bottom:6px;">No badges yet</div>
                    <div style="font-size:0.82rem;color:var(--text-muted);">Complete quests and earn XP to unlock badges!</div>
                </div>
            @endif
        </div>

    </div>


    {{-- ── FOOTER ────────────────────────────────────────────────────── --}}
    <div class="profile-footer">
        {{ $themeIcon ?? '✦' }} Learn · Play · Act · <strong>Earn</strong>
        &nbsp;•&nbsp; Powered by Gemini AI
    </div>

</div>
@endsection

@section('scripts')
<script>
    const pictureInput   = document.getElementById('profile_picture');
    const profilePreview = document.getElementById('profilePreview');
    const profileLetter  = document.getElementById('profileLetter');

    if (pictureInput) {
        pictureInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image.');
                this.value = '';
                return;
            }
            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must be less than 2MB.');
                this.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                profilePreview.src = e.target.result;
                profilePreview.classList.remove('d-none');
                if (profileLetter) profileLetter.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });
    }
</script>
@endsection