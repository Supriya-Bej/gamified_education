<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    /**
     * Supported models in priority order for resilience.
     */
    protected array $models = [
        'gemini-flash-lite-latest',
        'gemini-3.5-flash-lite',
        'gemini-flash-latest',
        'gemini-3.8-flash',
    ];

    /**
     * Perform generateContent call against Gemini API with automatic model fallback.
     */
    protected function callGemini(string $prompt, float $temperature = 0.7): ?array
    {
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            Log::warning('Gemini API key is not configured.');
            return null;
        }

        foreach ($this->models as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $response = Http::timeout(12)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => 1500,
                    ]
                ]);

                if ($response->successful()) {
                    $text = $response->json('candidates.0.content.parts.0.text');
                    if ($text) {
                        $cleaned = trim($text);
                        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $cleaned);
                        $cleaned = preg_replace('/\s*```$/i', '', $cleaned);

                        $data = json_decode($cleaned, true);
                        if (is_array($data)) {
                            return $data;
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Gemini model {$model} call failed: " . $e->getMessage());
                continue;
            }
        }

        return null;
    }

    /**
     * Generate dynamic AI Profile for student dashboard based on interests and goals.
     */
    public function generateProfile(
        array $interests,
        string $learningGoal,
        string $experienceLevel
    ): array {
        $interestsList = implode(', ', $interests);
        $hasChess = false;
        foreach ($interests as $item) {
            if (stripos($item, 'chess') !== false) {
                $hasChess = true;
                break;
            }
        }

        $prompt = "
You are the AI personalization engine for a gamified education platform.
Create a rich, gamified learning profile for a student based on their preferences.

Student Interests: {$interestsList}
Learning Goal: {$learningGoal}
Experience Level: {$experienceLevel}

Theme Selection Rules:
1. If the student has chosen Chess or chess strategy:
   - visual_theme MUST be 'chess'
   - primary_color: '#0a0a0c' (deep obsidian/onyx)
   - secondary_color: '#ffffff' (crisp white)
   - accent_color: '#94a3b8' (silver/slate)
   - color_mode: 'dark'
   - rank_title: 'Knight Tactician' (or 'Grandmaster Candidate' / 'Tactical Prodigy')
   - dashboard_title: 'The Grandmaster Arena'
   - tagline: 'Outthink. Outplay. Level Up.'
   - daily_quest: 'Solve today\'s tactical puzzle and master the board.'
   - motto: 'Every move counts in the game of knowledge.'
2. Otherwise, select the strongest matching theme from:
   chess, technology, music, sports, science, mathematics, creative, reading, business, gaming, general.

Return ONLY raw valid JSON (no markdown formatting, no code fences):
{
    \"visual_theme\": \"chess\",
    \"dashboard_title\": \"The Grandmaster Arena\",
    \"tagline\": \"Outthink. Outplay. Level Up.\",
    \"welcome_message\": \"Welcome to the Grandmaster Arena! Plan your strategy, anticipate challenges, and make your winning move.\",
    \"rank_title\": \"Knight Tactician\",
    \"motto\": \"Every move counts in the game of knowledge.\",
    \"primary_color\": \"#0a0a0c\",
    \"secondary_color\": \"#ffffff\",
    \"accent_color\": \"#94a3b8\",
    \"color_mode\": \"dark\",
    \"learning_style\": \"Analytical\",
    \"difficulty\": \"{$experienceLevel}\",
    \"daily_quest\": \"Solve today's opening tactical puzzle\",
    \"recommended_topics\": [
        \"Chess Tactics & Combinations\",
        \"Strategic Planning & Center Control\",
        \"Endgame Fundamentals\"
    ]
}
";

        $result = $this->callGemini($prompt);

        if ($result && isset($result['visual_theme'])) {
            return $result;
        }

        // Resilient fallback tailored to student's primary interest
        return $this->getFallbackProfile($interests, $learningGoal, $experienceLevel, $hasChess);
    }

    /**
     * Fallback profile if Gemini API is unreachable or rate limited.
     */
    protected function getFallbackProfile(
        array $interests,
        string $learningGoal,
        string $experienceLevel,
        bool $hasChess
    ): array {
        if ($hasChess) {
            return [
                'visual_theme' => 'chess',
                'dashboard_title' => 'The Grandmaster Arena',
                'tagline' => 'Outthink. Outplay. Level Up.',
                'welcome_message' => 'Welcome to the Grandmaster Arena! Plan your strategy, anticipate challenges, and make your winning move.',
                'rank_title' => 'Knight Tactician',
                'motto' => 'Every move counts in the game of knowledge.',
                'primary_color' => '#0a0a0c',
                'secondary_color' => '#ffffff',
                'accent_color' => '#94a3b8',
                'color_mode' => 'dark',
                'learning_style' => 'Analytical',
                'difficulty' => $experienceLevel,
                'daily_quest' => "Analyze today's opening move and solve 3 tactical puzzles",
                'recommended_topics' => [
                    'Chess Tactics & Combinations',
                    'Strategic Planning & Board Vision',
                    'Classic Endgame Mastery'
                ],
            ];
        }

        // Generic / default theme
        return [
            'visual_theme' => 'general',
            'dashboard_title' => 'Personalized Learning Realm',
            'tagline' => 'Learn. Play. Challenge. Earn.',
            'welcome_message' => 'Welcome to your personalized gamified learning world!',
            'rank_title' => 'Curious Explorer',
            'motto' => 'Level up your knowledge one quest at a time.',
            'primary_color' => '#172554',
            'secondary_color' => '#22c55e',
            'accent_color' => '#38bdf8',
            'color_mode' => 'dark',
            'learning_style' => 'Interactive',
            'difficulty' => $experienceLevel,
            'daily_quest' => 'Complete your daily learning warmup challenge',
            'recommended_topics' => $interests,
        ];
    }

    /**
     * Generate gamified personalized learning tasks.
     */
    public function generateTasks(
        array $interests,
        string $learningGoal,
        string $experienceLevel,
        int $taskCount = 5
    ): array {
        $interestsList = implode(', ', $interests);

        $prompt = "
You are an AI learning task generator for a gamified education platform.
Create {$taskCount} personalized gamified learning tasks for a student.

Student Interests: {$interestsList}
Learning Goal: {$learningGoal}
Experience Level: {$experienceLevel}

Requirements:
1. Tasks must connect directly to the student's interests (e.g. if interest includes Chess, include chess tactical quests, opening principles, calculation exercises).
2. Give each task a fun gamified title, clear description, XP reward (10 to 50 XP), and category.

Return ONLY valid raw JSON:
{
    \"tasks\": [
        {
            \"title\": \"Knight\'s Fork Discovery\",
            \"description\": \"Identify and practice the devastating double-attack pattern with knights.\",
            \"category\": \"Tactics\",
            \"difficulty\": \"beginner\",
            \"xp\": 25
        }
    ]
}
";

        $result = $this->callGemini($prompt);

        if ($result && isset($result['tasks']) && is_array($result['tasks'])) {
            return $result;
        }

        // Fallback tasks
        $hasChess = false;
        foreach ($interests as $item) {
            if (stripos($item, 'chess') !== false) {
                $hasChess = true;
                break;
            }
        }

        if ($hasChess) {
            return [
                'tasks' => [
                    [
                        'title' => 'Opening Principles Gambit',
                        'description' => 'Master the core concepts of piece development, center control, and king safety.',
                        'category' => 'Strategy',
                        'difficulty' => $experienceLevel,
                        'xp' => 30,
                    ],
                    [
                        'title' => 'Tactical Fork & Pin Challenge',
                        'description' => 'Spot tactics that trap opponent pieces using skewers, forks, and discovered attacks.',
                        'category' => 'Tactics',
                        'difficulty' => $experienceLevel,
                        'xp' => 25,
                    ],
                    [
                        'title' => 'Pawn Structure Analysis',
                        'description' => 'Learn how pawn islands, passed pawns, and chains dictate the tempo of the game.',
                        'category' => 'Mastery',
                        'difficulty' => $experienceLevel,
                        'xp' => 35,
                    ],
                ]
            ];
        }

        return [
            'tasks' => [
                [
                    'title' => 'Foundation Quest',
                    'description' => 'Explore the fundamentals of your key interest area.',
                    'category' => 'Core',
                    'difficulty' => $experienceLevel,
                    'xp' => 20,
                ],
                [
                    'title' => 'Skill Challenge',
                    'description' => 'Complete a hands-on exercise to put your learning into practice.',
                    'category' => 'Practice',
                    'difficulty' => $experienceLevel,
                    'xp' => 30,
                ],
            ]
        ];
    }
}
