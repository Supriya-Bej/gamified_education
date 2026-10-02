<?php

namespace App\Services;

use App\Models\LearningTask;
use App\Models\StudentProgress;

class QuestRewardService
{
    public function award($user, LearningTask $task, int $xp): void
    {
        $interest = strtolower(trim($task->category ?? 'general'));

        $progress = StudentProgress::firstOrCreate(
            ['user_id' => $user->id],
            ['total_xp' => 0, 'level' => 1, 'completed_tasks' => 0, 'subject_xp' => []]
        );

        $subjectXp = $progress->subject_xp ?? [];

        if (!isset($subjectXp[$interest])) {
            $subjectXp[$interest] = ['xp' => 0, 'level' => 1, 'completed_tasks' => 0];
        }

        $subjectXp[$interest]['xp'] += $xp;
        $subjectXp[$interest]['completed_tasks']++;
        $subjectXp[$interest]['level'] = $this->calculateLevel($subjectXp[$interest]['xp']);

        $progress->total_xp += $xp;
        $progress->completed_tasks++;
        $progress->level = $this->calculateLevel($progress->total_xp);
        $progress->subject_xp = $subjectXp;
        $progress->save();

        $task->status = 'completed';
        $task->save();
    }

    private function calculateLevel(int $xp): int
    {
        if ($xp >= 100) return 3;
        if ($xp >= 50) return 2;
        return 1;
    }
}