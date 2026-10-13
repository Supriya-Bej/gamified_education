<?php

namespace App\Services;

use App\Models\Badge;
use App\Models\LearningTask;
use App\Models\StudentBadge;
use App\Models\StudentProgress;
use App\Models\UserRegister;

class BadgeService
{
    /**
     * Check and award all eligible badges for a student.
     */
    public function checkAndAward(UserRegister $student): array
    {
        $progress = StudentProgress::where('user_id', $student->id)->first();

        if (!$progress) {
            return [];
        }

        $badges = Badge::where('is_active', true)->get();
        $completedTaskCount = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'completed')
            ->count();

        $awardedBadges = [];

        foreach ($badges as $badge) {
            $eligible = match ($badge->requirement_type) {
                'total_xp' => $progress->total_xp >= $badge->requirement_value,
                'completed_tasks' => $completedTaskCount >= $badge->requirement_value,
                default => false,
            };

            if ($eligible) {
                $studentBadge = StudentBadge::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'badge_id' => $badge->id,
                    ],
                    ['awarded_at' => now()]
                );

                if (! $studentBadge->wasRecentlyCreated) {
                    continue;
                }
                $awardedBadges[] = $badge;
            }
        }

        return $awardedBadges;
    }
}