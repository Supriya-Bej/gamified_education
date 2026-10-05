<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'EcoQuest')
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
         STUDENT SIDEBAR CSS
    ====================================================== --}}

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }


        /* =================================================
           SIDEBAR
        ================================================= */

        .sidebar {

            position: fixed;

            top: 0;
            left: 0;
            bottom: 0;

            width: 260px;

            background: #111827;

            color: #ffffff;

            padding: 22px 16px;

            overflow-y: auto;

            z-index: 1000;
        }


        /* =================================================
           BRAND
        ================================================= */

        .brand {

            display: flex;

            align-items: center;

            gap: 12px;

            color: #ffffff;

            text-decoration: none;

            padding: 8px 10px 25px;

            border-bottom: 1px solid rgba(255,255,255,0.08);

            margin-bottom: 25px;
        }


        .brand-icon {

            width: 42px;
            height: 42px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 12px;

            background: #2563eb;

            font-size: 22px;

            flex-shrink: 0;
        }


        .brand-title {

            font-size: 20px;

            font-weight: 800;

            line-height: 1.1;
        }


        .brand-title span {

            color: #60a5fa;
        }


        .brand-subtitle {

            font-size: 11px;

            color: #9ca3af;

            margin-top: 4px;
        }


        /* =================================================
           CATEGORY
        ================================================= */

        .nav-category {

            color: #6b7280;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;

            letter-spacing: 1px;

            padding: 0 10px;

            margin-bottom: 10px;
        }


        /* =================================================
           SIDEBAR LINKS
        ================================================= */

        .side-link {

            width: 100%;

            display: flex;

            align-items: center;

            gap: 12px;

            padding: 12px 13px;

            margin-bottom: 5px;

            border-radius: 10px;

            color: #cbd5e1;

            text-decoration: none;

            font-size: 14px;

            transition: 0.2s ease;

            border: 0;

            background: transparent;

            text-align: left;

            cursor: pointer;
        }


        .side-link i {

            width: 20px;

            font-size: 17px;

            text-align: center;
        }


        .side-link:hover {

            color: #ffffff;

            background: rgba(255,255,255,0.08);
        }


        .side-link.active {

            color: #ffffff;

            background: #2563eb;

            box-shadow: 0 6px 15px rgba(37,99,235,0.25);
        }


        /* =================================================
           IDENTITY
        ================================================= */

        .identity-box {

            background: rgba(255,255,255,0.05);

            border: 1px solid rgba(255,255,255,0.07);

            border-radius: 14px;

            padding: 14px;

            margin-top: 8px;
        }


        .identity-top {

            display: flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 8px;
        }


        .identity-icon {

            font-size: 18px;
        }


        .identity-rank {

            font-size: 13px;

            font-weight: 700;

            color: #ffffff;
        }


        .identity-theme {

            color: #9ca3af;

            font-size: 11px;
        }


        /* =================================================
           LOGOUT
        ================================================= */

        .sidebar-bottom {

            margin-top: 25px;

            padding-top: 15px;

            border-top: 1px solid rgba(255,255,255,0.08);
        }


        .logout-link {

            color: #fca5a5;
        }


        .logout-link:hover {

            color: #ffffff;

            background: rgba(239,68,68,0.15);
        }


        /* =================================================
           MAIN CONTENT
        ================================================= */

        .student-main {

            margin-left: 260px;

            min-height: 100vh;

            padding: 0;
        }


        /* =================================================
           MOBILE
        ================================================= */

        @media (max-width: 991px) {

            .sidebar {

                width: 230px;
            }

            .student-main {

                margin-left: 230px;
            }

        }


        @media (max-width: 767px) {

            .sidebar {

                position: relative;

                width: 100%;

                min-height: auto;

                max-height: none;
            }


            .student-main {

                margin-left: 0;
            }

        }

    </style>


    @yield('head')

</head>


<body>


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

    <script src="{{ asset('Asset/Bootstrap-5/js/bootstrap.bundle.min.js') }}">
    </script>


    @yield('scripts')

</body>

</html>