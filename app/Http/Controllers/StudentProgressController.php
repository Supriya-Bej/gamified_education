<?php

namespace App\Http\Controllers;

use App\Models\LearningTask;
use App\Models\StudentProgress;
use App\Models\StudentBadge;
use App\Models\LearningMaterialProgress;
use Illuminate\Support\Facades\Auth;

class StudentProgressController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        /*Student Progress*/
        $progress = StudentProgress::firstOrCreate(
            [
                'user_id' => $student->id,
            ],
            [
                'total_xp' => 0,
                'level' => 1,
                'completed_tasks' => 0,
                'subject_xp' => [],
            ]
        );

        /*Completed Quests*/
        $completedTasks = LearningTask::where('user_id', $student->id)
            ->where('status', 'completed')
            ->latest('updated_at')
            ->get();

        /*Earned Badges*/
        $badges = StudentBadge::with('badge')
            ->where('user_id', $student->id)
            ->latest('awarded_at')
            ->get();

        $completedLearningMaterials = LearningMaterialProgress::with(
            'learningMaterial'
        )
            ->where('user_id', $student->id)
            ->whereNotNull('completed_at')
            ->latest('completed_at')
            ->get();

        /*XP Progress*/
        $currentLevel = max(1, (int) $progress->level);
        $currentLevelXp = ($currentLevel - 1) * 100;
        $nextLevelXp = $currentLevel * 100;
        $xpIntoLevel = max(
            0,
            $progress->total_xp - $currentLevelXp
        );

        $xpNeededForLevel = 100;

        $xpPercentage = min(
            100,
            ($xpIntoLevel / $xpNeededForLevel) * 100
        );

        return view(
            'student.progress.index',
            compact(
                'progress',
                'completedTasks',
                'badges',
                'completedLearningMaterials',
                'currentLevel',
                'currentLevelXp',
                'nextLevelXp',
                'xpIntoLevel',
                'xpNeededForLevel',
                'xpPercentage'
            )
        );
    }
}
