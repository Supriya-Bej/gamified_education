@extends('student.layouts.app')

@section('title', 'Leaderboard | EcoQuest')

@section('content')
<section class="theme-page">
    <div class="container-fluid py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1" style="color:var(--secondary);">Leaderboard</h1>
                <p class="mb-0" style="color:var(--text-muted);">Students ranked by total XP earned.</p>
            </div>
            <div class="rounded-3 px-4 py-3" style="background:var(--surface); border:1px solid var(--border);">
                <span style="color:var(--text-muted);">Your rank</span>
                <strong class="ms-2" style="color:var(--secondary);">
                    #{{ number_format($userRank ?? 0) }}
                </strong>
            </div>
        </div>

        <div class="rounded-4 overflow-hidden" style="background:var(--surface); border:1px solid var(--border);">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr style="color:var(--text-muted); border-color:var(--border);">
                            <th scope="col" class="px-4 py-3">Rank</th>
                            <th scope="col" class="py-3">Student</th>
                            <th scope="col" class="px-4 py-3 text-end">Total XP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $leaderboardStudent)
                            <tr style="color:var(--text-main); border-color:var(--border);">
                                <th scope="row" class="px-4">
                                    #{{ number_format($leaderboardStudent->leaderboard_rank) }}
                                </th>
                                <td>
                                    {{ $leaderboardStudent->name }}
                                    @if((int) $leaderboardStudent->id === (int) auth('student')->id())
                                        <span class="badge rounded-pill ms-2" style="background:var(--primary);">You</span>
                                    @endif
                                </td>
                                <td class="px-4 text-end fw-bold" style="color:var(--secondary);">
                                    {{ number_format($leaderboardStudent->total_xp) }} XP
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5" style="color:var(--text-muted);">
                                    No students are on the leaderboard yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="p-3" style="border-top:1px solid var(--border);">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
