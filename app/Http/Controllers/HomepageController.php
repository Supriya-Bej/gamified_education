<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameEngine;
use App\Models\LearningTask;
use App\Models\ProjectChallenge;
use App\Models\SiteSetting;
use Illuminate\View\View;

class HomepageController extends Controller
{
    public function show(): View
    {
        $defaults = $this->defaults();
        $savedContent = SiteSetting::query()
            ->where('key', 'homepage')
            ->first()
            ?->value ?? [];

        return view('user.index', [
            'content' => array_replace($defaults, $savedContent),
            'stats' => $this->stats(),
        ]);
    }

    private function stats(): array
    {
        $enabledEngines = GameEngine::query()
            ->where('is_enabled', true)
            ->select('engine_key');
        $availableGames = Game::query()->whereIn('game_type', $enabledEngines);

        return [
            [
                'value' => (clone $availableGames)->distinct()->count('category'),
                'label' => 'Learning Areas',
            ],
            [
                'value' => (clone $availableGames)->count(),
                'label' => 'Games & Quizzes',
            ],
            [
                'value' => LearningTask::query()
                    ->where('review_status', 'approved')
                    ->distinct()
                    ->count('title')
                    + ProjectChallenge::query()
                        ->where('review_status', 'approved')
                        ->distinct()
                        ->count('title'),
                'label' => 'Challenges',
            ],
            [
                'value' => '∞',
                'label' => 'Ways to Grow',
            ],
        ];
    }

    private function defaults(): array
    {
        $defaults = [];

        foreach (config('homepage.sections') as $fields) {
            foreach ($fields as $key => $field) {
                $defaults[$key] = $field['default'];
            }
        }

        return $defaults;
    }
}
