<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserRegister;
use App\Models\StudentProgress;

class StudentManagementController extends Controller
{
    public function index()
    {
        $students = UserRegister::where('role', 'student')
            ->with('progress')
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }

    public function show(UserRegister $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        $student->load('progress');

        $completedTasks = \App\Models\LearningTask::where('user_id', $student->id)
            ->where('status', 'completed')
            ->latest()
            ->get();

        $pendingTasks = \App\Models\LearningTask::where('user_id', $student->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.students.show', compact(
            'student',
            'completedTasks',
            'pendingTasks'
        ));
    }
}
