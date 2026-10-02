<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Admin Login | EcoQuest</title>

    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('Asset/Bootstrap-5/icons/bootstrap-icons.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            background:
                radial-gradient(circle at top left,
                    #dff8e9,
                    transparent 35%),
                radial-gradient(circle at bottom right,
                    #dceeff,
                    transparent 35%),
                #f5f8f7;

            font-family: Arial, sans-serif;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 430px;
        }

        .login-card {
            background: rgba(255, 255, 255, .95);

            border-radius: 28px;

            padding: 40px;

            box-shadow:
                0 25px 70px rgba(0, 0, 0, .10);

            border: 1px solid rgba(255, 255, 255, .8);
        }

        .brand {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;

            margin: auto;
            margin-bottom: 15px;

            border-radius: 22px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #12372a;
            color: white;

            font-size: 30px;
        }

        .brand h2 {
            font-weight: 800;
            color: #12372a;
            margin-bottom: 5px;
        }

        .brand p {
            color: #718078;
            margin: 0;
        }

        .admin-badge {
            display: inline-block;

            background: #e8f5ee;
            color: #16734b;

            padding: 6px 13px;

            border-radius: 50px;

            font-size: 12px;
            font-weight: 700;

            margin-top: 12px;
        }

        .form-label {
            font-weight: 700;
            color: #34433c;
        }

        .form-control {
            border-radius: 14px;
            padding: 13px 15px;

            border: 1px solid #dce5df;
        }

        .form-control:focus {
            border-color: #2f9e68;

            box-shadow:
                0 0 0 4px rgba(47, 158, 104, .10);
        }

        .login-btn {
            width: 100%;

            border: none;

            padding: 14px;

            border-radius: 14px;

            background: #12372a;
            color: white;

            font-weight: 700;

            transition: .2s;
        }

        .login-btn:hover {
            background: #1c5842;
            transform: translateY(-1px);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            text-decoration: none;
            color: #16734b;
            font-weight: 600;
        }

        .alert {
            border-radius: 13px;
        }

        @media(max-width: 500px) {

            .login-card {
                padding: 28px 22px;
            }

        }
    </style>

</head>

<body>

    <div class="login-wrapper">

        <div class="login-card">

            <div class="brand">

                <div class="brand-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>

                <h2>EcoQuest</h2>

                <p>Platform Administration</p>

                <span class="admin-badge">
                    <i class="bi bi-shield-check"></i>
                    ADMIN PORTAL
                </span>

            </div>


            {{-- Success Message --}}

            @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

            @endif


            {{-- Validation Error --}}

            @if($errors->any())

            <div class="alert alert-danger">

                {{ $errors->first() }}

            </div>

            @endif


            <form action="{{ route('admin.login.submit') }}"
                method="POST">

                @csrf


                <div class="mb-3">

                    <label class="form-label">
                        Admin Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        placeholder="admin@example.com"
                        required>

                </div>


                <div class="mb-4">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter admin password"
                        required>

                </div>


                <button type="submit"
                    class="login-btn">

                    <i class="bi bi-box-arrow-in-right me-2"></i>

                    Login to Admin Panel

                </button>

            </form>


            <div class="back-link">

                <a href="{{ route('login') }}">

                    <i class="bi bi-arrow-left me-1"></i>

                    Student Login

                </a>

            </div>

        </div>

    </div>


    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}">
    </script>

</body>

</html>