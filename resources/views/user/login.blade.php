<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | EcoQuest</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/bootstrap-icons/bootstrap-icons.css')}}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">


    <style>
        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family: "Inter", sans-serif;

            background: #061a17;

            min-height: 100vh;

            overflow-x: hidden;

        }


        /* =========================================
           MAIN CONTAINER
        ========================================== */

        .login-page {

            min-height: 100vh;

            position: relative;

            overflow: hidden;

        }


        /* =========================================
           BACKGROUND VIDEO
        ========================================== */

        .bg-video {

            position: absolute;

            inset: 0;

            width: 100%;

            height: 100%;

            object-fit: cover;

            z-index: 0;

        }


        .video-overlay {

            position: absolute;

            inset: 0;

            z-index: 1;

            background:
                linear-gradient(90deg,
                    rgba(2, 22, 20, 0.96) 0%,
                    rgba(2, 30, 27, 0.88) 40%,
                    rgba(2, 30, 27, 0.62) 100%);

        }


        /* =========================================
           FLOATING BACKGROUND SHAPES
        ========================================== */

        .floating-circle {

            position: absolute;

            border-radius: 50%;

            filter: blur(3px);

            opacity: .35;

            z-index: 2;

            animation: floating 8s ease-in-out infinite;

        }


        .circle-1 {

            width: 250px;

            height: 250px;

            background: #5be46d;

            top: -100px;

            left: -80px;

        }


        .circle-2 {

            width: 180px;

            height: 180px;

            background: #23b6ff;

            bottom: 5%;

            left: 40%;

            animation-delay: 2s;

        }


        .circle-3 {

            width: 220px;

            height: 220px;

            background: #b9ef52;

            right: -100px;

            top: 15%;

            animation-delay: 4s;

        }


        @keyframes floating {

            0%,
            100% {

                transform:
                    translateY(0) scale(1);

            }

            50% {

                transform:
                    translateY(-30px) scale(1.08);

            }

        }


        /* =========================================
           PAGE CONTENT
        ========================================== */

        .page-content {

            position: relative;

            z-index: 5;

            min-height: 100vh;

            padding: 30px 0;

        }


        /* =========================================
           NAVBAR
        ========================================== */

        .logo {

            text-decoration: none;

            font-size: 30px;

            font-weight: 800;

            color: white;

        }


        .logo i {

            color: #83eb61;

            margin-right: 7px;

        }


        .logo span {

            color: #83eb61;

        }


        .tagline {

            color: rgba(255, 255, 255, .55);

            font-size: 10px;

            letter-spacing: 1.2px;

            margin-left: 38px;

            margin-top: -6px;

        }


        .register-link {

            color: white;

            text-decoration: none;

            padding: 10px 22px;

            border-radius: 10px;

            border: 1px solid rgba(255, 255, 255, .45);

            transition: .3s;

        }


        .register-link:hover {

            background: white;

            color: #173c32;

        }


        /* =========================================
           LEFT HERO
        ========================================== */

        .hero-section {

            padding: 60px 20px 30px;

            color: white;

            animation: heroAppear 1s ease;

        }


        @keyframes heroAppear {

            from {

                opacity: 0;

                transform: translateX(-40px);

            }

            to {

                opacity: 1;

                transform: translateX(0);

            }

        }


        .small-heading {

            color: #83eb61;

            font-weight: 600;

            font-size: 17px;

            margin-bottom: 12px;

        }


        .hero-title {

            font-size: clamp(48px, 5vw, 72px);

            line-height: 1;

            font-weight: 800;

            margin-bottom: 25px;

        }


        .hero-title span {

            color: #83eb61;

        }


        .hero-description {

            color: rgba(255, 255, 255, .72);

            font-size: 16px;

            line-height: 1.8;

            max-width: 520px;

        }


        /* =========================================
           FEATURE CARDS
        ========================================== */

        .feature-list {

            margin-top: 35px;

        }


        .feature {

            display: flex;

            align-items: center;

            margin-bottom: 18px;

            opacity: 0;

            animation: featureAppear .7s ease forwards;

        }


        .feature:nth-child(1) {

            animation-delay: .2s;

        }


        .feature:nth-child(2) {

            animation-delay: .4s;

        }


        .feature:nth-child(3) {

            animation-delay: .6s;

        }


        .feature:nth-child(4) {

            animation-delay: .8s;

        }


        @keyframes featureAppear {

            from {

                opacity: 0;

                transform: translateY(20px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }


        .feature-icon {

            width: 46px;

            height: 46px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: rgba(128, 236, 94, .10);

            border: 1px solid rgba(128, 236, 94, .30);

            color: #83eb61;

            font-size: 20px;

            margin-right: 14px;

        }


        .feature h6 {

            margin: 0 0 3px;

            font-weight: 700;

        }


        .feature p {

            margin: 0;

            font-size: 12px;

            color: rgba(255, 255, 255, .55);

        }


        /* =========================================
           LOGIN CARD
        ========================================== */

        .login-card {

            max-width: 510px;

            margin-left: auto;

            background: rgba(255, 255, 255, .97);

            border-radius: 28px;

            padding: 42px;

            box-shadow:
                0 35px 90px rgba(0, 0, 0, .35),
                0 0 70px rgba(106, 230, 91, .10);

            animation: cardAppear .8s ease;

        }


        @keyframes cardAppear {

            from {

                opacity: 0;

                transform:
                    translateY(40px) scale(.97);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0) scale(1);

            }

        }


        /* =========================================
           LOGIN HEADER
        ========================================== */

        .login-icon {

            width: 65px;

            height: 65px;

            margin: 0 auto 18px;

            border-radius: 20px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 30px;

            color: #4caf3d;

            background: #edf9e9;

            box-shadow:
                0 10px 25px rgba(74, 174, 61, .12);

            animation: iconFloat 3s ease-in-out infinite;

        }


        @keyframes iconFloat {

            0%,
            100% {

                transform: translateY(0);

            }

            50% {

                transform: translateY(-5px);

            }

        }


        .login-title {

            text-align: center;

            font-weight: 800;

            font-size: 30px;

            color: #132722;

            margin-bottom: 7px;

        }


        .login-title span {

            color: #50ad3d;

        }


        .login-subtitle {

            text-align: center;

            color: #7c8783;

            font-size: 13px;

            margin-bottom: 30px;

        }


        /* =========================================
           INPUTS
        ========================================== */

        .form-label {

            font-size: 13px;

            font-weight: 600;

            color: #26332f;

            margin-bottom: 8px;

        }


        .input-wrapper {

            position: relative;

        }


        .input-wrapper>i {

            position: absolute;

            left: 15px;

            top: 15px;

            color: #8b9792;

            z-index: 2;

        }


        .form-control {

            height: 51px;

            border-radius: 11px;

            border: 1px solid #d8dfdc;

            padding-left: 43px;

            font-size: 13px;

        }


        .form-control:focus {

            border-color: #5cc746;

            box-shadow:
                0 0 0 3px rgba(92, 199, 70, .12);

        }


        /* =========================================
           PASSWORD TOGGLE
        ========================================== */

        .password-toggle {

            position: absolute;

            right: 13px;

            top: 13px;

            border: none;

            background: transparent;

            color: #7b8581;

            z-index: 3;

        }


        .password-input {

            padding-right: 45px;

        }


        /* =========================================
           OPTIONS
        ========================================== */

        .login-options {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-top: 14px;

            margin-bottom: 25px;

        }


        .remember-label {

            font-size: 12px;

            color: #68736f;

        }


        .form-check-input {

            border-color: #cdd5d1;

        }


        .form-check-input:checked {

            background-color: #4fb83d;

            border-color: #4fb83d;

        }


        .forgot-link {

            text-decoration: none;

            font-size: 12px;

            color: #45a536;

            font-weight: 600;

        }


        .forgot-link:hover {

            text-decoration: underline;

        }


        /* =========================================
           LOGIN BUTTON
        ========================================== */

        .login-button {

            width: 100%;

            height: 52px;

            border: none;

            border-radius: 12px;

            color: white;

            font-weight: 700;

            font-size: 14px;

            background:
                linear-gradient(135deg,
                    #36a63a,
                    #70d84d);

            box-shadow:
                0 12px 25px rgba(62, 176, 57, .25);

            transition: .3s;

        }


        .login-button:hover {

            transform: translateY(-2px);

            box-shadow:
                0 15px 30px rgba(62, 176, 57, .35);

        }


        /* =========================================
           DIVIDER
        ========================================== */

        .divider {

            display: flex;

            align-items: center;

            gap: 12px;

            margin: 25px 0;

            color: #9aa39f;

            font-size: 12px;

        }


        .divider::before,
        .divider::after {

            content: "";

            height: 1px;

            background: #e0e5e3;

            flex: 1;

        }


        /* =========================================
           SOCIAL LOGIN
        ========================================== */

        .social-login {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 10px;

        }


        .social-button {

            height: 45px;

            border: 1px solid #dce2df;

            background: white;

            border-radius: 10px;

            font-size: 12px;

            font-weight: 600;

            color: #46514d;

            transition: .25s;

        }


        .social-button:hover {

            border-color: #62c752;

            background: #f5fbf3;

            transform: translateY(-2px);

        }


        .google {

            color: #4285f4;

        }


        .facebook {

            color: #1877f2;

        }


        .microsoft {

            color: #f25022;

        }


        /* =========================================
           BOTTOM REGISTER
        ========================================== */

        .register-text {

            text-align: center;

            margin-top: 25px;

            font-size: 13px;

            color: #7d8783;

        }


        .register-text a {

            color: #4aa83b;

            font-weight: 700;

            text-decoration: none;

        }


        .register-text a:hover {

            text-decoration: underline;

        }


        /* =========================================
           SECURITY MESSAGE
        ========================================== */

        .security-message {

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            margin-top: 22px;

            font-size: 11px;

            color: #9aa39f;

        }


        .security-message i {

            color: #55b746;

        }


        /* =========================================
           RESPONSIVE
        ========================================== */

        @media(max-width: 991px) {

            .hero-section {

                padding: 35px 20px;

                text-align: center;

            }


            .hero-description {

                margin-left: auto;

                margin-right: auto;

            }


            .feature {

                text-align: left;

            }


            .login-card {

                margin: 20px auto 0;

            }

        }


        @media(max-width: 576px) {

            .page-content {

                padding: 20px 10px;

            }


            .hero-title {

                font-size: 45px;

            }


            .login-card {

                padding: 28px 20px;

                border-radius: 20px;

            }


            .login-title {

                font-size: 26px;

            }


            .social-login {

                grid-template-columns: 1fr;

            }


            .login-options {

                align-items: flex-start;

            }

        }
    </style>

</head>


<body>


    <div class="login-page">


        <!-- =========================================
         BACKGROUND VIDEO
    ========================================== -->

        <!--
        Put your video here:

        assets/videos/ecoquest-bg.mp4

        Remove the comments if you want to use it.
    -->

        <!--

    <video
        class="bg-video"
        autoplay
        muted
        loop
        playsinline
    >

        <source
            src="assets/videos/ecoquest-bg.mp4"
            type="video/mp4"
        >

    </video>

    -->


        <!-- Background overlay -->

        <div class="video-overlay"></div>


        <!-- Floating animation -->

        <div class="floating-circle circle-1"></div>

        <div class="floating-circle circle-2"></div>

        <div class="floating-circle circle-3"></div>


        <!-- =========================================
         CONTENT
    ========================================== -->

        <div class="container-fluid page-content">


            <!-- =====================================
             NAVBAR
        ====================================== -->

            <div class="container">


                <div class="d-flex justify-content-between align-items-center">


                    <!-- Logo -->

                    <div>

                        <a href="index.html" class="logo">

                            <i class="bi bi-leaf-fill"></i>

                            Eco<span>Quest</span>

                        </a>


                        <div class="tagline">

                            LEARN. PLAY. PRACTICE. GROW.

                        </div>

                    </div>


                    <!-- Register -->

                    <div class="d-flex align-items-center gap-3">

                        <span class="text-white small d-none d-md-block">

                            New to EcoQuest?

                        </span>


                        <a href="registration.html" class="register-link">

                            Create Account

                        </a>

                    </div>


                </div>


            </div>



            <!-- =====================================
             MAIN ROW
        ====================================== -->

            <div class="container">

                <div class="row align-items-center g-5">


                    <!-- =================================
                     LEFT SIDE
                ================================== -->

                    <div class="col-lg-6">


                        <div class="hero-section">


                            <div class="small-heading">

                                <i class="bi bi-stars me-2"></i>

                                Welcome Back, Learner!

                            </div>


                            <h1 class="hero-title">

                                Continue Your

                                <br>

                                <span>Learning Journey.</span>

                            </h1>


                            <p class="hero-description">

                                Your personalized learning adventure
                                is waiting for you. Continue learning,
                                complete challenges, earn XP and
                                unlock your next achievement.

                            </p>



                            <!-- FEATURES -->

                            <div class="feature-list">


                                <div class="feature">

                                    <div class="feature-icon">

                                        <i class="bi bi-magic"></i>

                                    </div>


                                    <div>

                                        <h6>
                                            Personalized Learning
                                        </h6>

                                        <p>
                                            Content selected according
                                            to your interests and goals.
                                        </p>

                                    </div>

                                </div>



                                <div class="feature">

                                    <div class="feature-icon">

                                        <i class="bi bi-controller"></i>

                                    </div>


                                    <div>

                                        <h6>
                                            Learn Through Games
                                        </h6>

                                        <p>
                                            Turn learning into an
                                            interactive experience.
                                        </p>

                                    </div>

                                </div>



                                <div class="feature">

                                    <div class="feature-icon">

                                        <i class="bi bi-lightning-charge"></i>

                                    </div>


                                    <div>

                                        <h6>
                                            Earn XP & Badges
                                        </h6>

                                        <p>
                                            Complete activities and
                                            unlock achievements.
                                        </p>

                                    </div>

                                </div>



                                <div class="feature">

                                    <div class="feature-icon">

                                        <i class="bi bi-bar-chart-line"></i>

                                    </div>


                                    <div>

                                        <h6>
                                            Track Your Progress
                                        </h6>

                                        <p>
                                            See how much you've learned
                                            and how far you've progressed.
                                        </p>

                                    </div>

                                </div>


                            </div>


                        </div>


                    </div>



                    <!-- =================================
                     LOGIN FORM
                ================================== -->

                    <div class="col-lg-6">


                        <div class="login-card">


                            <!-- LOGIN ICON -->

                            <div class="login-icon">

                                <i class="bi bi-person-lock"></i>

                            </div>


                            <!-- TITLE -->

                            <h2 class="login-title">

                                Welcome <span>Back!</span>

                            </h2>


                            <p class="login-subtitle">

                                Login to continue your EcoQuest journey.

                            </p>



                            <!-- =================================
                             FORM
                        ================================== -->

                            <!-- Error message print -->
                            @if($errors->any())
                            <div class="alert alert-danger py-2 small">
                                <i class="bi bi-exclamation-circle me-2"></i>

                                {{ $errors->first() }}
                            </div>
                            @endif

                            <form action="{{route('login-user')}}" id="loginForm" method="POST">
                                @csrf
                                <!-- EMAIL -->

                                <div class="mb-3">


                                    <label class="form-label">Email Address</label>

                                    <div class="input-wrapper">
                                        <i class="bi bi-envelope"></i>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter your email address" value="{{old('email')}}" required>

                                    </div>


                                </div>



                                <!-- PASSWORD -->

                                <div class="mb-2">


                                    <label class="form-label">

                                        Password

                                    </label>


                                    <div class="input-wrapper">


                                        <i class="bi bi-lock"></i>


                                        <input type="password" name="password" id="loginPassword"
                                            class="form-control password-input" placeholder="Enter your password"
                                            required>


                                        <button type="button" class="password-toggle" onclick="togglePassword()">

                                            <i id="passwordIcon" class="bi bi-eye"></i>

                                        </button>


                                    </div>


                                </div>



                                <!-- OPTIONS -->

                                <div class="login-options">


                                    <div class="form-check">

                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">


                                        <label class="form-check-label remember-label" for="remember">

                                            Remember me

                                        </label>

                                    </div>


                                    <a href="forgot-password.html" class="forgot-link">

                                        Forgot Password?

                                    </a>


                                </div>



                                <!-- LOGIN BUTTON -->

                                <button type="submit" class="login-button">

                                    <i class="bi bi-box-arrow-in-right me-2"></i>

                                    Login to EcoQuest

                                </button>


                            </form>



                            <!-- =================================
                             DIVIDER
                        ================================== -->

                            <div class="divider">

                                Or continue with

                            </div>



                            <!-- =================================
                             SOCIAL LOGIN
                        ================================== -->

                            <div class="social-login">


                                <button type="button" class="social-button google">

                                    <i class="bi bi-google me-2"></i>

                                    Google

                                </button>


                                <button type="button" class="social-button facebook">

                                    <i class="bi bi-facebook me-2"></i>

                                    Facebook

                                </button>


                                <button type="button" class="social-button microsoft">

                                    <i class="bi bi-microsoft me-2"></i>

                                    Microsoft

                                </button>


                            </div>



                            <!-- REGISTER -->

                            <div class="register-text">

                                Don't have an account?

                                <a href="{{route('user.register')}}">

                                    Create your EcoQuest account

                                </a>

                            </div>



                            <!-- SECURITY -->

                            <div class="security-message">

                                <i class="bi bi-shield-check"></i>

                                Your account information is protected.

                            </div>


                        </div>


                    </div>


                </div>

            </div>


        </div>


    </div>



    <!-- =========================================
     BOOTSTRAP JS
========================================== -->

    <<script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}">
        </script>

        <script>
            /* =========================================
           PASSWORD SHOW / HIDE
        ========================================== */

            function togglePassword() {


                const password =
                    document.getElementById("loginPassword");


                const icon =
                    document.getElementById("passwordIcon");


                if (password.type === "password") {


                    password.type = "text";


                    icon.classList.remove("bi-eye");

                    icon.classList.add("bi-eye-slash");


                } else {


                    password.type = "password";


                    icon.classList.remove("bi-eye-slash");

                    icon.classList.add("bi-eye");


                }

            }
        </script>


</body>

</html>