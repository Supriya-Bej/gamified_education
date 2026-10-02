<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRegister;
use App\Models\Game;
use App\Models\LearningTask;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalStudents = UserRegister::where('role', 'student')->count();

        $totalAdmins = UserRegister::where('role', 'admin')->count();

        $totalGames = Game::count();

        $totalTasks = LearningTask::count();

        $pendingTasks = LearningTask::where('status', 'pending')->count();

        $completedTasks = LearningTask::where('status', 'completed')->count();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalAdmins',
            'totalGames',
            'totalTasks',
            'pendingTasks',
            'completedTasks'
        ));
    }
}