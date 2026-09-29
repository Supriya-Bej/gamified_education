<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>EcoQuest | Learn. Play. Practice. Grow.</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/bootstrap-icons/bootstrap-icons.css')}}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">


    <style>
        * {
            box-sizing: border-box;
        }


        html {
            scroll-behavior: smooth;
        }


        body {
            margin: 0;
            font-family: "Inter", sans-serif;
            color: #17231f;
            background: #f7fbf8;
            overflow-x: hidden;
        }


        /* =====================================================
           COMMON
        ====================================================== */

        .section-padding {
            padding: 100px 0;
        }


        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 7px 14px;

            border-radius: 30px;

            background: #eaf8e6;
            color: #3b9e32;

            font-size: 12px;
            font-weight: 700;

            margin-bottom: 15px;
        }


        .section-title {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.15;

            color: #13241e;
        }


        .section-title span {
            color: #49b53d;
        }


        .section-description {
            color: #687570;
            line-height: 1.8;
            max-width: 650px;
        }



        /* =====================================================
           NAVBAR
        ====================================================== */

        .navbar {
            position: absolute;

            top: 0;
            left: 0;

            width: 100%;

            z-index: 100;

            padding: 22px 0;

            background: transparent;
        }


        .navbar-brand {
            color: white !important;

            font-size: 27px;
            font-weight: 800;

            letter-spacing: -1px;
        }


        .navbar-brand i {
            color: #80e95b;
        }


        .navbar-brand span {
            color: #8ee960;
        }


        .nav-link {
            color: rgba(255, 255, 255, 0.82) !important;

            font-size: 14px;
            font-weight: 500;

            margin: 0 8px;

            transition: 0.3s;
        }


        .nav-link:hover {
            color: #8df05e !important;
        }


        .login-nav {
            border: 1px solid rgba(255, 255, 255, 0.5);

            padding: 9px 21px !important;

            border-radius: 9px;
        }


        .login-nav:hover {
            background: white;

            color: #193c2d !important;
        }


        .register-nav {
            background: #62c84c;

            color: white !important;

            padding: 10px 22px !important;

            border-radius: 9px;

            margin-left: 5px;
        }


        .register-nav:hover {
            background: #4caf3b;

            color: white !important;

            transform: translateY(-2px);
        }



        /* =====================================================
           HERO
        ====================================================== */

        .hero {
            min-height: 100vh;

            position: relative;

            display: flex;

            align-items: center;

            overflow: hidden;

            background:
                linear-gradient(115deg,
                    #06221d 0%,
                    #0b3b2d 45%,
                    #123e35 100%);
        }


        /* Background Video */

        .hero-video {
            position: absolute;

            inset: 0;

            width: 100%;
            height: 100%;

            object-fit: cover;

            opacity: 0.22;

            z-index: 0;
        }


        .hero-overlay {
            position: absolute;

            inset: 0;

            background:
                linear-gradient(90deg,
                    rgba(3, 27, 23, 0.96),
                    rgba(5, 42, 33, 0.75),
                    rgba(4, 37, 31, 0.78));

            z-index: 1;
        }


        /* Animated blobs */

        .hero-blob {
            position: absolute;

            border-radius: 50%;

            filter: blur(2px);

            z-index: 2;

            opacity: 0.18;

            animation: blobFloat 9s ease-in-out infinite;
        }


        .blob-1 {
            width: 350px;
            height: 350px;

            background: #73e15c;

            top: -150px;
            right: 15%;
        }


        .blob-2 {
            width: 250px;
            height: 250px;

            background: #21c5ff;

            bottom: -100px;
            right: 40%;

            animation-delay: 2s;
        }


        .blob-3 {
            width: 180px;
            height: 180px;

            background: #d7ed42;

            top: 45%;
            right: -50px;

            animation-delay: 4s;
        }


        @keyframes blobFloat {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            50% {
                transform: translate(25px, -35px) scale(1.1);
            }
        }



        /* =====================================================
           HERO CONTENT
        ====================================================== */

        .hero-content {
            position: relative;

            z-index: 5;

            padding-top: 80px;
        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            padding: 8px 15px;

            border-radius: 30px;

            background: rgba(120, 230, 90, 0.1);

            border: 1px solid rgba(120, 230, 90, 0.25);

            color: #94f56d;

            font-size: 13px;

            font-weight: 600;

            margin-bottom: 20px;

            animation: fadeDown 0.8s ease;
        }


        .hero-title {

            color: white;

            font-size: clamp(48px, 6vw, 78px);

            font-weight: 800;

            letter-spacing: -3px;

            line-height: 1.02;

            max-width: 760px;

            margin-bottom: 25px;

            animation: fadeUp 0.9s ease;
        }


        .hero-title span {
            color: #83e95d;
        }


        .hero-description {

            color: rgba(255, 255, 255, 0.72);

            max-width: 650px;

            font-size: 17px;

            line-height: 1.8;

            margin-bottom: 32px;

            animation: fadeUp 1s ease;
        }


        .hero-buttons {

            display: flex;

            gap: 12px;

            flex-wrap: wrap;

            animation: fadeUp 1.1s ease;
        }


        .btn-start {

            background: linear-gradient(135deg,
                    #43b93b,
                    #83dc52);

            color: white;

            border: none;

            padding: 14px 27px;

            border-radius: 11px;

            font-weight: 700;

            text-decoration: none;

            box-shadow:
                0 12px 30px rgba(75, 190, 63, 0.25);

            transition: 0.3s;
        }


        .btn-start:hover {

            color: white;

            transform: translateY(-3px);

            box-shadow:
                0 17px 35px rgba(75, 190, 63, 0.35);
        }


        .btn-explore {

            color: white;

            border: 1px solid rgba(255, 255, 255, 0.35);

            padding: 13px 25px;

            border-radius: 11px;

            text-decoration: none;

            font-weight: 600;

            transition: 0.3s;
        }


        .btn-explore:hover {

            background: white;

            color: #173d30;
        }



        /* =====================================================
           HERO STATS
        ====================================================== */

        .hero-stats {

            display: flex;

            gap: 35px;

            margin-top: 55px;

            animation: fadeUp 1.3s ease;
        }


        .hero-stat strong {

            display: block;

            color: white;

            font-size: 25px;
        }


        .hero-stat span {

            color: rgba(255, 255, 255, 0.52);

            font-size: 12px;
        }


        .stat-divider {

            width: 1px;

            background: rgba(255, 255, 255, 0.15);
        }



        /* =====================================================
           FLOATING LEARNING CARD
        ====================================================== */

        .floating-card {

            position: absolute;

            z-index: 6;

            right: 7%;

            bottom: 16%;

            width: 270px;

            padding: 20px;

            background: rgba(255, 255, 255, 0.94);

            border-radius: 20px;

            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);

            animation:
                floatingCard 4s ease-in-out infinite;
        }


        @keyframes floatingCard {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }


        .floating-card-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 15px;
        }


        .floating-card-title {

            font-weight: 700;

            font-size: 14px;
        }


        .xp {

            color: #4cae3d;

            font-weight: 700;

            font-size: 12px;
        }


        .mini-course {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 10px;

            border-radius: 10px;

            background: #f3f8f4;

            margin-bottom: 8px;
        }


        .mini-icon {

            width: 34px;
            height: 34px;

            border-radius: 9px;

            display: flex;

            align-items: center;
            justify-content: center;

            background: #e1f5dc;

            color: #43a936;
        }


        .mini-course small {

            color: #74817c;

            display: block;

            font-size: 10px;
        }


        .mini-course strong {

            font-size: 11px;
        }



        @keyframes fadeUp {

            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        @keyframes fadeDown {

            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }



        /* =====================================================
           SUBJECTS
        ====================================================== */

        .subjects-section {

            background: #ffffff;
        }


        .subject-card {

            position: relative;

            padding: 25px;

            border: 1px solid #e6eeea;

            border-radius: 18px;

            background: white;

            height: 100%;

            transition: 0.35s;

            overflow: hidden;
        }


        .subject-card::after {

            content: "";

            position: absolute;

            width: 100px;
            height: 100px;

            border-radius: 50%;

            background: #eaf8e6;

            right: -45px;
            bottom: -45px;

            transition: 0.3s;
        }


        .subject-card:hover {

            transform: translateY(-8px);

            border-color: #bfe7b7;

            box-shadow:
                0 18px 45px rgba(31, 70, 45, 0.09);
        }


        .subject-card:hover::after {

            transform: scale(1.5);
        }


        .subject-icon {

            width: 52px;
            height: 52px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 14px;

            font-size: 23px;

            background: #edf9e9;

            margin-bottom: 18px;
        }


        .subject-card h5 {

            font-weight: 700;

            font-size: 16px;

            margin-bottom: 8px;
        }


        .subject-card p {

            color: #78847f;

            font-size: 12px;

            line-height: 1.7;

            margin: 0;
        }



        /* =====================================================
           HOW IT WORKS
        ====================================================== */

        .how-section {

            background: #f2f8f4;
        }


        .process-card {

            position: relative;

            text-align: center;

            padding: 30px 20px;
        }


        .process-number {

            width: 58px;
            height: 58px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin: 0 auto 18px;

            border-radius: 50%;

            background: white;

            border: 2px solid #bce6b5;

            color: #4cae3d;

            font-size: 19px;

            font-weight: 800;

            box-shadow: 0 10px 25px rgba(51, 120, 60, 0.08);
        }


        .process-card h5 {

            font-size: 16px;

            font-weight: 700;

            margin-bottom: 8px;
        }


        .process-card p {

            color: #74817c;

            font-size: 12px;

            line-height: 1.7;
        }


        .process-line {

            position: absolute;

            top: 59px;

            left: 68%;

            width: 65%;

            height: 1px;

            border-top: 1px dashed #a9d6a3;
        }



        /* =====================================================
           GAMIFICATION SECTION
        ====================================================== */

        .game-section {

            background: white;

            overflow: hidden;
        }


        .game-preview {

            position: relative;

            min-height: 430px;

            border-radius: 25px;

            background:
                linear-gradient(135deg,
                    #0b332a,
                    #176044);

            padding: 35px;

            overflow: hidden;

            box-shadow: 0 25px 60px rgba(22, 67, 47, 0.15);
        }


        .game-preview::before {

            content: "";

            position: absolute;

            width: 300px;
            height: 300px;

            border-radius: 50%;

            background: #73db5a;

            opacity: 0.12;

            top: -130px;
            right: -70px;

            animation: blobFloat 7s infinite;
        }


        .game-label {

            display: inline-block;

            color: #8be96c;

            font-size: 12px;

            font-weight: 700;

            margin-bottom: 10px;
        }


        .game-title {

            color: white;

            font-size: 32px;

            font-weight: 800;

            max-width: 430px;
        }


        .game-subtitle {

            color: rgba(255, 255, 255, 0.6);

            font-size: 13px;

            line-height: 1.7;

            max-width: 430px;
        }


        .game-options {

            margin-top: 30px;

            max-width: 450px;
        }


        .game-option {

            display: flex;

            justify-content: space-between;

            align-items: center;

            background: rgba(255, 255, 255, 0.08);

            border: 1px solid rgba(255, 255, 255, 0.1);

            color: white;

            padding: 14px 17px;

            border-radius: 10px;

            margin-bottom: 9px;

            font-size: 12px;

            transition: 0.25s;
        }


        .game-option:hover {

            background: rgba(255, 255, 255, 0.15);

            transform: translateX(5px);
        }


        .game-option i {

            color: #8bea69;
        }



        /* =====================================================
           PERSONALIZATION
        ====================================================== */

        .personal-section {

            background: #f5faf6;
        }


        .personal-card {

            padding: 35px;

            border-radius: 23px;

            background: white;

            border: 1px solid #e4eee7;

            box-shadow: 0 15px 40px rgba(30, 70, 48, 0.06);
        }


        .profile-preview {

            display: flex;

            align-items: center;

            gap: 15px;

            padding-bottom: 25px;

            border-bottom: 1px solid #edf1ee;

            margin-bottom: 25px;
        }


        .avatar {

            width: 55px;
            height: 55px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #e7f7e2;

            color: #4bae3d;

            font-size: 22px;
        }


        .profile-preview h6 {

            font-weight: 700;

            margin: 0;
        }


        .profile-preview small {

            color: #7d8984;

            font-size: 11px;
        }


        .recommendation {

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 14px;

            border-radius: 12px;

            background: #f5f9f6;

            margin-bottom: 10px;
        }


        .recommendation-icon {

            width: 40px;
            height: 40px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #e3f5de;

            color: #4aaa3b;
        }


        .recommendation strong {

            font-size: 12px;

            display: block;
        }


        .recommendation small {

            color: #7b8682;

            font-size: 10px;
        }



        /* =====================================================
           ACHIEVEMENT
        ====================================================== */

        .achievement-section {

            background: #ffffff;
        }


        .achievement-card {

            padding: 30px;

            border-radius: 20px;

            text-align: center;

            border: 1px solid #e5ece7;

            transition: 0.3s;
        }


        .achievement-card:hover {

            transform: translateY(-7px);

            box-shadow: 0 20px 45px rgba(35, 80, 50, 0.08);
        }


        .achievement-icon {

            font-size: 42px;

            margin-bottom: 15px;

            display: inline-block;

            animation: achievementFloat 3s ease-in-out infinite;
        }


        @keyframes achievementFloat {

            0%,
            100% {
                transform: translateY(0) rotate(0);
            }

            50% {
                transform: translateY(-7px) rotate(4deg);
            }
        }


        .achievement-card h5 {

            font-weight: 700;

            font-size: 15px;
        }


        .achievement-card p {

            color: #7a8681;

            font-size: 12px;

            margin: 0;
        }



        /* =====================================================
           CTA
        ====================================================== */

        .cta-section {

            padding: 100px 0;

            background:
                linear-gradient(135deg,
                    #092d25,
                    #176044);

            position: relative;

            overflow: hidden;
        }


        .cta-section::before {

            content: "";

            position: absolute;

            width: 400px;
            height: 400px;

            border-radius: 50%;

            background: #7bdf5d;

            opacity: 0.07;

            left: -150px;
            top: -180px;
        }


        .cta-title {

            color: white;

            font-size: 45px;

            font-weight: 800;

            max-width: 700px;

            margin: auto;
        }


        .cta-description {

            color: rgba(255, 255, 255, 0.65);

            max-width: 600px;

            margin: 18px auto 30px;

            line-height: 1.8;

            font-size: 14px;
        }



        /* =====================================================
           FOOTER
        ====================================================== */

        footer {

            background: #061a16;

            color: white;

            padding: 55px 0 25px;
        }


        .footer-logo {

            font-size: 24px;

            font-weight: 800;
        }


        .footer-logo span {

            color: #7fe35d;
        }


        .footer-description {

            color: rgba(255, 255, 255, 0.5);

            font-size: 12px;

            line-height: 1.8;

            max-width: 350px;

            margin-top: 15px;
        }


        footer h6 {

            font-size: 13px;

            margin-bottom: 18px;
        }


        footer ul {

            list-style: none;

            padding: 0;

            margin: 0;
        }


        footer li {

            margin-bottom: 10px;
        }


        footer a {

            color: rgba(255, 255, 255, 0.5);

            text-decoration: none;

            font-size: 12px;

            transition: 0.3s;
        }


        footer a:hover {

            color: #83e95d;
        }


        .copyright {

            border-top: 1px solid rgba(255, 255, 255, 0.08);

            margin-top: 40px;

            padding-top: 20px;

            color: rgba(255, 255, 255, 0.35);

            font-size: 11px;
        }



        /* =====================================================
           RESPONSIVE
        ====================================================== */

        @media(max-width: 991px) {

            .navbar-collapse {

                background: #0a342a;

                padding: 20px;

                border-radius: 15px;

                margin-top: 15px;
            }


            .floating-card {

                display: none;
            }


            .hero-title {

                font-size: 58px;
            }


            .process-line {

                display: none;
            }

        }


        @media(max-width: 576px) {

            .section-padding {

                padding: 70px 0;
            }


            .hero {

                min-height: 850px;
            }


            .hero-title {

                font-size: 45px;

                letter-spacing: -2px;
            }


            .hero-description {

                font-size: 14px;
            }


            .hero-stats {

                gap: 17px;
            }


            .hero-stat strong {

                font-size: 20px;
            }


            .section-title {

                font-size: 32px;
            }


            .game-preview {

                padding: 25px;

                min-height: 400px;
            }


            .cta-title {

                font-size: 33px;
            }

        }
    </style>

</head>


<body>


    <!-- ==========================================================
     NAVBAR
=========================================================== -->

    <nav class="navbar navbar-expand-lg">

        <div class="container">

            <div class="d-flex justify-content-between align-items-center">

                <a class="navbar-brand" href="index.html">

                    <i class="bi bi-stars"></i>

                    Eco<span>Quest</span>

                </a>


                <button
                    class="navbar-toggler border-0 shadow-none"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNav">

                    <i class="bi bi-list text-white fs-2"></i>

                </button>

            </div>


            <div class="collapse navbar-collapse" id="mainNav">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">

                        <a class="nav-link" href="#home">
                            Home
                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#subjects">
                            Learning
                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#how">
                            How It Works
                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link" href="#games">
                            Games
                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link login-nav" href="{{route('login')}}">
                            Login
                        </a>

                    </li>


                    <li class="nav-item">

                        <a class="nav-link register-nav" href="{{route('user.register')}}">
                            Get Started
                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>



    <!-- ==========================================================
     HERO
=========================================================== -->

    <section class="hero" id="home">


        <!-- Background Video -->

        <!--
        Put your learning-themed video here:

        assets/videos/learning-bg.mp4

        Suggested video:
        Students + technology + education + classroom +
        coding + science + mathematics.
    -->

        <video
            class="hero-video"
            autoplay
            muted
            loop
            playsinline>

            <source
                src="assets/videos/learning-bg.mp4"
                type="video/mp4">

        </video>


        <div class="hero-overlay"></div>


        <!-- Animated blobs -->

        <div class="hero-blob blob-1"></div>

        <div class="hero-blob blob-2"></div>

        <div class="hero-blob blob-3"></div>


        <div class="container hero-content">

            <div class="row align-items-center">

                <div class="col-lg-9">

                    <div class="hero-badge">

                        <i class="bi bi-stars"></i>

                        A smarter way to learn

                    </div>


                    <h1 class="hero-title">

                        Learning is better

                        <span>when it feels like a game.</span>

                    </h1>


                    <p class="hero-description">

                        EcoQuest is a personalized gamified learning platform
                        where students can learn academic subjects, develop
                        useful skills, solve challenges, play educational games,
                        earn XP and grow every day.

                    </p>


                    <div class="hero-buttons">

                        <a
                            href="{{route('user.register')}}"
                            class="btn-start">

                            Start Learning

                            <i class="bi bi-arrow-right ms-2"></i>

                        </a>


                        <a
                            href="#how"
                            class="btn-explore">

                            <i class="bi bi-play-circle me-2"></i>

                            Explore EcoQuest

                        </a>

                    </div>


                    <div class="hero-stats">

                        <div class="hero-stat">

                            <strong>10+</strong>

                            <span>Learning Areas</span>

                        </div>


                        <div class="stat-divider"></div>


                        <div class="hero-stat">

                            <strong>50+</strong>

                            <span>Games & Quizzes</span>

                        </div>


                        <div class="stat-divider"></div>


                        <div class="hero-stat">

                            <strong>100+</strong>

                            <span>Challenges</span>

                        </div>


                        <div class="stat-divider"></div>


                        <div class="hero-stat">

                            <strong>∞</strong>

                            <span>Ways to Grow</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- Floating learning card -->

        <div class="floating-card d-none d-xl-block">

            <div class="floating-card-header">

                <div class="floating-card-title">

                    <i class="bi bi-stars text-success me-1"></i>

                    Recommended For You

                </div>

                <div class="xp">
                    +150 XP
                </div>

            </div>


            <div class="mini-course">

                <div class="mini-icon">

                    <i class="bi bi-code-slash"></i>

                </div>

                <div>

                    <strong>Programming Basics</strong>

                    <small>Computer Science</small>

                </div>

            </div>


            <div class="mini-course">

                <div class="mini-icon">

                    <i class="bi bi-calculator"></i>

                </div>

                <div>

                    <strong>Quick Math Challenge</strong>

                    <small>Mathematics</small>

                </div>

            </div>


            <div class="mini-course">

                <div class="mini-icon">

                    <i class="bi bi-puzzle"></i>

                </div>

                <div>

                    <strong>Logic Master</strong>

                    <small>Logical Reasoning</small>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
     SUBJECTS
=========================================================== -->

    <section class="section-padding subjects-section" id="subjects">

        <div class="container">


            <div class="text-center mb-5">

                <div class="section-tag">

                    <i class="bi bi-grid"></i>

                    Explore Learning

                </div>


                <h2 class="section-title">

                    Learn more than just a <span>subject.</span>

                </h2>


                <p class="section-description mx-auto">

                    EcoQuest brings academic learning, practical knowledge,
                    technology, awareness and skill development together
                    in one interactive platform.

                </p>

            </div>


            <div class="row g-4">


                <!-- Mathematics -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            🧮
                        </div>

                        <h5>Mathematics</h5>

                        <p>
                            Practice calculations, algebra, logic and
                            problem-solving through interactive challenges.
                        </p>

                    </div>

                </div>


                <!-- Science -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            🧪
                        </div>

                        <h5>Science</h5>

                        <p>
                            Explore scientific concepts through quizzes,
                            experiments, games and visual learning.
                        </p>

                    </div>

                </div>


                <!-- Computer -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            💻
                        </div>

                        <h5>Computer & Technology</h5>

                        <p>
                            Learn computer fundamentals, programming,
                            internet concepts and digital awareness.
                        </p>

                    </div>

                </div>


                <!-- English -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            📚
                        </div>

                        <h5>English</h5>

                        <p>
                            Improve vocabulary, grammar, communication
                            and language skills through practice.
                        </p>

                    </div>

                </div>


                <!-- Environment -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            🌱
                        </div>

                        <h5>Environment</h5>

                        <p>
                            Understand environmental issues and develop
                            responsible real-world habits.
                        </p>

                    </div>

                </div>


                <!-- Logical -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            🧠
                        </div>

                        <h5>Logical Reasoning</h5>

                        <p>
                            Improve critical thinking, patterns,
                            decision making and problem-solving.
                        </p>

                    </div>

                </div>


                <!-- GK -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            💡
                        </div>

                        <h5>General Knowledge</h5>

                        <p>
                            Discover useful facts, current awareness
                            and knowledge beyond textbooks.
                        </p>

                    </div>

                </div>


                <!-- Life Skills -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            🤝
                        </div>

                        <h5>Life Skills</h5>

                        <p>
                            Develop communication, time management,
                            decision making and practical skills.
                        </p>

                    </div>

                </div>


                <!-- Health -->

                <div class="col-md-6 col-lg-4">

                    <div class="subject-card">

                        <div class="subject-icon">
                            ❤️
                        </div>

                        <h5>Health & Safety</h5>

                        <p>
                            Learn important knowledge about personal
                            health, safety and everyday situations.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
     HOW IT WORKS
=========================================================== -->

    <section class="section-padding how-section" id="how">

        <div class="container">


            <div class="text-center mb-5">

                <div class="section-tag">

                    <i class="bi bi-lightning-charge"></i>

                    Simple Learning Journey

                </div>


                <h2 class="section-title">

                    Learn. Play. <span>Grow.</span>

                </h2>


                <p class="section-description mx-auto">

                    EcoQuest turns learning into a journey where every
                    activity helps you move one step forward.

                </p>

            </div>


            <div class="row">


                <div class="col-md-3">

                    <div class="process-card">

                        <div class="process-number">
                            01
                        </div>

                        <h5>Discover</h5>

                        <p>
                            Tell EcoQuest about your class, interests
                            and learning goals.
                        </p>

                        <div class="process-line"></div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="process-card">

                        <div class="process-number">
                            02
                        </div>

                        <h5>Learn</h5>

                        <p>
                            Explore personalized lessons and
                            educational content.
                        </p>

                        <div class="process-line"></div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="process-card">

                        <div class="process-number">
                            03
                        </div>

                        <h5>Play & Practice</h5>

                        <p>
                            Solve quizzes, play games and complete
                            interactive challenges.
                        </p>

                        <div class="process-line"></div>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="process-card">

                        <div class="process-number">
                            04
                        </div>

                        <h5>Earn & Grow</h5>

                        <p>
                            Earn XP, unlock badges and track
                            your learning progress.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
     GAMES
=========================================================== -->

    <section class="section-padding game-section" id="games">

        <div class="container">

            <div class="row align-items-center g-5">


                <div class="col-lg-6">

                    <div class="game-preview">

                        <div class="game-label">

                            <i class="bi bi-controller me-1"></i>

                            SAMPLE GAME

                        </div>


                        <h3 class="game-title">

                            Turn your knowledge into XP.

                        </h3>


                        <p class="game-subtitle">

                            From mathematics to computer science,
                            environmental awareness to logical reasoning,
                            every topic can become an interactive challenge.

                        </p>


                        <div class="game-options">

                            <div class="game-option">

                                <span>
                                    🧮 Solve the equation
                                </span>

                                <i class="bi bi-check-circle"></i>

                            </div>


                            <div class="game-option">

                                <span>
                                    💻 Identify the programming concept
                                </span>

                                <i class="bi bi-check-circle"></i>

                            </div>


                            <div class="game-option">

                                <span>
                                    🧠 Find the missing pattern
                                </span>

                                <i class="bi bi-check-circle"></i>

                            </div>


                            <div class="game-option">

                                <span>
                                    🌱 Complete the eco challenge
                                </span>

                                <i class="bi bi-check-circle"></i>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-6">

                    <div class="section-tag">

                        <i class="bi bi-controller"></i>

                        Gamified Learning

                    </div>


                    <h2 class="section-title">

                        Studying doesn't have to feel like <span>studying.</span>

                    </h2>


                    <p class="section-description">

                        EcoQuest uses games, quizzes, missions and rewards
                        to make learning more engaging. Instead of simply
                        reading information, students actively interact
                        with what they are learning.

                    </p>


                    <div class="row g-3 mt-3">

                        <div class="col-6">

                            <div class="p-3 rounded-3 bg-light">

                                <strong class="d-block">
                                    🎮 Mini Games
                                </strong>

                                <small class="text-muted">
                                    Learn through play
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="p-3 rounded-3 bg-light">

                                <strong class="d-block">
                                    🧩 Challenges
                                </strong>

                                <small class="text-muted">
                                    Practice your skills
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="p-3 rounded-3 bg-light">

                                <strong class="d-block">
                                    🏆 Leaderboard
                                </strong>

                                <small class="text-muted">
                                    Compete & improve
                                </small>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="p-3 rounded-3 bg-light">

                                <strong class="d-block">
                                    ⭐ XP & Badges
                                </strong>

                                <small class="text-muted">
                                    Get rewarded
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
     PERSONALIZATION
=========================================================== -->

    <section class="section-padding personal-section">

        <div class="container">

            <div class="row align-items-center g-5">


                <div class="col-lg-6">

                    <div class="section-tag">

                        <i class="bi bi-person-hearts"></i>

                        Personalized For You

                    </div>


                    <h2 class="section-title">

                        Your learning journey should be <span>your own.</span>

                    </h2>


                    <p class="section-description">

                        Everyone learns differently. During registration,
                        EcoQuest learns about your education level, interests,
                        subjects and goals. Your dashboard can then highlight
                        relevant topics, games and challenges.

                    </p>


                    <div class="mt-4">

                        <div class="d-flex gap-3 mb-3">

                            <div class="subject-icon flex-shrink-0">
                                🎯
                            </div>

                            <div>

                                <strong>
                                    Personalized Recommendations
                                </strong>

                                <p class="text-muted small mb-0 mt-1">
                                    Discover content selected according
                                    to your learning preferences.
                                </p>

                            </div>

                        </div>


                        <div class="d-flex gap-3 mb-3">

                            <div class="subject-icon flex-shrink-0">
                                📊
                            </div>

                            <div>

                                <strong>
                                    Track Your Progress
                                </strong>

                                <p class="text-muted small mb-0 mt-1">
                                    See where you are improving and
                                    which areas need more practice.
                                </p>

                            </div>

                        </div>


                        <div class="d-flex gap-3">

                            <div class="subject-icon flex-shrink-0">
                                🚀
                            </div>

                            <div>

                                <strong>
                                    Keep Improving
                                </strong>

                                <p class="text-muted small mb-0 mt-1">
                                    Your learning journey grows with
                                    your performance.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-lg-6">

                    <div class="personal-card">


                        <div class="profile-preview">

                            <div class="avatar">

                                <i class="bi bi-person"></i>

                            </div>

                            <div>

                                <h6>
                                    Your Personalized Dashboard
                                </h6>

                                <small>
                                    Recommended based on your interests
                                </small>

                            </div>

                        </div>


                        <div class="recommendation">

                            <div class="recommendation-icon">
                                💻
                            </div>

                            <div>

                                <strong>
                                    Programming Fundamentals
                                </strong>

                                <small>
                                    Recommended for you
                                </small>

                            </div>

                            <span class="ms-auto text-success">
                                +50 XP
                            </span>

                        </div>


                        <div class="recommendation">

                            <div class="recommendation-icon">
                                🧠
                            </div>

                            <div>

                                <strong>
                                    Logical Reasoning Challenge
                                </strong>

                                <small>
                                    Improve your problem solving
                                </small>

                            </div>

                            <span class="ms-auto text-success">
                                +75 XP
                            </span>

                        </div>


                        <div class="recommendation">

                            <div class="recommendation-icon">
                                🧮
                            </div>

                            <div>

                                <strong>
                                    Mathematics Quiz
                                </strong>

                                <small>
                                    Practice today's topic
                                </small>

                            </div>

                            <span class="ms-auto text-success">
                                +50 XP
                            </span>

                        </div>


                        <div class="recommendation">

                            <div class="recommendation-icon">
                                🌱
                            </div>

                            <div>

                                <strong>
                                    Environmental Challenge
                                </strong>

                                <small>
                                    Complete today's mission
                                </small>

                            </div>

                            <span class="ms-auto text-success">
                                +100 XP
                            </span>

                        </div>


                        <div class="mt-4">

                            <div class="d-flex justify-content-between">

                                <small class="text-muted">
                                    Overall Learning Progress
                                </small>

                                <small class="fw-bold">
                                    68%
                                </small>

                            </div>


                            <div
                                class="progress mt-2"
                                style="height:7px;">

                                <div
                                    class="progress-bar bg-success"
                                    style="width:68%"></div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
     ACHIEVEMENTS
=========================================================== -->

    <section class="section-padding achievement-section">

        <div class="container">


            <div class="text-center mb-5">

                <div class="section-tag">

                    <i class="bi bi-trophy"></i>

                    Gamification

                </div>


                <h2 class="section-title">

                    Every achievement deserves a <span>reward.</span>

                </h2>


                <p class="section-description mx-auto">

                    Learn more, complete more and unlock achievements
                    as you progress through your journey.

                </p>

            </div>


            <div class="row g-4">


                <div class="col-md-3">

                    <div class="achievement-card">

                        <div class="achievement-icon">
                            ⭐
                        </div>

                        <h5>Earn XP</h5>

                        <p>
                            Get points for learning and completing activities.
                        </p>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="achievement-card">

                        <div class="achievement-icon">
                            🏅
                        </div>

                        <h5>Unlock Badges</h5>

                        <p>
                            Show what you have achieved.
                        </p>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="achievement-card">

                        <div class="achievement-icon">
                            🏆
                        </div>

                        <h5>Climb Leaderboards</h5>

                        <p>
                            Challenge yourself and compete with others.
                        </p>

                    </div>

                </div>


                <div class="col-md-3">

                    <div class="achievement-card">

                        <div class="achievement-icon">
                            📜
                        </div>

                        <h5>Get Certificates</h5>

                        <p>
                            Receive certificates for important milestones.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>



    <!-- ==========================================================
     CTA
=========================================================== -->

    <section class="cta-section">

        <div class="container text-center">

            <h2 class="cta-title">

                Ready to make learning more exciting?

            </h2>


            <p class="cta-description">

                Create your EcoQuest account, tell us what you want
                to learn and start your personalized learning journey.

            </p>


            <a
                href="{{route('user.register')}}"
                class="btn-start">

                Create Your Account

                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

    </section>



    <!-- ==========================================================
     FOOTER
=========================================================== -->

    <footer>

        <div class="container">

            <div class="row g-5">


                <div class="col-lg-5">

                    <div class="footer-logo">

                        <i class="bi bi-stars text-success"></i>

                        Eco<span>Quest</span>

                    </div>


                    <p class="footer-description">

                        A gamified personalized learning platform
                        designed to make education interactive,
                        practical and enjoyable.

                    </p>

                </div>


                <div class="col-6 col-lg-2">

                    <h6>
                        Platform
                    </h6>

                    <ul>

                        <li>
                            <a href="#subjects">
                                Learning
                            </a>
                        </li>

                        <li>
                            <a href="#games">
                                Games
                            </a>
                        </li>

                        <li>
                            <a href="#how">
                                How It Works
                            </a>
                        </li>

                    </ul>

                </div>


                <div class="col-6 col-lg-2">

                    <h6>
                        Account
                    </h6>

                    <ul>

                        <li>
                            <a href="{{route('login')}}">
                                Login
                            </a>
                        </li>

                        <li>
                            <a href="{{route('user.register')}}">
                                Register
                            </a>
                        </li>

                    </ul>

                </div>


                <div class="col-6 col-lg-3">

                    <h6>
                        Learning
                    </h6>

                    <ul>

                        <li>
                            <a href="#">
                                Mathematics
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Science
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Computer
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Life Skills
                            </a>
                        </li>

                    </ul>

                </div>

            </div>


            <div class="copyright">

                <div class="d-flex justify-content-between flex-wrap gap-2">

                    <span class="text-center">
                        © 2026 EcoQuest. All rights reserved.
                    </span>

                    <span>
                        Learn. Play. Practice. Grow.
                    </span>

                </div>

            </div>

        </div>

    </footer>



    <!-- Bootstrap JS -->

    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>


    <script>
        /*
    ==========================================
       SIMPLE SCROLL REVEAL ANIMATION
    ==========================================
    */

        const revealElements =
            document.querySelectorAll(
                ".subject-card, .process-card, .achievement-card, .personal-card"
            );


        const revealObserver =
            new IntersectionObserver(

                function(entries) {

                    entries.forEach(function(entry) {

                        if (entry.isIntersecting) {

                            entry.target.style.opacity = "1";

                            entry.target.style.transform =
                                "translateY(0)";

                        }

                    });

                },

                {
                    threshold: 0.15
                }

            );


        revealElements.forEach(function(element) {

            element.style.opacity = "0";

            element.style.transform = "translateY(30px)";

            element.style.transition =
                "opacity .7s ease, transform .7s ease";

            revealObserver.observe(element);

        });
    </script>


</body>

</html>