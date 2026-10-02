<?php

namespace App\Services;

use App\Models\Badge;
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

        $awardedBadges = [];

        foreach ($badges as $badge) {

            // Check whether the student already has this badge.
            $alreadyAwarded = StudentBadge::where('user_id', $student->id)
                ->where('badge_id', $badge->id)
                ->exists();

            if ($alreadyAwarded) {
                continue;
            }

            $eligible = false;

            // Check badge requirement.
            if ($badge->requirement_type === 'total_xp') {

                $eligible = $progress->total_xp >= $badge->requirement_value;

            } elseif ($badge->requirement_type === 'completed_tasks') {

                $eligible = $progress->completed_tasks >= $badge->requirement_value;
            }

            // Award badge.
            if ($eligible) {

                $studentBadge = StudentBadge::create([
                    'user_id' => $student->id,
                    'badge_id' => $badge->id,
                    'awarded_at' => now(),
                ]);

                $awardedBadges[] = $badge;
            }
        }

        return $awardedBadges;
    }
}