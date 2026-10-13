<?php

namespace Database\Seeders;

use App\Models\Badge;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            [
                'name' => 'First Quest',
                'description' => 'Complete your first learning quest.',
                'icon' => '🌱',
                'requirement_type' => 'completed_tasks',
                'requirement_value' => 1,
            ],
            [
                'name' => 'Quest Explorer',
                'description' => 'Complete 3 learning quests and keep exploring.',
                'icon' => '🧭',
                'requirement_type' => 'completed_tasks',
                'requirement_value' => 3,
            ],
            [
                'name' => 'Quest Champion',
                'description' => 'Complete 10 learning quests through practice and persistence.',
                'icon' => '🏆',
                'requirement_type' => 'completed_tasks',
                'requirement_value' => 10,
            ],
            [
                'name' => 'XP Collector',
                'description' => 'Earn 100 XP by completing learning challenges.',
                'icon' => '⚡',
                'requirement_type' => 'total_xp',
                'requirement_value' => 100,
            ],
            [
                'name' => 'Knowledge Seeker',
                'description' => 'Earn 500 XP as you build your skills.',
                'icon' => '📚',
                'requirement_type' => 'total_xp',
                'requirement_value' => 500,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::firstOrCreate(
                ['name' => $badge['name']],
                [...$badge, 'is_active' => true]
            );
        }
    }
}
