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

    public function progressIndex()
    {
        $students = UserRegister::query()
            ->where('role', 'student')
            ->with('progress')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.student-progress.index', compact('students'));
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
            ->paginate(10, ['*'], 'completed_page')
            ->withQueryString();

        $pendingTasks = \App\Models\LearningTask::where('user_id', $student->id)
            ->where('status', 'pending')
            ->latest()
            ->paginate(10, ['*'], 'pending_page')
            ->withQueryString();

        return view('admin.students.show', compact(
            'student',
            'completedTasks',
            'pendingTasks'
        ));
    }
}
