<?php

namespace App\Http\Controllers;

use App\Models\UserRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StudentLeaderboardController extends Controller
{
    public function index(): View
    {
        $student = Auth::guard('student')->user();

        $leaderboardQuery = UserRegister::query()
            ->leftJoin('student_progress', 'user_registers.id', '=', 'student_progress.user_id')
            ->where('user_registers.role', 'student')
            ->select([
                'user_registers.id',
                'user_registers.name',
            ])
            ->selectRaw('COALESCE(student_progress.total_xp, 0) as total_xp')
            ->selectRaw(
                'ROW_NUMBER() OVER (
                    ORDER BY COALESCE(student_progress.total_xp, 0) DESC,
                    user_registers.id ASC
                ) as leaderboard_rank'
            );

        $rankedStudents = DB::query()->fromSub($leaderboardQuery, 'ranked_students');
        $userRank = (clone $rankedStudents)
            ->where('id', $student->id)
            ->value('leaderboard_rank');
        $students = $rankedStudents
            ->orderBy('leaderboard_rank')
            ->paginate(20);

        return view('student.leaderboard.index', compact('students', 'userRank'));
    }
}
