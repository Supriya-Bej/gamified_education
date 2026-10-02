<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Game;

class GeminiService
{
    /**
     * Supported Gemini models in priority order.
     */
    protected array $models = [
        'gemini-flash-lite-latest',
        'gemini-3.5-flash-lite',
        'gemini-flash-latest',
        'gemini-3.8-flash',
    ];

    /**
     * Call Gemini API with automatic model fallback.
     */
    protected function callGemini(
        string $prompt,
        float $temperature = 0.7,
        int $timeout = 12,
        int $maxTokens = 1500
    ): ?array {
        $apiKey = config('services.gemini.api_key');

        if (!$apiKey) {
            Log::warning('Gemini API key is not configured.');
            return null;
        }

        foreach ($this->models as $model) {
            try {

                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $response = Http::timeout($timeout)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                [
                                    'text' => $prompt
                                ]
                            ]
                        ]
                    ],

                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => $maxTokens,
                        'responseMimeType' => 'application/json',
                    ]
                ]);

                if ($response->successful()) {

                    $text = $response->json(
                        'candidates.0.content.parts.0.text'
                    );

                    if ($text) {

                        $cleaned = trim($text);

                        // Remove markdown code fences if Gemini returns them
                        $cleaned = preg_replace(
                            '/^```(?:json)?\s*/i',
                            '',
                            $cleaned
                        );

                        $cleaned = preg_replace(
                            '/\s*```$/i',
                            '',
                            $cleaned
                        );

                        $data = json_decode(
                            trim($cleaned),
                            true
                        );

                        if (is_array($data)) {
                            return $data;
                        }
                    }
                }
            } catch (\Throwable $e) {

                Log::warning(
                    "Gemini model {$model} call failed: " .
                        $e->getMessage()
                );

                continue;
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE CATEGORY
    |--------------------------------------------------------------------------
    */

    protected function normalizeCategory(string $category): string
    {
        $category = strtolower(trim($category));

        $aliases = [
            'math' => 'mathematics',
            'mathematics' => 'mathematics',

            'programming' => 'coding',
            'computer' => 'coding',
            'computers' => 'coding',
            'technology' => 'coding',
            'software' => 'coding',
            'web development' => 'coding',
            'coding' => 'coding',

            'song' => 'music',
            'songs' => 'music',
            'singing' => 'music',
            'music' => 'music',
            'guitar' => 'music',
            'piano' => 'music',

            'environmental education' => 'environment',
            'environment' => 'environment',

            'geo' => 'geography',
            'geography' => 'geography',

            'history' => 'history',
            'world history' => 'history',

            'psychology' => 'psychology',

            'self improvement' => 'self_improvement',
            'self-improvement' => 'self_improvement',

            'art' => 'art',
            'drawing' => 'art',
            'painting' => 'art',
            'color theory' => 'art',
            'creative' => 'art',

            'science' => 'science',
            'physics' => 'science',
            'chemistry' => 'science',
            'biology' => 'science',

            'chess' => 'chess',
            'strategy' => 'chess',
        ];

        return $aliases[$category] ?? $category;
    }


    /*
    |--------------------------------------------------------------------------
    | GAME TYPE RULES
    |--------------------------------------------------------------------------
    |
    | This prevents Gemini from generating:
    |
    | music -> algorithm_race
    | geography -> creative_coding
    | math -> physics_quiz
    |
    */

    protected function getAllowedGameTypes(): array
    {
        return [

            'chess' => [
                'chess_tactics',
                'chess_opening',
                'chess_endgame',
            ],

            'mathematics' => [
                'arithmetic_speed',
                'geometry_area_perimeter',
                'fractions_challenge',
                'algebra_puzzle',
            ],

            'coding' => [
                'algorithm_race',
                'sorting_challenge',
                'creative_coding',
                'debugging_challenge',
            ],

            'science' => [
                'physics_quiz',
                'chemistry_lab',
                'biology_matching',
            ],

            'environment' => [
                'waste_sorting',
                'recycling_challenge',
                'water_conservation',
                'energy_saving',
            ],

            'geography' => [
                'geography_exploration',
                'map_navigation',
                'world_landmarks',
            ],

            'history' => [
                'history_exploration',
                'timeline_challenge',
                'historical_decision',
            ],

            'psychology' => [
                'psychology_scenario',
                'mindset_challenge',
                'decision_simulation',
            ],

            'self_improvement' => [
                'habit_builder',
                'goal_planner',
                'daily_routine_challenge',
            ],

            'music' => [
                'music_rhythm',
                'melody_challenge',
                'music_theory',
                'songwriting_challenge',
            ],

            'art' => [
                'color_matching',
                'pattern_design',
                'creative_drawing',
                'visual_composition',
            ],
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK CATEGORY + GAME TYPE COMPATIBILITY
    |--------------------------------------------------------------------------
    */

    protected function isGameTypeCompatible(
        string $category,
        string $gameType
    ): bool {

        $category = $this->normalizeCategory($category);

        $allowed = $this->getAllowedGameTypes();

        /*
        | If category has a known game registry,
        | game_type MUST belong to that category.
        */
        if (isset($allowed[$category])) {

            return in_array(
                $gameType,
                $allowed[$category],
                true
            );
        }

        /*
        | Unknown categories:
        | allow only if game_type starts with category.
        |
        | Example:
        | category = astronomy
        | game_type = astronomy_exploration
        */
        $prefix = str_replace(
            ['-', ' '],
            '_',
            strtolower($category)
        );

        return str_starts_with(
            $gameType,
            $prefix . '_'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE FALLBACK GAME TYPE
    |--------------------------------------------------------------------------
    */

    protected function getFallbackGameType(string $category): string
    {
        $category = $this->normalizeCategory($category);

        $fallbacks = [

            'chess' => 'chess_tactics',

            'mathematics' => 'arithmetic_speed',

            'coding' => 'algorithm_race',

            'science' => 'physics_quiz',

            'environment' => 'waste_sorting',

            'geography' => 'geography_exploration',

            'history' => 'history_exploration',

            'psychology' => 'psychology_scenario',

            'self_improvement' => 'habit_builder',

            'music' => 'music_rhythm',

            'art' => 'pattern_design',
        ];

        return $fallbacks[$category]
            ?? $category . '_challenge';
    }


    /**
     * Generate dynamic AI Profile for student dashboard.
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
- primary_color: '#0a0a0c'
- secondary_color: '#ffffff'
- accent_color: '#94a3b8'
- color_mode: 'dark'
- rank_title: 'Knight Tactician'
- dashboard_title: 'The Grandmaster Arena'
- tagline: 'Outthink. Outplay. Level Up.'
- daily_quest: 'Solve today\\'s tactical puzzle and master the board.'
- motto: 'Every move counts in the game of knowledge.'

2. Otherwise select the strongest matching theme from:

chess, technology, music, sports, science, mathematics, creative, reading, business, gaming, general.

Return ONLY valid JSON.

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

        return $this->getFallbackProfile(
            $interests,
            $learningGoal,
            $experienceLevel,
            $hasChess
        );
    }


    /**
     * Fallback profile.
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
     *
     * Gemini is only allowed to use game types that
     * actually exist in the games table.
     */
    public function generateTasks(
        array $interests,
        string $learningGoal,
        string $experienceLevel,
        int $taskCount = 5
    ): array {

        $interestsList = implode(', ', $interests);

        /*
    |--------------------------------------------------------------------------
    | GET AVAILABLE GAMES FROM DATABASE
    |--------------------------------------------------------------------------
    */

        $availableGames = Game::query()
            ->whereNotNull('game_type')
            ->where('game_type', '!=', '')
            ->get([
                'name',
                'category',
                'game_type',
            ]);

        /*
    |--------------------------------------------------------------------------
    | IF NO GAMES EXIST
    |--------------------------------------------------------------------------
    */

        if ($availableGames->isEmpty()) {
            Log::warning(
                'Gemini task generation skipped because no playable games exist.'
            );

            return [
                'tasks' => []
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | BUILD GAME CATALOG FOR GEMINI
    |--------------------------------------------------------------------------
    */

        $gameCatalog = $availableGames
            ->map(function ($game) {

                return [
                    'name' => $game->name,
                    'category' => strtolower(trim($game->category ?? 'general')),
                    'game_type' => strtolower(trim($game->game_type)),
                ];
            })
            ->values()
            ->toArray();

        $gameCatalogJson = json_encode(
            $gameCatalog,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );

        /*
    |--------------------------------------------------------------------------
    | AI PROMPT
    |--------------------------------------------------------------------------
    */

        $prompt = <<<PROMPT
You are an AI learning task generator for a gamified education platform.

Create exactly {$taskCount} personalized learning quests.

Student Interests:
{$interestsList}

Learning Goal:
{$learningGoal}

Experience Level:
{$experienceLevel}

IMPORTANT:

The platform has a fixed library of playable games.

You MUST choose game_type ONLY from the following available games:

{$gameCatalogJson}

You are NOT allowed to invent a new game_type.

You are NOT allowed to use a game_type that is not present in the list above.

You are NOT allowed to assign an unrelated game to a learning topic.

GAME MATCHING RULES:

1. The quest topic must match the selected game's category.

2. The quest title must match the selected game.

3. The quest description must match the selected game.

4. category and game_type must describe the same learning area.

5. Never use a coding game for a music quest.

6. Never use a mathematics game for a history quest.

7. Never use an unrelated game simply because it exists.

8. If a student's interest does not currently have a suitable playable game,
   DO NOT create an unrelated quest using another category.

9. game_type must exactly match one of the available game_type values.

10. category must match the category of the selected game.

For example, if the available game is:

{
    "name": "Creative Coding Challenge",
    "category": "coding",
    "game_type": "creative_coding"
}

Then an appropriate quest could be:

{
    "title": "Code Symphony Maestro",
    "description": "Create a visual pattern using programming logic and creative coding.",
    "category": "coding",
    "game_type": "creative_coding",
    "difficulty": "beginner",
    "xp": 30
}

But this would be WRONG:

{
    "title": "Rhythm Code Race",
    "description": "Learn music rhythm patterns.",
    "category": "music",
    "game_type": "algorithm_race"
}

because algorithm_race belongs to coding.

Each task MUST contain:

- title
- description
- category
- game_type
- difficulty
- xp

XP must be between 10 and 50.

The quests should be educational, practical, interactive,
and connected to real-world problem solving.

The title and description must clearly match the selected game.

game_type must:
- be lowercase
- use underscores
- exactly match an available game_type
- never be null
- never be empty
- never be invented

Return ONLY valid JSON.

Do not return markdown.
Do not return code fences.
Do not return explanations.
Do not return text before or after the JSON.

Return exactly this structure:

{
    "tasks": [
        {
            "title": "Example Quest",
            "description": "Complete a practical challenge using the selected game.",
            "category": "coding",
            "game_type": "creative_coding",
            "difficulty": "beginner",
            "xp": 30
        }
    ]
}
PROMPT;

        /*
    |--------------------------------------------------------------------------
    | CALL GEMINI
    |--------------------------------------------------------------------------
    */

        $result = $this->callGemini(
            $prompt,
            0.3,
            30,
            3000
        );

        /*
    |--------------------------------------------------------------------------
    | VALIDATE GEMINI RESPONSE
    |--------------------------------------------------------------------------
    */

        if (
            $result &&
            isset($result['tasks']) &&
            is_array($result['tasks'])
        ) {

            $validTasks = [];

            /*
        |--------------------------------------------------------------------------
        | CREATE DATABASE GAME MAP
        |--------------------------------------------------------------------------
        */

            $gameMap = [];

            foreach ($availableGames as $game) {

                $gameType = strtolower(
                    trim($game->game_type)
                );

                $gameMap[$gameType] = [
                    'category' => strtolower(
                        trim($game->category ?? 'general')
                    ),
                    'name' => $game->name,
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | VALIDATE EACH AI TASK
        |--------------------------------------------------------------------------
        */

            foreach ($result['tasks'] as $task) {

                if (!is_array($task)) {
                    continue;
                }

                $title = trim(
                    (string) ($task['title'] ?? '')
                );

                $description = trim(
                    (string) ($task['description'] ?? '')
                );

                $category = strtolower(
                    trim((string) ($task['category'] ?? ''))
                );

                $gameType = strtolower(
                    trim((string) ($task['game_type'] ?? ''))
                );

                $difficulty = strtolower(
                    trim(
                        (string) (
                            $task['difficulty']
                            ?? $experienceLevel
                        )
                    )
                );

                $xp = (int) ($task['xp'] ?? 20);

                /*
            |--------------------------------------------------------------------------
            | BASIC VALIDATION
            |--------------------------------------------------------------------------
            */

                if (
                    $title === '' ||
                    $description === '' ||
                    $gameType === ''
                ) {
                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | NORMALIZE GAME TYPE
            |--------------------------------------------------------------------------
            */

                $gameType = preg_replace(
                    '/[^a-z0-9_]+/',
                    '_',
                    $gameType
                );

                $gameType = trim(
                    strtolower($gameType),
                    '_'
                );

                /*
            |--------------------------------------------------------------------------
            | GAME TYPE MUST EXIST
            |--------------------------------------------------------------------------
            */

                if (!isset($gameMap[$gameType])) {
                    Log::warning(
                        "Gemini generated unsupported game_type: {$gameType}"
                    );

                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | CATEGORY MUST MATCH GAME CATEGORY
            |--------------------------------------------------------------------------
            */

                $databaseCategory = $gameMap[$gameType]['category'];

                if ($category !== $databaseCategory) {

                    Log::warning(
                        "Gemini generated category mismatch. " .
                            "game_type={$gameType}, " .
                            "AI category={$category}, " .
                            "database category={$databaseCategory}"
                    );

                    continue;
                }

                /*
            |--------------------------------------------------------------------------
            | NORMALIZE XP
            |--------------------------------------------------------------------------
            */

                $xp = max(
                    10,
                    min(50, $xp)
                );

                /*
            |--------------------------------------------------------------------------
            | ACCEPT VALID TASK
            |--------------------------------------------------------------------------
            */

                $validTasks[] = [
                    'title' => $title,
                    'description' => $description,
                    'category' => $category,
                    'game_type' => $gameType,
                    'difficulty' => $difficulty !== ''
                        ? $difficulty
                        : $experienceLevel,
                    'xp' => $xp,
                ];
            }

            /*
        |--------------------------------------------------------------------------
        | RETURN VALID TASKS
        |--------------------------------------------------------------------------
        */

            if (!empty($validTasks)) {

                return [
                    'tasks' => array_slice(
                        $validTasks,
                        0,
                        $taskCount
                    )
                ];
            }
        }

        /*
    |--------------------------------------------------------------------------
    | NO VALID TASKS
    |--------------------------------------------------------------------------
    */

        Log::warning(
            'Gemini returned no valid game-matched learning tasks.'
        );

        return [
            'tasks' => []
        ];
    }


    /**
     * Generate lesson + quiz for one quest.
     */
    public function generateQuestContent(
        string $title,
        string $description,
        string $difficulty,
        array $interests
    ): ?array {

        $interestsList = implode(', ', $interests);

        $prompt = <<<PROMPT
You are a teacher inside a gamified education platform.

Create learning content for this quest.

Quest Title: {$title}
Quest Description: {$description}
Difficulty: {$difficulty}
Student Interests: {$interestsList}

Requirements:

1. "lesson":

A clear, friendly lesson of 200-300 words with at least one concrete example.

Plain text only.

Separate paragraphs with a blank line.

No markdown symbols.

2. "quiz":

Exactly 5 multiple-choice questions based ONLY on the lesson.

Each question must have:

- 4 options
- "answer" as the zero-based index of the correct option
- a one-sentence explanation

Return ONLY raw valid JSON:

{
    "lesson": "text...",
    "quiz": [
        {
            "question": "text",
            "options": ["A", "B", "C", "D"],
            "answer": 0,
            "explanation": "text"
        }
    ]
}
PROMPT;

        $result = $this->callGemini(
            $prompt,
            0.6,
            30,
            3500
        );

        if (
            !$result ||
            empty($result['lesson']) ||
            !is_string($result['lesson']) ||
            empty($result['quiz']) ||
            !is_array($result['quiz'])
        ) {
            return null;
        }

        $quiz = [];

        foreach ($result['quiz'] as $q) {

            if (
                isset(
                    $q['question'],
                    $q['options'],
                    $q['answer'],
                    $q['explanation']
                )
                && is_array($q['options'])
                && count($q['options']) === 4
                && is_numeric($q['answer'])
                && (int) $q['answer'] >= 0
                && (int) $q['answer'] <= 3
            ) {

                $quiz[] = [
                    'question' => (string) $q['question'],

                    'options' => array_values(
                        array_map(
                            'strval',
                            $q['options']
                        )
                    ),

                    'answer' => (int) $q['answer'],

                    'explanation' =>
                    (string) $q['explanation'],
                ];
            }
        }

        if (count($quiz) < 3) {
            return null;
        }

        return [
            'lesson' => $result['lesson'],
            'quiz' => $quiz
        ];
    }
}
