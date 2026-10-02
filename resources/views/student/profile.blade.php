<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Profile | EcoQuest</title>


    <!-- Bootstrap -->
    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">


    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">


    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    <style>
        /* =========================================================
           GLOBAL
        ========================================================= */

        :root {

            --green: #198754;
            --green-dark: #116b43;
            --green-light: #eaf7ef;

            --dark: #12251d;
            --dark-2: #263a32;

            --muted: #74817b;

            --background: #f4f7f5;

            --border: #e5ebe7;

            --danger: #dc3545;
            --danger-light: #fff0f1;

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

            font-family: 'Inter', sans-serif;

            color: var(--dark);

            background:

                radial-gradient(circle at 5% 10%,
                    rgba(25, 135, 84, 0.08),
                    transparent 25%),

                radial-gradient(circle at 95% 90%,
                    rgba(47, 191, 118, 0.07),
                    transparent 25%),

                var(--background);

        }


        /* =========================================================
           PAGE WRAPPER
        ========================================================= */

        .page-wrapper {

            max-width: 1120px;

            margin: 0 auto;

            padding: 28px 20px 70px;

        }


        /* =========================================================
           TOP NAV
        ========================================================= */

        .topbar {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 25px;

        }


        .brand {

            display: flex;

            align-items: center;

            gap: 11px;

            text-decoration: none;

            color: var(--dark);

        }


        .brand-icon {

            width: 43px;

            height: 43px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 13px;

            color: white;

            font-size: 20px;

            background:
                linear-gradient(135deg,
                    #116b43,
                    #35bd78);

            box-shadow:
                0 7px 18px rgba(25, 135, 84, 0.20);

        }


        .brand-text {

            font-size: 20px;

            font-weight: 800;

            letter-spacing: -0.4px;

        }


        .brand-text span {

            color: var(--green);

        }


        /* =========================================================
           DASHBOARD BUTTON
        ========================================================= */

        .dashboard-btn {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 10px 16px;

            border-radius: 11px;

            border: 1px solid var(--border);

            background: white;

            color: var(--dark-2);

            text-decoration: none;

            font-size: 13px;

            font-weight: 700;

            box-shadow:
                0 4px 12px rgba(20, 50, 35, 0.04);

            transition: all 0.25s ease;

        }


        .dashboard-btn:hover {

            color: var(--green);

            border-color: #bcdcca;

            transform: translateY(-2px);

            box-shadow:
                0 8px 20px rgba(20, 50, 35, 0.08);

        }


        /* =========================================================
           PAGE INTRO
        ========================================================= */

        .page-intro {

            margin-bottom: 22px;

        }


        .page-intro h1 {

            margin: 0;

            font-size: 28px;

            font-weight: 800;

            letter-spacing: -0.7px;

        }


        .page-intro p {

            margin: 7px 0 0;

            color: var(--muted);

            font-size: 14px;

        }


        /* =========================================================
           MAIN PROFILE CARD
        ========================================================= */

        .profile-card {

            position: relative;

            overflow: hidden;

            background: white;

            border: 1px solid var(--border);

            border-radius: 24px;

            box-shadow:
                0 15px 45px rgba(25, 60, 43, 0.08);

            margin-bottom: 25px;

        }


        /* =========================================================
           COVER
        ========================================================= */

        .cover {

            height: 190px;

            position: relative;

            overflow: hidden;

            background:
                linear-gradient(115deg,
                    #0f5d3b 0%,
                    #198754 45%,
                    #39c47b 75%,
                    #91e2b5 100%);

        }


        .cover::before {

            content: "";

            position: absolute;

            width: 420px;

            height: 420px;

            right: -100px;

            top: -250px;

            border-radius: 50%;

            border: 70px solid rgba(255, 255, 255, 0.08);

        }


        .cover::after {

            content: "";

            position: absolute;

            width: 320px;

            height: 320px;

            left: -160px;

            bottom: -260px;

            border-radius: 50%;

            border: 60px solid rgba(255, 255, 255, 0.08);

        }


        .cover-pattern {

            position: absolute;

            inset: 0;

            opacity: 0.18;

            background-image:
                radial-gradient(circle,
                    white 1.5px,
                    transparent 1.5px);

            background-size: 24px 24px;

            animation: patternMove 25s linear infinite;

        }


        @keyframes patternMove {

            from {
                transform: translate(0, 0);
            }

            to {
                transform: translate(48px, 48px);
            }

        }


        .cover-content {

            position: absolute;

            left: 38px;

            bottom: 30px;

            color: white;

            z-index: 2;

        }


        .cover-label {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 7px 11px;

            background: rgba(255, 255, 255, 0.14);

            border: 1px solid rgba(255, 255, 255, 0.20);

            border-radius: 9px;

            backdrop-filter: blur(8px);

            font-size: 11px;

            font-weight: 700;

            letter-spacing: 0.4px;

        }


        .cover-icons {

            position: absolute;

            right: 45px;

            top: 42px;

            display: flex;

            gap: 18px;

            color: rgba(255, 255, 255, 0.27);

            font-size: 28px;

        }


        .cover-icons i:nth-child(1) {

            animation: iconFloat 4s ease-in-out infinite;

        }


        .cover-icons i:nth-child(2) {

            animation: iconFloat 4s ease-in-out infinite 1s;

        }


        .cover-icons i:nth-child(3) {

            animation: iconFloat 4s ease-in-out infinite 2s;

        }


        @keyframes iconFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

        }


        /* =========================================================
           PROFILE MAIN
        ========================================================= */

        .profile-main {

            padding: 0 38px 32px;

        }


        /* =========================================================
           AVATAR AREA
        ========================================================= */

        .avatar-section {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            margin-top: -67px;

            position: relative;

            z-index: 5;

        }


        .avatar-area {

            display: flex;

            align-items: flex-end;

            gap: 20px;

        }


        .avatar-container {

            position: relative;

            width: 138px;

            height: 138px;

            flex-shrink: 0;

        }


        .profile-picture,
        .profile-letter {

            width: 138px;

            height: 138px;

            border-radius: 50%;

            border: 6px solid white;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.18);

        }


        .profile-picture {

            object-fit: cover;

            display: block;

        }


        .profile-letter {

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                linear-gradient(135deg,
                    #198754,
                    #3bc77d);

            color: white;

            font-size: 55px;

            font-weight: 800;

        }


        /* =========================================================
           CAMERA BUTTON
        ========================================================= */

        .camera-button {

            position: absolute;

            right: 1px;

            bottom: 5px;

            width: 45px;

            height: 45px;

            border-radius: 50%;

            border: 4px solid white;

            display: flex;

            align-items: center;

            justify-content: center;

            background: var(--green);

            color: white;

            cursor: pointer;

            box-shadow:
                0 5px 15px rgba(0, 0, 0, 0.18);

            transition: all 0.25s ease;

        }


        .camera-button:hover {

            background: var(--green-dark);

            transform: scale(1.08);

        }


        .camera-button i {

            font-size: 17px;

        }


        /* =========================================================
           PROFILE BASIC INFO
        ========================================================= */

        .profile-basic {

            padding-bottom: 5px;

        }


        .profile-name {

            font-size: 25px;

            font-weight: 800;

            margin-bottom: 4px;

            letter-spacing: -0.5px;

        }


        .profile-email {

            color: var(--muted);

            font-size: 13px;

            margin-bottom: 9px;

        }


        .role-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 11px;

            border-radius: 20px;

            background: var(--green-light);

            color: var(--green-dark);

            font-size: 10px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.5px;

        }


        /* =========================================================
           DELETE PHOTO
        ========================================================= */

        .photo-actions {

            margin-top: 16px;

            margin-left: 158px;

        }


        .delete-photo-btn {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            border: 1px solid #f2c5ca;

            background: var(--danger-light);

            color: var(--danger);

            border-radius: 9px;

            padding: 8px 13px;

            font-size: 11px;

            font-weight: 700;

            cursor: pointer;

            transition: all 0.2s ease;

        }


        .delete-photo-btn:hover {

            background: var(--danger);

            color: white;

            border-color: var(--danger);

        }


        /* =========================================================
           PROFILE STATS
        ========================================================= */

        .mini-stats {

            display: flex;

            gap: 10px;

        }


        .mini-stat {

            min-width: 105px;

            padding: 12px 14px;

            border: 1px solid var(--border);

            border-radius: 12px;

            background: #fafcfb;

        }


        .mini-stat-number {

            display: block;

            font-size: 17px;

            font-weight: 800;

            color: var(--green);

        }


        .mini-stat-label {

            display: block;

            font-size: 9px;

            text-transform: uppercase;

            letter-spacing: 0.4px;

            color: var(--muted);

            margin-top: 2px;

        }


        /* =========================================================
           ALERTS
        ========================================================= */

        .alert {

            border: none;

            border-radius: 13px;

            font-size: 13px;

            box-shadow:
                0 5px 15px rgba(0, 0, 0, 0.04);

        }


        /* =========================================================
           CONTENT GRID
        ========================================================= */

        .content-grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 25px;

        }


        .content-card {

            background: white;

            border: 1px solid var(--border);

            border-radius: 20px;

            padding: 27px;

            box-shadow:
                0 10px 30px rgba(25, 60, 43, 0.05);

            transition: all 0.25s ease;

        }


        .content-card:hover {

            transform: translateY(-2px);

            box-shadow:
                0 15px 35px rgba(25, 60, 43, 0.08);

        }


        .content-card.full {

            grid-column: span 2;

        }


        /* =========================================================
           SECTION TITLE
        ========================================================= */

        .section-heading {

            display: flex;

            align-items: center;

            gap: 11px;

            margin-bottom: 23px;

        }


        .section-icon {

            width: 40px;

            height: 40px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 11px;

            color: var(--green);

            background: var(--green-light);

            font-size: 18px;

        }


        .section-heading h2 {

            font-size: 16px;

            font-weight: 800;

            margin: 0;

        }


        .section-heading p {

            font-size: 11px;

            color: var(--muted);

            margin: 3px 0 0;

        }


        /* =========================================================
           FORM
        ========================================================= */

        .form-label {

            font-size: 12px;

            font-weight: 700;

            color: var(--dark-2);

            margin-bottom: 7px;

        }


        .form-control {

            border: 1px solid #dfe7e2;

            border-radius: 10px;

            padding: 11px 13px;

            font-size: 13px;

            color: var(--dark);

            transition: all 0.2s ease;

        }


        .form-control:focus {

            border-color: var(--green);

            box-shadow:
                0 0 0 4px rgba(25, 135, 84, 0.09);

        }


        .file-hint {

            display: block;

            color: var(--muted);

            font-size: 10px;

            margin-top: 7px;

        }


        /* =========================================================
           SAVE BUTTON
        ========================================================= */

        .save-button {

            border: none;

            border-radius: 11px;

            padding: 11px 20px;

            color: white;

            background:
                linear-gradient(135deg,
                    #198754,
                    #35bd78);

            font-size: 12px;

            font-weight: 800;

            box-shadow:
                0 7px 18px rgba(25, 135, 84, 0.20);

            transition: all 0.25s ease;

        }


        .save-button:hover {

            color: white;

            transform: translateY(-2px);

            box-shadow:
                0 10px 25px rgba(25, 135, 84, 0.27);

        }


        /* =========================================================
           INFO BOXES
        ========================================================= */

        .info-box {

            display: flex;

            gap: 12px;

            padding: 14px;

            border: 1px solid #edf1ee;

            border-radius: 12px;

            background: #fafcfb;

            transition: all 0.2s ease;

        }


        .info-box:hover {

            border-color: #cde3d5;

            background: #f7fbf8;

            transform: translateY(-1px);

        }


        .info-icon {

            width: 37px;

            height: 37px;

            min-width: 37px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

            background: var(--green-light);

            color: var(--green);

        }


        .info-label {

            font-size: 9px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            color: var(--muted);

            margin-bottom: 3px;

        }


        .info-value {

            font-size: 12px;

            font-weight: 700;

            color: var(--dark);

            line-height: 1.5;

        }


        /* =========================================================
           INTEREST TAGS
        ========================================================= */

        .interest-tag {

            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 7px 11px;

            border-radius: 20px;

            background: var(--green-light);

            color: var(--green-dark);

            font-size: 11px;

            font-weight: 700;

            margin: 3px;

        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .profile-footer {

            text-align: center;

            color: var(--muted);

            font-size: 11px;

            margin-top: 30px;

        }


        .profile-footer strong {

            color: var(--green);

        }

        /* =========================================================
   ACHIEVEMENTS / BADGES
========================================================= */

        .badges-grid {

            display: grid;

            grid-template-columns:
                repeat(auto-fill, minmax(210px, 1fr));

            gap: 15px;

        }


        .badge-card {

            position: relative;

            padding: 18px;

            border: 1px solid var(--border);

            border-radius: 16px;

            background:
                linear-gradient(145deg,
                    #ffffff,
                    #f8fcf9);

            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;

            overflow: hidden;

        }


        .badge-card::before {

            content: "";

            position: absolute;

            width: 90px;

            height: 90px;

            right: -35px;

            top: -35px;

            border-radius: 50%;

            background: rgba(25, 135, 84, 0.06);

        }


        .badge-card:hover {

            transform: translateY(-4px);

            border-color: #c9e3d3;

            box-shadow:
                0 12px 28px rgba(25, 60, 43, 0.09);

        }


        .badge-icon-box {

            width: 58px;

            height: 58px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 16px;

            background: #fff8df;

            border: 1px solid #f4e5a9;

            margin-bottom: 13px;

            position: relative;

            z-index: 1;

        }


        .badge-icon-box span {

            font-size: 30px;

            line-height: 1;

        }


        .badge-icon-box i {

            font-size: 27px;

            color: #e0a800;

        }


        .badge-name {

            font-size: 14px;

            font-weight: 800;

            color: var(--dark);

            margin-bottom: 5px;

        }


        .badge-description {

            font-size: 11px;

            color: var(--muted);

            line-height: 1.6;

            margin-bottom: 12px;

        }


        .badge-earned {

            display: inline-flex;

            align-items: center;

            gap: 5px;

            padding: 5px 9px;

            border-radius: 20px;

            background: var(--green-light);

            color: var(--green-dark);

            font-size: 9px;

            font-weight: 800;

            text-transform: uppercase;

            letter-spacing: 0.4px;

        }


        .no-badges {

            text-align: center;

            padding: 35px 20px;

            border: 1px dashed #d8e4dc;

            border-radius: 15px;

            background: #fafcfb;

        }


        .no-badges-icon {

            width: 58px;

            height: 58px;

            margin: 0 auto 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 50%;

            background: #fff8df;

            color: #d9a400;

            font-size: 25px;

        }


        .no-badges h3 {

            font-size: 14px;

            font-weight: 800;

            margin-bottom: 5px;

        }


        .no-badges p {

            color: var(--muted);

            font-size: 11px;

            margin: 0;

        }


        .badge-count {

            margin-left: auto;

            padding: 5px 10px;

            border-radius: 20px;

            background: var(--green-light);

            color: var(--green-dark);

            font-size: 10px;

            font-weight: 800;

        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 800px) {

            .content-grid {

                grid-template-columns: 1fr;

            }


            .content-card.full {

                grid-column: span 1;

            }


            .avatar-section {

                display: block;

            }


            .mini-stats {

                margin-top: 20px;

            }


            .photo-actions {

                margin-left: 0;

            }

        }


        @media (max-width: 576px) {

            .page-wrapper {

                padding: 18px 12px 50px;

            }


            .topbar {

                margin-bottom: 20px;

            }


            .brand-text {

                font-size: 17px;

            }


            .dashboard-btn span {

                display: none;

            }


            .cover {

                height: 150px;

            }


            .cover-content {

                left: 20px;

            }


            .cover-icons {

                right: 20px;

                font-size: 21px;

            }


            .profile-main {

                padding: 0 18px 25px;

            }


            .avatar-section {

                margin-top: -55px;

            }


            .avatar-container {

                width: 115px;

                height: 115px;

            }


            .profile-picture,
            .profile-letter {

                width: 115px;

                height: 115px;

            }


            .profile-letter {

                font-size: 44px;

            }


            .profile-name {

                font-size: 21px;

                margin-top: 14px;

            }


            .avatar-area {

                display: block;

            }


            .mini-stats {

                overflow-x: auto;

            }


            .content-card {

                padding: 20px;

            }

        }
    </style>

</head>


<body>


    <div class="page-wrapper">


        <!-- =====================================================
         TOP BAR
    ====================================================== -->

        <div class="topbar">


            <!-- Brand -->

            <a href="{{ route('student.dashboard') }}"
                class="brand">

                <div class="brand-icon">

                    <i class="bi bi-controller"></i>

                </div>

                <div class="brand-text">

                    Eco<span>Quest</span>

                </div>

            </a>


            <!-- Dashboard -->

            <a href="{{ route('student.dashboard') }}"
                class="dashboard-btn">

                <i class="bi bi-arrow-left"></i>

                <span>Back to Dashboard</span>

            </a>


        </div>



        <!-- =====================================================
         PAGE INTRO
    ====================================================== -->

        <div class="page-intro">

            <h1>My Profile</h1>

            <p>
                Manage your EcoQuest account and learning information.
            </p>

        </div>



        <!-- =====================================================
         SUCCESS MESSAGE
    ====================================================== -->

        @if(session('success'))

        <div class="alert alert-success mb-4">

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

        </div>

        @endif



        <!-- =====================================================
         ERROR MESSAGE
    ====================================================== -->

        @if($errors->any())

        <div class="alert alert-danger mb-4">

            <div class="fw-bold mb-1">

                <i class="bi bi-exclamation-circle-fill me-2"></i>

                Please check the following:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif



        <!-- =====================================================
         PROFILE HERO
    ====================================================== -->

        <div class="profile-card">


            <!-- COVER -->

            <div class="cover">

                <div class="cover-pattern"></div>


                <div class="cover-icons">

                    <i class="bi bi-lightbulb-fill"></i>

                    <i class="bi bi-trophy-fill"></i>

                    <i class="bi bi-stars"></i>

                </div>


                <div class="cover-content">

                    <div class="cover-label">

                        <i class="bi bi-controller"></i>

                        ECOQUEST LEARNER PROFILE

                    </div>

                </div>

            </div>



            <!-- PROFILE MAIN -->

            <div class="profile-main">


                <div class="avatar-section">


                    <div class="avatar-area">


                        <!-- AVATAR -->

                        <div class="avatar-container">


                            @if($user->profile_picture)

                            <img
                                id="profilePreview"
                                src="{{ asset('storage/' . $user->profile_picture) }}"
                                class="profile-picture"
                                alt="Profile Picture">

                            @else

                            <div
                                id="profileLetter"
                                class="profile-letter">

                                {{ strtoupper(substr($user->name, 0, 1)) }}

                            </div>

                            <img
                                id="profilePreview"
                                src=""
                                class="profile-picture d-none"
                                alt="Profile Preview">

                            @endif


                            <!-- CAMERA -->

                            <label
                                for="profile_picture"
                                class="camera-button"
                                title="Change profile picture">

                                <i class="bi bi-camera-fill"></i>

                            </label>


                            <!-- FILE INPUT -->

                            <input
                                type="file"
                                id="profile_picture"
                                name="profile_picture"
                                accept=".jpg,.jpeg,.png,.webp"
                                hidden
                                form="profileUpdateForm">

                        </div>



                        <!-- BASIC INFO -->

                        <div class="profile-basic">

                            <div class="profile-name">

                                {{ $user->name }}

                            </div>


                            <div class="profile-email">

                                <i class="bi bi-envelope me-1"></i>

                                {{ $user->email }}

                            </div>


                            <div class="role-badge">

                                <i class="bi bi-person-check-fill"></i>

                                {{ ucfirst($user->role ?? 'Student') }}

                            </div>

                        </div>

                    </div>



                    <!-- SMALL STATS -->

                    <div class="mini-stats">


                        <div class="mini-stat">

                            <span class="mini-stat-number">

                                <i class="bi bi-star-fill"></i> 01

                            </span>

                            <span class="mini-stat-label">

                                Level

                            </span>

                        </div>


                        <div class="mini-stat">

                            <span class="mini-stat-number">

                                <i class="bi bi-lightning-fill"></i> XP

                            </span>

                            <span class="mini-stat-label">

                                Progress

                            </span>

                        </div>


                    </div>


                </div>



                <!-- =================================================
                 DELETE BUTTON
            ================================================== -->

                @if($user->profile_picture)

                <div class="photo-actions">

                    <form
                        action="{{ route('student.profile.picture.delete') }}"
                        method="POST"
                        onsubmit="return confirm('Delete your profile picture?');">

                        @csrf

                        @method('DELETE')


                        <button
                            type="submit"
                            class="delete-photo-btn">

                            <i class="bi bi-trash3"></i>

                            Delete Picture

                        </button>

                    </form>

                </div>

                @endif


            </div>

        </div>



        <!-- =====================================================
         EDIT + INFORMATION
    ====================================================== -->

        <div class="content-grid">


            <!-- =================================================
             EDIT PROFILE
        ================================================== -->

            <div class="content-card">


                <div class="section-heading">

                    <div class="section-icon">

                        <i class="bi bi-pencil-square"></i>

                    </div>

                    <div>

                        <h2>Edit Profile</h2>

                        <p>
                            Keep your account details up to date.
                        </p>

                    </div>

                </div>



                <form
                    id="profileUpdateForm"
                    action="{{ route('student.profile.update') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    @method('PUT')


                    <!-- NAME -->

                    <div class="mb-3">

                        <label class="form-label">

                            Full Name

                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name', $user->name) }}"
                            required>

                    </div>



                    <!-- EMAIL -->

                    <div class="mb-3">

                        <label class="form-label">

                            Email Address

                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $user->email) }}"
                            required>

                    </div>


                    <!-- IMAGE NOTE -->

                    <div class="mb-3">

                        <div class="file-hint">

                            <i class="bi bi-camera me-1"></i>

                            To change your picture, click the camera button
                            on your profile photo.

                        </div>

                        <div class="file-hint">

                            JPG, JPEG, PNG or WEBP · Maximum 2MB

                        </div>

                    </div>


                    <!-- SAVE -->

                    <div class="text-end pt-2">

                        <button
                            type="submit"
                            class="save-button">

                            <i class="bi bi-check2-circle me-1"></i>

                            Save Changes

                        </button>

                    </div>


                </form>

            </div>



            <!-- =================================================
             ACCOUNT SUMMARY
        ================================================== -->

            <div class="content-card">


                <div class="section-heading">

                    <div class="section-icon">

                        <i class="bi bi-person-vcard"></i>

                    </div>

                    <div>

                        <h2>Account Summary</h2>

                        <p>
                            Your basic EcoQuest information.
                        </p>

                    </div>

                </div>



                <div class="info-box mb-3">

                    <div class="info-icon">

                        <i class="bi bi-person-fill"></i>

                    </div>

                    <div>

                        <div class="info-label">
                            Name
                        </div>

                        <div class="info-value">
                            {{ $user->name }}
                        </div>

                    </div>

                </div>



                <div class="info-box mb-3">

                    <div class="info-icon">

                        <i class="bi bi-envelope-fill"></i>

                    </div>

                    <div>

                        <div class="info-label">
                            Email
                        </div>

                        <div class="info-value">
                            {{ $user->email }}
                        </div>

                    </div>

                </div>



                <div class="info-box">

                    <div class="info-icon">

                        <i class="bi bi-shield-check"></i>

                    </div>

                    <div>

                        <div class="info-label">
                            Account Role
                        </div>

                        <div class="info-value">

                            {{ ucfirst($user->role ?? 'Student') }}

                        </div>

                    </div>

                </div>


            </div>



            <!-- =================================================
             ECOQUEST INFORMATION
        ================================================== -->

            <div class="content-card full">


                <div class="section-heading">

                    <div class="section-icon">

                        <i class="bi bi-stars"></i>

                    </div>

                    <div>

                        <h2>My EcoQuest Information</h2>

                        <p>
                            Learning information collected during registration.
                        </p>

                    </div>

                </div>



                <div class="row g-3">


                    <!-- EDUCATION -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-mortarboard-fill"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Education Level
                                </div>

                                <div class="info-value">

                                    {{ $preference->education_level ?? 'Not provided' }}

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- CLASS -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-journal-bookmark-fill"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Class / Semester
                                </div>

                                <div class="info-value">

                                    {{ $preference->class_semester ?? 'Not provided' }}

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- INSTITUTION -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-building-fill"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Institution
                                </div>

                                <div class="info-value">

                                    {{ $preference->institution ?? 'Not provided' }}

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- EXPERIENCE -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-bar-chart-fill"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Experience Level
                                </div>

                                <div class="info-value">

                                    {{ $preference->experience_level ?? 'Not provided' }}

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- GOAL -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-flag-fill"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Learning Goal
                                </div>

                                <div class="info-value">

                                    {{ $preference->learning_goal ?? 'Not provided' }}

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- PREFERENCE -->

                    <div class="col-md-6">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-sliders"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Experience Preference
                                </div>

                                <div class="info-value">

                                    {{ $preference->experience_preference ?? 'Not provided' }}

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- INTERESTS -->

                    <div class="col-12">

                        <div class="info-box">

                            <div class="info-icon">

                                <i class="bi bi-lightbulb-fill"></i>

                            </div>

                            <div>

                                <div class="info-label">
                                    Interests
                                </div>

                                <div class="info-value">

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

                                    Not provided

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>


                </div>

            </div>


        </div>


        <!-- =====================================================
     MY ACHIEVEMENTS
