@extends('admin.layouts.app')

@section('title', 'Leaderboard')
@section('page-heading', 'Leaderboard Management')

@section('content')
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3 p-4">
            <div>
                <h2 class="h5 fw-bold mb-1">Student rankings</h2>
                <p class="text-muted mb-0">Ranked by XP. Corrections require a reason and are recorded in the audit history.</p>
            </div>
            <form method="GET" action="{{ route('admin.leaderboard.index') }}" class="d-flex gap-2">
                <label for="leaderboard-search" class="visually-hidden">Search students</label>
                <input id="leaderboard-search" type="search" name="search" value="{{ $search }}"
                    class="form-control" placeholder="Name or email" maxlength="100">
                <button class="btn btn-outline-primary" type="submit">Search</button>
                @if($search !== '')
                    <a class="btn btn-outline-secondary" href="{{ route('admin.leaderboard.index') }}">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Rank</th>
                        <th>Student</th>
                        <th>Current XP</th>
                        <th>Adjust XP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td class="px-4 fw-bold">#{{ number_format($student->leaderboard_rank) }}</td>
                            <td>
                                <div class="fw-semibold">{{ $student->name }}</div>
                                <div class="small text-muted">{{ $student->email }}</div>
                            </td>
                            <td class="fw-semibold">{{ number_format($student->total_xp) }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.leaderboard.xp.adjust', ['student' => $student->id, 'search' => $search]) }}"
                                    class="d-flex flex-wrap gap-2">
                                    @csrf
                                    <label for="xp-change-{{ $student->id }}" class="visually-hidden">XP adjustment for {{ $student->name }}</label>
                                    <input id="xp-change-{{ $student->id }}" type="number" name="xp_change"
                                        class="form-control form-control-sm" style="max-width:110px;"
                                        min="-2147483647" max="2147483647" placeholder="+/- XP" required>
                                    <label for="xp-reason-{{ $student->id }}" class="visually-hidden">Reason for adjustment for {{ $student->name }}</label>
                                    <input id="xp-reason-{{ $student->id }}" type="text" name="reason"
                                        class="form-control form-control-sm" style="max-width:220px;"
                                        maxlength="500" placeholder="Reason" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Record</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                {{ $search !== '' ? 'No matching students found.' : 'No students are available yet.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($students->hasPages())
            <div class="card-footer bg-white">
                {{ $students->links() }}
            </div>
        @endif
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 p-4">
            <h2 class="h5 fw-bold mb-1">XP adjustment history</h2>
            <p class="text-muted mb-0">Recent admin corrections with the reason and administrator recorded.</p>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Date</th>
                        <th>Student</th>
                        <th>Change</th>
                        <th>Reason</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adjustments as $adjustment)
                        <tr>
                            <td class="px-4">{{ $adjustment->created_at->format('M j, Y H:i') }}</td>
                            <td>{{ $adjustment->student?->name ?? 'Deleted student' }}</td>
                            <td class="{{ $adjustment->xp_change > 0 ? 'text-success' : 'text-danger' }} fw-semibold">
                                {{ $adjustment->xp_change > 0 ? '+' : '' }}{{ number_format($adjustment->xp_change) }} XP
                            </td>
                            <td>{{ $adjustment->reason }}</td>
                            <td>{{ $adjustment->admin?->name ?? 'Deleted admin' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No XP adjustments have been recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($adjustments->hasPages())
            <div class="card-footer bg-white">
                {{ $adjustments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
