<aside class="sidebar">


    {{-- =====================================================
         BRAND
    ====================================================== --}}

    <a
        href="{{ route('student.dashboard') }}"
        class="brand">


        <div class="brand-icon">

            {{ $themeIcon ?? '✦' }}

        </div>


        <div>

            <div class="brand-title">

                Eco<span>Quest</span>

            </div>


            <div class="brand-subtitle">

                Gamified Learning

            </div>

        </div>


    </a>



    {{-- =====================================================
         MAIN MENU
    ====================================================== --}}

    <div class="nav-category">

        Main Menu

    </div>


    <a
        href="{{ route('student.dashboard') }}"
        class="side-link
        {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">

        <i class="bi bi-grid-1x2-fill"></i>

        <span>Dashboard</span>

    </a>



    <a
        href="{{ route('student.profile') }}"
        class="side-link
        {{ request()->routeIs('student.profile') ? 'active' : '' }}">

        <i class="bi bi-person-circle"></i>

        <span>My Profile</span>

    </a>



    <a
        href="{{ route('student.badges') }}"
        class="side-link
        {{ request()->routeIs('student.badges') ? 'active' : '' }}">

        <i class="bi bi-award-fill"></i>

        <span>My Badges</span>

    </a>



    <a
        href="{{ route('student.dashboard') }}#quests"
        class="side-link">

        <i class="bi bi-trophy-fill"></i>

        <span>
            {{ $labels['mission_title'] ?? 'Learning Quests' }}
        </span>

    </a>



    <a
        href="{{ route('student.progress') }}#progress"
        class="side-link">
        <i class="bi bi-graph-up-arrow"></i>
        <span>My Progress</span>
    </a>



    <a
        href="{{ route('student.dashboard') }}#topics"
        class="side-link">

        <i class="bi bi-lightbulb-fill"></i>

        <span>AI Topics</span>

    </a>



    {{-- =====================================================
         IDENTITY
    ====================================================== --}}

    <div class="nav-category mt-3">

        Identity

    </div>


    <div class="identity-box">


        <div class="identity-top">


            <span class="identity-icon">

                {{ $themeIcon ?? '✦' }}

            </span>


            <span class="identity-rank">

                {{ $rankTitle ?? 'Explorer' }}

            </span>


        </div>



        <div class="identity-theme">

            {{ ucfirst($theme ?? 'general') }}

            Theme

            <span>•</span>

            {{ ucfirst($difficulty ?? 'beginner') }}

        </div>


    </div>



    {{-- =====================================================
         LOGOUT
    ====================================================== --}}

    <div class="sidebar-bottom">


        <form
            action="{{ route('logout-user') }}"
            method="POST">

            @csrf


            <button
                type="submit"
                class="side-link logout-link">

                <i class="bi bi-box-arrow-right"></i>

                <span>Log Out</span>

            </button>


        </form>


    </div>


</aside>