====================================================== -->

        <div class="content-card full">

            <div class="section-heading">

                <div class="section-icon">

                    <i class="bi bi-trophy-fill"></i>

                </div>

                <div>

                    <div class="d-flex align-items-center">

                        <h2>My Achievements</h2>

                        <span class="badge-count">

                            {{ $badges->count() }}

                            {{ $badges->count() == 1 ? 'Badge' : 'Badges' }}

                        </span>

                    </div>

                    <p>
                        Badges you have earned through your EcoQuest journey.
                    </p>

                </div>

            </div>


            @if($badges->count() > 0)

            <div class="badges-grid">

                @foreach($badges as $studentBadge)

                @php
                $badge = $studentBadge->badge;
                @endphp


                @if($badge)

                <div class="badge-card">

                    <!-- Badge Icon -->

                    <div class="badge-icon-box">

                        @if($badge->icon)

                        <span>
                            {{ $badge->icon }}
                        </span>

                        @else

                        <i class="bi bi-award-fill"></i>

                        @endif

                    </div>


                    <!-- Badge Name -->

                    <div class="badge-name">

                        {{ $badge->name }}

                    </div>


                    <!-- Description -->

                    <div class="badge-description">

                        {{ $badge->description ?? 'Achievement unlocked!' }}

                    </div>


                    <!-- Earned -->

                    <div class="badge-earned">

                        <i class="bi bi-check-circle-fill"></i>

                        Earned

                    </div>

                </div>

                @endif

                @endforeach

            </div>

            @else

            <div class="no-badges">

                <div class="no-badges-icon">

                    <i class="bi bi-trophy"></i>

                </div>

                <h3>
                    No badges yet
                </h3>

                <p>
                    Complete quests and earn XP to unlock your first achievement.
                </p>

            </div>

            @endif

        </div>



        <!-- FOOTER -->

        <div class="profile-footer">

            <i class="bi bi-controller me-1"></i>

            Learn · Play · Act · Verify · <strong>Earn</strong>

        </div>


    </div>



    <!-- =========================================================
     IMAGE PREVIEW
========================================================= -->

    <script>
        const pictureInput =
            document.getElementById('profile_picture');

        const profilePreview =
            document.getElementById('profilePreview');

        const profileLetter =
            document.getElementById('profileLetter');


        if (pictureInput) {

            pictureInput.addEventListener('change', function() {

                const file = this.files[0];


                if (!file) {

                    return;

                }


                /*
                 * Make sure the selected file is an image
                 */

                if (!file.type.startsWith('image/')) {

                    alert('Please select a valid image.');

                    this.value = '';

                    return;

                }


                /*
                 * Maximum 2MB
                 */

                if (file.size > 2 * 1024 * 1024) {

                    alert('Image size must be less than 2MB.');

                    this.value = '';

                    return;

                }


                /*
                 * Create temporary preview
                 */

                const reader = new FileReader();


                reader.onload = function(event) {

                    profilePreview.src =
                        event.target.result;


                    profilePreview.classList.remove('d-none');


                    if (profileLetter) {

                        profileLetter.classList.add('d-none');

                    }

                };


                reader.readAsDataURL(file);

            });

        }
    </script>


    <!-- Bootstrap JS -->

    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>


</body>

</html>