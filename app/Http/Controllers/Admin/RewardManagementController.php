<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRegister;
use App\Models\StudentProgress;

class RewardManagementController extends Controller
{
    public function index()
    {
        $students = UserRegister::where('role', 'student')
            ->with('progress')
            ->whereHas('progress')
            ->latest()
            ->paginate(10);

        $totalXp = StudentProgress::sum('total_xp');

        $studentsWithXp = StudentProgress::where('total_xp', '>', 0)
            ->count();

        $completedTasks = StudentProgress::sum('completed_tasks');

        $averageXp = StudentProgress::avg('total_xp');

        return view('admin.rewards.index', compact(
            'students',
            'totalXp',
            'studentsWithXp',
            'completedTasks',
            'averageXp'
        ));
    }
}