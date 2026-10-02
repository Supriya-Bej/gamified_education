<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningTask;

class LearningTaskManagementController extends Controller
{
    public function index()
    {
        $tasks = LearningTask::with([
            'user',
            'game'
        ])
        ->latest()
        ->paginate(15);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function show(LearningTask $task)
    {
        $task->load([
            'user',
            'game'
        ]);

        return view('admin.tasks.show', compact('task'));
    }
}