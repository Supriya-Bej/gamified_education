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
                'engine_key' => 'science_quiz',
                'name' => 'Science Quiz',
                'description' => 'Explore science through interactive multiple-choice questions.',
                'view_name' => 'student.games.knowledge-quiz',
                'supported_interests' => ['science'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'sports_quiz',
                'name' => 'Sports Quiz',
                'description' => 'Learn sports rules, skills, and facts through interactive questions.',
                'view_name' => 'student.games.knowledge-quiz',
                'supported_interests' => ['sports'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'reading_quiz',
                'name' => 'Reading Quiz',
                'description' => 'Practice reading comprehension and story skills.',
                'view_name' => 'student.games.knowledge-quiz',
                'supported_interests' => ['reading'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'puzzles_quiz',
                'name' => 'Puzzle Challenge',
                'description' => 'Solve logic and number-pattern puzzles.',
                'view_name' => 'student.games.knowledge-quiz',
                'supported_interests' => ['puzzles', 'puzzle'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'technology_quiz',
                'name' => 'Technology Quiz',
                'description' => 'Explore technology, computing, and digital safety.',
                'view_name' => 'student.games.knowledge-quiz',
                'supported_interests' => ['technology'],
                'is_enabled' => true,
            ],
            [
                'engine_key' => 'general_knowledge_quiz',
                'name' => 'General Knowledge Quiz',
                'description' => 'Explore facts about the world and everyday life.',
                'view_name' => 'student.games.knowledge-quiz',
                'supported_interests' => ['gk', 'general knowledge', 'general_knowledge'],
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
            [
                'game_type' => 'science_quiz',
                'name' => 'Science Quiz',
                'description' => 'Explore science through interactive questions.',
                'category' => 'science',
                'subject_name' => 'Science',
                'subject_category' => 'Science',
            ],
            [
                'game_type' => 'sports_quiz',
                'name' => 'Sports Quiz',
                'description' => 'Learn sports rules, skills, and facts.',
                'category' => 'sports',
                'subject_name' => 'Sports',
                'subject_category' => 'Sports',
            ],
            [
                'game_type' => 'reading_quiz',
                'name' => 'Reading Quiz',
                'description' => 'Practice reading comprehension and story skills.',
                'category' => 'reading',
                'subject_name' => 'Reading',
                'subject_category' => 'Reading',
            ],
            [
                'game_type' => 'puzzles_quiz',
                'name' => 'Puzzle Challenge',
                'description' => 'Solve logic and number-pattern puzzles.',
                'category' => 'puzzles',
                'subject_name' => 'Puzzles',
                'subject_category' => 'Puzzles',
            ],
            [
                'game_type' => 'technology_quiz',
                'name' => 'Technology Quiz',
                'description' => 'Explore technology, computing, and digital safety.',
                'category' => 'technology',
                'subject_name' => 'Technology',
                'subject_category' => 'Technology',
            ],
            [
                'game_type' => 'general_knowledge_quiz',
                'name' => 'General Knowledge Quiz',
                'description' => 'Explore facts about the world and everyday life.',
                'category' => 'general_knowledge',
                'subject_name' => 'General Knowledge',
                'subject_category' => 'General Knowledge',
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
