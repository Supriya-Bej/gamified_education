@extends('admin.layouts.app')

@section('title', 'XP & Rewards')

@section('page-heading', 'XP & Rewards Management')

@section('content')

<div class="container-fluid">

    {{-- Summary Cards --}}

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total XP Earned
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalXp) }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10
                                    text-primary rounded-3 p-3">

                            <i class="bi bi-stars fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Students With XP
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $studentsWithXp }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10
                                    text-success rounded-3 p-3">

                            <i class="bi bi-people fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Completed Tasks
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($completedTasks) }}
                            </h3>

                        </div>

                        <div class="bg-warning bg-opacity-10
                                    text-warning rounded-3 p-3">

                            <i class="bi bi-check2-circle fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Average XP
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($averageXp ?? 0, 0) }}
                            </h3>

                        </div>

                        <div class="bg-info bg-opacity-10
                                    text-info rounded-3 p-3">

                            <i class="bi bi-graph-up-arrow fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Student XP Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 p-4">

            <div>

                <h4 class="fw-bold mb-1">
                    Student XP Progress
                </h4>

                <p class="text-muted mb-0">
                    Monitor XP, levels and completed learning tasks.
                </p>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-4">
                                Student
                            </th>

                            <th>
                                Level
                            </th>

                            <th>
                                Total XP
                            </th>

                            <th>
                                Completed Tasks
                            </th>

                            <th>
                                Progress
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($students as $student)

                        @php

                        $progress = $student->progress;

                        $level = $progress->level ?? 1;

                        $xp = $progress->total_xp ?? 0;

                        $completed = $progress->completed_tasks ?? 0;

                        /*
                        * Simple visual progress.
                        * This is only for the admin display.
                        */
                        $levelBaseXp = max(($level - 1) * 100, 0);

                        $nextLevelXp = $level * 100;

                        $levelProgress = $nextLevelXp > $levelBaseXp
                        ? (($xp - $levelBaseXp) /
                        ($nextLevelXp - $levelBaseXp)) * 100
                        : 0;

                        $levelProgress = max(
                        0,
                        min(100, $levelProgress)
                        );

                        @endphp


                        <tr>

                            {{-- Student --}}

                            <td class="px-4">

                                <div class="d-flex align-items-center gap-3">

                                    <div class="rounded-circle
                                                    bg-primary
                                                    text-white
                                                    d-flex
                                                    align-items-center
                                                    justify-content-center"
                                        style="width:42px;height:42px;">

                                        {{ strtoupper(
                                                substr($student->name, 0, 1)
                                            ) }}

                                    </div>

                                    <div>

                                        <div class="fw-semibold">
                                            {{ $student->name }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $student->email }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- Level --}}

                            <td>

                                <span class="badge bg-dark">
                                    Level {{ $level }}
                                </span>

                            </td>


                            {{-- XP --}}

                            <td>

                                <span class="fw-bold text-primary">

                                    <i class="bi bi-stars me-1"></i>

                                    {{ number_format($xp) }} XP

                                </span>

                            </td>


                            {{-- Completed --}}

                            <td>

                                <span class="fw-semibold">

                                    {{ $completed }}

                                </span>

                                tasks

                            </td>


                            {{-- Progress --}}

                            <td style="min-width:180px;">

                                <div class="d-flex justify-content-between
                                                small mb-1">

                                    <span class="text-muted">
                                        Level progress
                                    </span>

                                    <span class="fw-semibold">
                                        {{ round($levelProgress) }}%
                                    </span>

                                </div>

                                <div class="progress"
                                    style="height:7px;">

                                    <div class="progress-bar"
                                        role="progressbar"
                                        @style(['width' => $levelProgress . '%'])>

                                    </div>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5"
                                class="text-center py-5">

                                <i class="bi bi-stars fs-1 text-muted"></i>

                                <h5 class="mt-3">
                                    No XP Data Found
                                </h5>

                                <p class="text-muted mb-0">
                                    Student progress will appear here.
                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($students->hasPages())

        <div class="card-footer bg-white border-0 p-4">

            {{ $students->links() }}

        </div>

        @endif

    </div>

</div>

@endsection