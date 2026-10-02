@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('page-heading', 'Dashboard')

@section('content')

<div class="mb-4">

    <h3 class="fw-bold mb-1">
        Welcome back, {{ Auth::guard('admin')->user()->name }} 👋
    </h3>

    <p class="text-muted mb-0">
        Here is what's happening inside EcoQuest today.
    </p>

</div>


<div class="row g-4">

    <!-- STUDENTS -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="text-muted mb-2">
                            Total Students
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalStudents }}
                        </h2>

                    </div>

                    <div class="fs-2 text-success">
                        <i class="bi bi-people-fill"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- GAMES -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="text-muted mb-2">
                            Total Games
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalGames }}
                        </h2>

                    </div>

                    <div class="fs-2 text-primary">
                        <i class="bi bi-controller"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- TASKS -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="text-muted mb-2">
                            Total Tasks
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $totalTasks }}
                        </h2>

                    </div>

                    <div class="fs-2 text-warning">
                        <i class="bi bi-journal-text"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- COMPLETED -->

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <p class="text-muted mb-2">
                            Completed Tasks
                        </p>

                        <h2 class="fw-bold mb-0">
                            {{ $completedTasks }}
                        </h2>

                    </div>

                    <div class="fs-2 text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- SECOND ROW -->

<div class="row g-4 mt-1">


    <!-- PENDING TASKS -->

    <div class="col-lg-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <div class="d-flex justify-content-between">

                    <div>

                        <h5 class="fw-bold">
                            Pending Learning Tasks
                        </h5>

                        <p class="text-muted mb-0">
                            Tasks waiting for students.
                        </p>

                    </div>

                    <i class="bi bi-hourglass-split fs-3 text-warning"></i>

                </div>

                <div class="mt-4">

                    <h1 class="fw-bold">
                        {{ $pendingTasks }}
                    </h1>

                    <span class="badge bg-warning-subtle text-warning-emphasis">
                        Pending
                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- QUICK ACTIONS -->

    <div class="col-lg-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body p-4">

                <h5 class="fw-bold mb-3">
                    Quick Actions
                </h5>

                <div class="d-flex flex-wrap gap-2">

                    <a href="#"
                       class="btn btn-success">

                        <i class="bi bi-controller me-1"></i>

                        Manage Games

                    </a>

                    <a href="#"
                       class="btn btn-outline-primary">

                        <i class="bi bi-people me-1"></i>

                        Students

                    </a>

                    <a href="#"
                       class="btn btn-outline-dark">

                        <i class="bi bi-robot me-1"></i>

                        AI Tasks

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection