<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Join EcoQuest | AI Gamified Learning</title>

    <!-- Local Bootstrap -->
    <link rel="stylesheet" href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 10% 20%, rgba(0, 255, 170, .15), transparent 30%),
                radial-gradient(circle at 90% 80%, rgba(0, 150, 255, .15), transparent 30%),
                #071311;
            color: #fff;
            overflow-x: hidden;
        }

        /* Background glow */
        .glow {
            position: fixed;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: .25;
            pointer-events: none;
        }

        .glow-one {
            background: #00ffae;
            top: -100px;
            left: -100px;
        }

        .glow-two {
            background: #008cff;
            bottom: -120px;
            right: -100px;
        }

        /* Navbar */
        .navbar {
            background: rgba(5, 20, 17, .8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: #fff !important;
        }

        .brand-dot {
            color: #00e89d;
        }

        .login-link {
            color: #d8e8e3;
            text-decoration: none;
            margin-right: 15px;
        }

        .login-link:hover {
            color: #00e89d;
        }

        .login-btn {
            border: 1px solid #00e89d;
            color: #00e89d;
            border-radius: 30px;
            padding: 8px 20px;
            text-decoration: none;
            transition: .3s;
        }

        .login-btn:hover {
            background: #00e89d;
            color: #071311;
        }

        /* Main */
        .register-wrapper {
            min-height: calc(100vh - 70px);
            padding: 55px 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            width: 100%;
            max-width: 1150px;
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 30px;
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, .4);
        }

        /* Left side */
        .register-intro {
            padding: 55px 45px;
            background:
                linear-gradient(145deg,
                    rgba(0, 255, 174, .16),
                    rgba(0, 90, 75, .05));
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .intro-badge {
            display: inline-flex;
            width: fit-content;
            padding: 7px 15px;
            border-radius: 30px;
            background: rgba(0, 232, 157, .1);
            border: 1px solid rgba(0, 232, 157, .3);
            color: #00e89d;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .register-intro h1 {
            font-size: 3rem;
            line-height: 1.1;
            font-weight: 800;
        }

        .register-intro h1 span {
            color: #00e89d;
        }

        .register-intro p {
            color: #b6cbc5;
            line-height: 1.7;
            margin-top: 18px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 18px;
            color: #dce9e5;
        }

        .feature-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(0, 232, 157, .12);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #00e89d;
        }

        /* Form side */
        .register-form-area {
            padding: 45px;
            background: rgba(3, 13, 11, .55);
        }

        .form-heading {
            margin-bottom: 30px;
        }

        .form-heading h2 {
            font-weight: 750;
            margin-bottom: 7px;
        }

        .form-heading p {
            color: #91aaa3;
            margin: 0;
        }

        /* Progress */
        .progress-wrapper {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 40px;
        }

        .progress-line {
            position: absolute;
            top: 17px;
            left: 8%;
            width: 84%;
            height: 2px;
            background: rgba(255, 255, 255, .12);
            z-index: 0;
        }

        .progress-step {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #708780;
            font-size: 12px;
        }

        .step-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto auto 7px;
            background: #14231f;
            border: 1px solid #29433b;
            font-weight: 700;
        }

        .progress-step.active {
            color: #00e89d;
        }

        .progress-step.active .step-circle {
            background: #00e89d;
            color: #071311;
            border-color: #00e89d;
        }

        /* Steps */
        .form-step {
            display: none;
            animation: fadeIn .4s ease;
        }

        .form-step.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(15px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .step-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .step-description {
            color: #829992;
            font-size: 14px;
            margin-bottom: 25px;
        }

        /* Form */
        .form-label {
            color: #dce8e4;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .required {
            color: #00e89d;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, .055);
            border: 1px solid rgba(255, 255, 255, .12);
            color: #fff;
            min-height: 48px;
            border-radius: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, .07);
            color: #fff;
            border-color: #00e89d;
            box-shadow: 0 0 0 3px rgba(0, 232, 157, .1);
        }

        .form-control::placeholder {
            color: #657b74;
        }

        .form-select option {
            background: #10201b;
            color: #fff;
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 14px;
            top: 13px;
            background: none;
            border: none;
            color: #80958f;
        }

        /* Interest cards */
        .selection-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .selection-card {
            position: relative;
        }

        .selection-card input {
            position: absolute;
            opacity: 0;
        }

        .selection-card label {
            display: block;
            padding: 14px 10px;
            text-align: center;
            border-radius: 13px;
            border: 1px solid rgba(255, 255, 255, .1);
            background: rgba(255, 255, 255, .035);
            color: #b7c9c4;
            cursor: pointer;
            font-size: 13px;
            transition: .25s;
        }

        .selection-card label:hover {
            border-color: rgba(0, 232, 157, .4);
            transform: translateY(-2px);
        }

        .selection-card input:checked+label {
            background: rgba(0, 232, 157, .12);
            border-color: #00e89d;
            color: #00e89d;
        }

        .selection-card label span {
            display: block;
            font-size: 21px;
            margin-bottom: 5px;
        }

        .selection-note {
            color: #71867f;
            font-size: 12px;
            margin-top: 10px;
        }

        /* Buttons */
        .button-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-next,
        .btn-submit {
            flex: 1;
            border: none;
            border-radius: 12px;
            padding: 13px 20px;
            background: #00e89d;
            color: #071311;
            font-weight: 700;
            transition: .3s;
        }

        .btn-next:hover,
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 232, 157, .2);
        }

        .btn-back {
            border: 1px solid rgba(255, 255, 255, .15);
            background: transparent;
            color: #b9cac5;
            border-radius: 12px;
            padding: 13px 20px;
            min-width: 110px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, .05);
            color: #fff;
        }

        .terms {
            color: #849891;
            font-size: 12px;
        }

        .terms a {
            color: #00e89d;
            text-decoration: none;
        }

        .form-check-input {
            background-color: transparent;
            border-color: #49635b;
        }

        .form-check-input:checked {
            background-color: #00e89d;
            border-color: #00e89d;
        }

        /* Responsive */
        @media(max-width: 900px) {
            .register-container {
                grid-template-columns: 1fr;
            }

            .register-intro {
                padding: 40px;
            }

            .register-intro h1 {
                font-size: 2.3rem;
            }
        }

        @media(max-width: 600px) {
            .register-wrapper {
                padding: 25px 12px;
            }

            .register-intro,
            .register-form-area {
                padding: 30px 22px;
            }

            .selection-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .button-row {
                flex-direction: column;
            }

            .btn-back {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="glow glow-one"></div>
    <div class="glow glow-two"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-4">

            <a class="navbar-brand" href="#">
                Eco<span class="brand-dot">Quest</span>
            </a>

            <div class="ms-auto d-flex align-items-center">
                <span class="login-link d-none d-sm-inline">
                    Already have an account?
                </span>

                <a href="{{ route('login') }}" class="login-btn">
                    Login
                </a>
            </div>

        </div>
    </nav>


    <!-- Registration -->

    <div class="register-wrapper">

        <div class="register-container">

            <!-- LEFT SIDE -->
            <div class="register-intro">

                <span class="intro-badge">
                    ✨ AI-Powered Learning
                </span>

                <h1>
                    Build Your Own
                    <span>Learning World.</span>
                </h1>

                <p>
                    Tell us what you enjoy, how you learn and what you want
                    to achieve. Our AI will create a personalized learning
                    experience for you.
                </p>

                <div class="feature">
                    <div class="feature-icon">🎯</div>
                    <div>Personalized learning journey</div>
                </div>

                <div class="feature">
                    <div class="feature-icon">🎮</div>
                    <div>Gamified challenges & activities</div>
                </div>

                <div class="feature">
                    <div class="feature-icon">🤖</div>
                    <div>AI-powered recommendations</div>
                </div>

                <div class="feature">
                    <div class="feature-icon">🏆</div>
                    <div>Earn points, badges & achievements</div>
                </div>

            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <!-- RIGHT SIDE -->
            <div class="register-form-area">

                <div class="form-heading">
                    <h2>Create Your Account</h2>
                    <p>Let's personalize your learning experience.</p>
                </div>


                <!-- Progress -->
                <div class="progress-wrapper">

                    <div class="progress-line"></div>

                    <div class="progress-step active" id="progress1">
                        <div class="step-circle">1</div>
                        Account
                    </div>

                    <div class="progress-step" id="progress2">
                        <div class="step-circle">2</div>
                        Academic
                    </div>

                    <div class="progress-step" id="progress3">
                        <div class="step-circle">3</div>
                        AI Profile
                    </div>

                </div>

                <form action="{{ route('register-user') }}" method="POST" id="registrationForm">
                    @csrf
                    <!-- ================= STEP 1 ================= -->

                    <div class="form-step active" id="step1">

                        <div class="step-title">
                            Account Information
                        </div>

                        <div class="step-description">
                            Create your basic account.
                        </div>


                        <!-- Name -->
                        <div class="mb-3">

                            <label class="form-label">
                                Full Name <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter your full name"
                                required>

                        </div>


                        <!-- Email -->
                        <div class="mb-3">

                            <label class="form-label">
                                Email Address <span class="required">*</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="example@email.com"
                                required>

                        </div>


                        <div class="row">

                            <!-- Password -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Password <span class="required">*</span>
                                </label>

                                <div class="password-wrapper">

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="Minimum 8 characters"
                                        minlength="8"
                                        required>

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('password')">
                                        👁
                                    </button>

                                </div>

                            </div>


                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Confirm Password <span class="required">*</span>
                                </label>

                                <div class="password-wrapper">

                                    <input
                                        type="password"
                                        name="password_confirmation"
                                        id="confirmPassword"
                                        class="form-control"
                                        placeholder="Confirm password"
                                        minlength="8"
                                        required>

                                    <button
                                        type="button"
                                        class="password-toggle"
                                        onclick="togglePassword('confirmPassword')">
                                        👁
                                    </button>

                                </div>

                            </div>

                        </div>


                        <div class="button-row">

                            <div></div>

                            <button
                                type="button"
                                class="btn-next"
                                onclick="nextStep(2)">
                                Continue →
                            </button>

                        </div>

                    </div>


                    <!-- ================= STEP 2 ================= -->

                    <div class="form-step" id="step2">

                        <div class="step-title">
                            Academic Profile
                        </div>

                        <div class="step-description">
                            Help AI understand your current learning level.
                        </div>


                        <!-- Education -->
                        <div class="mb-3">

                            <label class="form-label">
                                Education Level <span class="required">*</span>
                            </label>

                            <select
                                name="education_level"
                                class="form-select"
                                required>

                                <option value="">
                                    Select education level
                                </option>

                                <option>School</option>
                                <option>Higher Secondary</option>
                                <option>Diploma</option>
                                <option>Undergraduate</option>
                                <option>Postgraduate</option>
                                <option>Other</option>

                            </select>

                        </div>


                        <div class="row">

                            <!-- Class -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Class / Semester
                                    <span class="required">*</span>
                                </label>

                                <select
                                    name="class_semester"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select
                                    </option>

                                    <option>Class 6</option>
                                    <option>Class 7</option>
                                    <option>Class 8</option>
                                    <option>Class 9</option>
                                    <option>Class 10</option>
                                    <option>Class 11</option>
                                    <option>Class 12</option>
                                    <option>Semester 1</option>
                                    <option>Semester 2</option>
                                    <option>Semester 3</option>
                                    <option>Semester 4</option>
                                    <option>Semester 5</option>
                                    <option>Semester 6</option>

                                </select>

                            </div>


                            <!-- Experience -->
                            <div class="col-md-6 mb-3">

                                <label class="form-label">
                                    Learning Experience
                                    <span class="required">*</span>
                                </label>

                                <select
                                    name="experience_level"
                                    class="form-select"
                                    required>

                                    <option value="">
                                        Select level
                                    </option>

                                    <option>Beginner</option>
                                    <option>Intermediate</option>
                                    <option>Advanced</option>

                                </select>

                            </div>

                        </div>


                        <!-- Institution -->
                        <div class="mb-3">

                            <label class="form-label">
                                School / College Name
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                name="institution"
                                class="form-control"
                                placeholder="Enter your school or college"
                                required>

                        </div>


                        <div class="button-row">

                            <button
                                type="button"
                                class="btn-back"
                                onclick="previousStep(1)">
                                ← Back
                            </button>

                            <button
                                type="button"
                                class="btn-next"
                                onclick="nextStep(3)">
                                Continue →
                            </button>

                        </div>

                    </div>


                    <!-- ================= STEP 3 ================= -->

                    <div class="form-step" id="step3">

                        <div class="step-title">
                            Your AI Learning Profile
                        </div>

                        <div class="step-description">
                            Choose what you enjoy. AI will use this to personalize
                            your learning world.
                        </div>


                        <!-- Interests -->
                        <div class="mb-4">

                            <label class="form-label">
                                What are you interested in?
                                <span class="required">*</span>
                            </label>

                            <div class="selection-grid">

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Math" id="math">
                                    <label for="math">
                                        <span>🔢</span>
                                        Mathematics
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Science" id="science">
                                    <label for="science">
                                        <span>🔬</span>
                                        Science
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Coding" id="coding">
                                    <label for="coding">
                                        <span>💻</span>
                                        Coding
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Chess" id="chess">
                                    <label for="chess">
                                        <span>♟️</span>
                                        Chess
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Sports" id="sports">
                                    <label for="sports">
                                        <span>⚽</span>
                                        Sports
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Art" id="art">
                                    <label for="art">
                                        <span>🎨</span>
                                        Art & Design
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Music" id="music">
                                    <label for="music">
                                        <span>🎵</span>
                                        Music
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Reading" id="reading">
                                    <label for="reading">
                                        <span>📚</span>
                                        Reading
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Puzzles" id="puzzles">
                                    <label for="puzzles">
                                        <span>🧩</span>
                                        Puzzles
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Technology" id="technology">
                                    <label for="technology">
                                        <span>🤖</span>
                                        Technology
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="GK" id="gk">
                                    <label for="gk">
                                        <span>🌎</span>
                                        General Knowledge
                                    </label>
                                </div>

                                <div class="selection-card">
                                    <input type="checkbox" name="interests[]" value="Environment" id="environment">
                                    <label for="environment">
                                        <span>🌱</span>
                                        Environment
                                    </label>
                                </div>

                            </div>

                            <div class="selection-note">
                                Select 1 or more interests (e.g. Chess, Coding, Sports).
                            </div>

                        </div>


                        <!-- Goal -->
                        <div class="mb-3">

                            <label class="form-label">
                                Main Learning Goal
                                <span class="required">*</span>
                            </label>

                            <select
                                name="learning_goal"
                                class="form-select"
                                required>

                                <option value="">
                                    What do you want to achieve?
                                </option>

                                <option>
                                    Improve Academic Performance
                                </option>

                                <option>
                                    Learn New Skills
                                </option>

                                <option>
                                    Prepare for Exams
                                </option>

                                <option>
                                    Improve General Knowledge
                                </option>

                                <option>
                                    Improve Problem Solving
                                </option>

                                <option>
                                    Learn Through Games
                                </option>

                                <option>
                                    Prepare for Competitions
                                </option>

                                <option>
                                    Personal Growth
                                </option>

                            </select>

                        </div>


                        <!-- Experience preference -->
                        <div class="mb-3">

                            <label class="form-label">
                                What kind of experience do you enjoy?
                                <span class="required">*</span>
                            </label>

                            <select
                                name="experience_preference"
                                class="form-select"
                                required>

                                <option value="">
                                    Choose your preferred experience
                                </option>

                                <option>
                                    ⚡ Fast & Competitive
                                </option>

                                <option>
                                    🧩 Problem Solving
                                </option>

                                <option>
                                    🎨 Creative
                                </option>

                                <option>
                                    🧘 Calm & Focused
                                </option>

                                <option>
                                    🏆 Achievement Based
                                </option>

                                <option>
                                    🗺️ Exploration & Discovery
                                </option>

                            </select>

                        </div>


                        <!-- Terms -->
                        <div class="form-check mt-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="terms"
                                id="terms"
                                value="1"
                                required>

                            <label
                                class="form-check-label terms"
                                for="terms">

                                I agree to the
                                <a href="#">Terms & Conditions</a>
                                and understand that my preferences will be used
                                to personalize my learning experience.

                            </label>

                        </div>


                        <div class="button-row">

                            <button
                                type="button"
                                class="btn-back"
                                onclick="previousStep(2)">
                                ← Back
                            </button>

                            <button
                                type="submit"
                                class="btn-submit">
                                Create My Learning World 🚀
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>


    <script>
        let currentStep = 1;


        function showStep(step) {

            document.querySelectorAll(".form-step").forEach(function(element) {
                element.classList.remove("active");
            });

            document.getElementById("step" + step).classList.add("active");


            document.querySelectorAll(".progress-step").forEach(function(element) {
                element.classList.remove("active");
            });

            document.getElementById("progress" + step).classList.add("active");

            currentStep = step;

            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }


        function nextStep(step) {

            const currentForm = document.getElementById("step" + currentStep);

            const requiredFields = currentForm.querySelectorAll("[required]");

            for (let field of requiredFields) {

                if (!field.checkValidity()) {

                    field.reportValidity();

                    return;
                }
            }


            /* Minimum 3 interests */
            if (currentStep === 2) {

                // No extra validation here
            }


            showStep(step);
        }


        function previousStep(step) {

            showStep(step);

        }


        function togglePassword(id) {

            const input = document.getElementById(id);

            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }

        }


        /* Final submit */
        document.getElementById("registrationForm")
            .addEventListener("submit", function(event) {

                const interests = document.querySelectorAll(
                    'input[name="interests[]"]:checked'
                );

                if (interests.length < 1) {
                    event.preventDefault();

                    alert("Please select at least 1 interest.");
                    return;
                }

                if (!document.getElementById("terms").checked) {
                    event.preventDefault();

                    alert("Please accept the Terms & Conditions.");
                    return;
                }

                // IMPORTANT:
                // এখানে event.preventDefault() নেই।
                // তাই form Laravel Controller-এ submit হবে.
            });
    </script>

    <!-- Local Bootstrap JS -->
    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>