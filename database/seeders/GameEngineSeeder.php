<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use App\Models\GameEngine;
use Illuminate\Support\Facades\DB;

class GameEngineSeeder extends Seeder
{
    public function run(): void
    {
        $engines = [
            [
                'engine_key' => 'arithmetic_speed',
                'name' => 'Math Speed Challenge',
                'description' => 'Solve math questions against time.',
                'view_name' => 'student.games.math-speed',
                'supported_interests' => ['math', 'mathematics'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'creative_coding',
                'name' => 'Creative Coding Challenge',
                'description' => 'Complete coding-related challenges.',
                'view_name' => 'student.games.creative-coding',
                'supported_interests' => ['coding', 'programming'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'chess_tactics',
                'name' => 'Chess Tactics Challenge',
                'description' => 'Solve tactical chess puzzles and identify the best move.',
                'view_name' => 'student.games.chess-tactics',
                'supported_interests' => ['chess', 'strategy'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'art_color_theory',
                'name' => 'Color Theory Studio',
                'description' => 'Explore color relationships and build a strong visual palette.',
                'view_name' => 'student.games.art-color-theory',
                'supported_interests' => ['art', 'drawing', 'painting', 'color theory'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'music_note_rhythm',
                'name' => 'Music Notes & Rhythm',
                'description' => 'Practice musical notes, rhythm, and basic theory.',
                'view_name' => 'student.games.music-note-rhythm',
                'supported_interests' => ['music', 'song', 'singing', 'guitar', 'piano'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'sorting_challenge',
                'name' => 'Sorting Challenge',
                'description' => 'Sort items into the correct categories.',
                'view_name' => 'student.games.sorting-challenge',
                'supported_interests' => ['environment', 'recycling', 'science'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'algorithm_race',
                'name' => 'Algorithm Race',
                'description' => 'Algorithm-based game engine.',
                'view_name' => null,
                'supported_interests' => ['coding', 'programming'],
                'is_enabled' => false,
            ],
            [
                'engine_key' => 'physics_quiz',
                'name' => 'Physics Quiz',
                'description' => 'Physics question-based game engine.',
                'view_name' => null,
                'supported_interests' => ['physics', 'science'],
                'is_enabled' => false,
            ],
        ];

        foreach ($engines as $engine) {
            GameEngine::updateOrCreate(
                ['engine_key' => $engine['engine_key']],
                $engine
            );
        }

        $games = [
            [
                'game_type' => 'arithmetic_speed',
                'name' => 'Math Speed Challenge',
                'description' => 'Solve math questions against time.',
                'category' => 'mathematics',
                'subject_name' => 'Mathematics',
                'subject_category' => 'Mathematics',
            ],
            [
                'game_type' => 'creative_coding',
                'name' => 'Creative Coding Challenge',
                'description' => 'Complete coding-related challenges.',
                'category' => 'coding',
                'subject_name' => 'Creative Programming',
                'subject_category' => 'Programming',
            ],
            [
                'game_type' => 'sorting_challenge',
                'name' => 'Sorting Challenge',
                'description' => 'Sort items into the correct categories.',
                'category' => 'environment',
                'subject_name' => 'Environmental Science',
                'subject_category' => 'Environment',
            ],
            [
                'game_type' => 'chess_tactics',
                'name' => 'Chess Tactics Challenge',
                'description' => 'Solve tactical chess puzzles and identify the best move.',
                'category' => 'chess',
                'subject_name' => 'Chess',
                'subject_category' => 'Chess',
            ],
            [
                'game_type' => 'art_color_theory',
                'name' => 'Color Theory Studio',
                'description' => 'Learn color relationships through short interactive quizzes.',
                'category' => 'art',
                'subject_name' => 'Art',
                'subject_category' => 'Creative Arts',
            ],
            [
                'game_type' => 'music_note_rhythm',
                'name' => 'Music Notes & Rhythm',
                'description' => 'Learn musical notes and rhythm through short interactive quizzes.',
                'category' => 'music',
                'subject_name' => 'Music',
                'subject_category' => 'Music',
            ],
        ];

        foreach ($games as $game) {
            $subjectId = DB::table('subjects')
                ->whereRaw('LOWER(name) = ?', [strtolower($game['subject_name'])])
                ->value('id');

            if (!$subjectId) {
                $subjectId = DB::table('subjects')->insertGetId([
                    'name' => $game['subject_name'],
                    'description' => $game['subject_name'] . ' learning activities.',
                    'category' => $game['subject_category'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Game::updateOrCreate(
                ['game_type' => $game['game_type']],
                [
                    'subject_id' => $subjectId,
                    'name' => $game['name'],
                    'description' => $game['description'],
                    'category' => $game['category'],
                    'difficulty' => 'beginner',
                ]
            );
        }
    }
}
