<?php

namespace Tests\Feature;

use App\Http\Controllers\StudentLearningMaterialController;
use App\Models\Badge;
use App\Models\Certificate;
use App\Models\Game;
use App\Models\LeaderboardXpAdjustment;
use App\Models\LearningMaterial;
use App\Models\LearningMaterialProgress;
use App\Models\LearningTask;
use App\Models\ProjectChallenge;
use App\Models\ProjectChallengeSubmission;
use App\Models\SiteSetting;
use App\Models\StudentBadge;
use App\Models\StudentPreference;
use App\Models\StudentProgress;
use App\Models\Topic;
use App\Models\UserRegister;
use App\Services\GeminiService;
use App\Services\LearningMaterialProgressionService;
use Database\Seeders\BadgeSeeder;
use Database\Seeders\GameEngineSeeder;
use Database\Seeders\InterestLearningMaterialsSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Tests\TestCase;

class StudentPlayableTasksTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['services.gemini.api_key' => 'feature-test-key']);
        $this->createTestSchema();
        $this->seed(GameEngineSeeder::class);
    }

    private function projectChallengeResponse(string $title = 'Build a practical portfolio website'): array
    {
        return [
            'candidates' => [
                [
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'interest' => 'Coding',
                                'title' => $title,
                                'project_brief' => 'Design and build a responsive portfolio that presents a student’s work.',
                                'objectives' => [
                                    'Create a responsive page layout.',
                                    'Present at least three projects clearly.',
                                    'Check the page on desktop and mobile.',
                                ],
                                'deliverables' => [
                                    'A public project repository.',
                                    'A deployed portfolio link.',
                                ],
                                'difficulty' => 'beginner',
                                'xp' => 45,
                            ], JSON_THROW_ON_ERROR),
                        ]],
                    ],
                ],
            ],
        ];
    }

    public function test_gemini_catalog_only_contains_playable_games_matching_interests(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->codingTask(),
                $this->chessTask(),
                $this->mathTask(),
                $this->artTask(),
                $this->musicTask(),
            ])),
        ]);

        $tasks = app(GeminiService::class)->generateTasks(
            ['Coding', 'Chess', 'Art', 'Music'],
            'Practice all selected interests',
            'beginner',
            6
        )['tasks'];

        $this->assertSame(
            ['creative_coding', 'chess_tactics', 'art_color_theory', 'music_note_rhythm'],
            array_column($tasks, 'game_type')
        );
        $this->assertSame(
            ['coding', 'chess', 'art', 'music'],
            array_column($tasks, 'category')
        );
        $this->assertSame(
            Game::query()->whereIn('game_type', [
                'creative_coding',
                'chess_tactics',
                'art_color_theory',
                'music_note_rhythm',
            ])->orderBy('game_type')->pluck('id')->all(),
            collect($tasks)->sortBy('game_type')->pluck('game_id')->all()
        );

        Http::assertSent(fn (ClientRequest $request) => ! str_contains($request->url(), 'key=')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Chess Tactics Challenge')
            && str_contains($request['contents'][0]['parts'][0]['text'], '25 distinct quests')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'exactly "beginner"')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Color Theory Studio')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Music Notes & Rhythm')
            && ! str_contains($request['contents'][0]['parts'][0]['text'], 'Math Speed Challenge')
            && ! str_contains($request['contents'][0]['parts'][0]['text'], 'Sorting Challenge')
        );
    }

    public function test_every_registration_interest_has_an_enabled_playable_game_and_fallback_quest(): void
    {
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $interestGameTypes = [
            'Math' => 'arithmetic_speed',
            'Science' => 'science_quiz',
            'Coding' => 'creative_coding',
            'Chess' => 'chess_tactics',
            'Sports' => 'sports_quiz',
            'Art' => 'art_color_theory',
            'Music' => 'music_note_rhythm',
            'Reading' => 'reading_quiz',
            'Puzzles' => 'puzzles_quiz',
            'Technology' => 'technology_quiz',
            'GK' => 'general_knowledge_quiz',
            'Environment' => 'sorting_challenge',
        ];
        $gemini = app(GeminiService::class);

        foreach ($interestGameTypes as $interest => $gameType) {
            $games = $gemini->getPlayableGamesForInterests([$interest]);
            $this->assertTrue(
                $games->contains(fn (Game $game) => $game->game_type === $gameType),
                "No playable game is configured for the registration interest {$interest}."
            );
        }

        $generated = $gemini->generateTasks(
            array_keys($interestGameTypes),
            'Explore every selected interest',
            'beginner',
            count($interestGameTypes)
        );

        $this->assertEqualsCanonicalizing(
            array_values($interestGameTypes),
            array_column($generated['tasks'], 'game_type')
        );
        $this->assertTrue(collect($generated['tasks'])->every(
            fn (array $task) => $gemini->isValidGameData($task['game_type'], $task['game_data'])
        ));
    }

    public function test_knowledge_quiz_engines_render_and_grade_quests_for_each_new_interest(): void
    {
        $student = $this->studentWithInterests([
            'Science',
            'Sports',
            'Reading',
            'Puzzles',
            'Technology',
            'GK',
        ]);
        $quizEngines = [
            'science_quiz',
            'sports_quiz',
            'reading_quiz',
            'puzzles_quiz',
            'technology_quiz',
            'general_knowledge_quiz',
        ];

        foreach ($quizEngines as $gameType) {
            $game = Game::query()->where('game_type', $gameType)->firstOrFail();
            $task = LearningTask::create([
                'user_id' => $student->id,
                'game_id' => $game->id,
                'game_type' => $gameType,
                'title' => 'Test '.$game->name,
                'description' => 'Answer this interest-based quiz.',
                'category' => $game->category,
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'pending',
                'game_data' => [
                    'instructions' => 'Choose the correct answer.',
                    'questions' => [[
                        'question' => 'Which star is closest to Earth?',
                        'answer' => 'The Sun',
                        'options' => ['Sirius', 'The Sun', 'Polaris'],
                    ]],
                ],
            ]);

            $this->actingAs($student, 'student')
                ->get(route('student.game.play', $task))
                ->assertOk()
                ->assertSee($game->name)
                ->assertSee('Which star is closest to Earth?');

            $this->postJson(route('student.game.complete', $task), [
                'answers' => ['The Sun'],
            ])->assertOk()->assertJson([
                'passed' => true,
                'xp_earned' => 20,
            ]);
        }
    }

    public function test_admin_add_game_creates_an_engine_linked_library_entry(): void
    {
        $admin = UserRegister::create([
            'name' => 'Game Library Admin',
            'email' => 'game-library-admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.games.create'))
            ->assertOk()
            ->assertSee('General Knowledge Quiz')
            ->assertSee('Sports Quiz');

        $this->post(route('admin.games.store'), [
            'name' => 'Sports Rules Practice',
            'description' => 'Practice sports rules.',
            'category' => 'sports',
            'difficulty' => 'beginner',
            'game_type' => 'sports_quiz',
        ])->assertRedirect(route('admin.games.index'));

        $game = Game::query()
            ->where('name', 'Sports Rules Practice')
            ->firstOrFail();
        $this->assertSame('sports_quiz', $game->game_type);
        $this->assertSame('sports', $game->category);
        $this->assertNotNull($game->subject_id);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.games.index'))
            ->assertOk()
            ->assertSee('Game added successfully.')
            ->assertSee('Sports Rules Practice');
    }

    public function test_admin_game_engine_catalog_is_paginated(): void
    {
        $admin = UserRegister::create([
            'name' => 'Engine Admin',
            'email' => 'engine-admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.engines.index'))
            ->assertOk()
            ->assertSee('12 Engines')
            ->assertSee('pagination');

        $this->get(route('admin.engines.index', ['page' => 2]))
            ->assertOk()
            ->assertSee('Game Engine Management');
    }

    public function test_admin_student_and_engine_details_paginate_long_task_lists(): void
    {
        $admin = UserRegister::create([
            'name' => 'Detail Admin',
            'email' => 'detail-admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $student = $this->studentWithInterests(['Sports']);
        $game = Game::query()->where('game_type', 'sports_quiz')->firstOrFail();

        foreach (range(1, 11) as $index) {
            foreach (['completed', 'pending'] as $status) {
                LearningTask::create([
                    'user_id' => $student->id,
                    'game_id' => $game->id,
                    'game_type' => $game->game_type,
                    'title' => ucfirst($status).' sports quest '.$index,
                    'description' => 'Paginated task test.',
                    'category' => 'sports',
                    'difficulty' => 'beginner',
                    'xp' => 20,
                    'status' => $status,
                    'game_data' => [],
                ]);
            }

            Game::create([
                'subject_id' => $game->subject_id,
                'name' => 'Sports library entry '.$index,
                'description' => 'Additional game using the sports engine.',
                'category' => 'sports',
                'game_type' => 'sports_quiz',
                'difficulty' => 'beginner',
            ]);
        }

        $this->actingAs($admin, 'admin')
            ->get(route('admin.students.show', [
                'student' => $student->id,
                'completed_page' => 2,
                'pending_page' => 2,
            ]))
            ->assertOk()
            ->assertSee('11 completed tasks')
            ->assertSee('11 pending tasks');

        $this->get(route('admin.engines.show', [
            'gameType' => 'sports_quiz',
            'games_page' => 2,
            'tasks_page' => 2,
        ]))
            ->assertOk()
            ->assertSee('22 learning tasks currently use this engine');
    }

    public function test_art_and_music_games_render_and_are_graded_by_the_server(): void
    {
        $student = $this->studentWithInterests(['Art', 'Music']);
        $taskCases = [
            ['game_type' => 'art_color_theory', 'data' => $this->artTask(), 'view' => 'COLOR THEORY QUEST'],
            ['game_type' => 'music_note_rhythm', 'data' => $this->musicTask(), 'view' => 'NOTES & RHYTHM QUEST'],
        ];

        foreach ($taskCases as $case) {
            $game = Game::query()->where('game_type', $case['game_type'])->firstOrFail();
            $task = LearningTask::create([
                'user_id' => $student->id,
                'game_id' => $game->id,
                'game_type' => $game->game_type,
                'title' => $case['data']['title'],
                'description' => $case['data']['description'],
                'category' => $game->category,
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'pending',
                'game_data' => $case['data']['game_data'],
            ]);

            $this->actingAs($student, 'student')
                ->get(route('student.game.play', $task))
                ->assertOk()
                ->assertSee($case['view'])
                ->assertSee($case['data']['game_data']['questions'][0]['question']);

            $wrongAnswers = array_fill(
                0,
                count($case['data']['game_data']['questions']),
                'incorrect answer'
            );
            $this->actingAs($student, 'student')
                ->postJson(route('student.game.complete', $task), ['answers' => $wrongAnswers])
                ->assertOk()
                ->assertJson([
                    'success' => true,
                    'passed' => false,
                    'xp_earned' => 0,
                ]);
            $this->assertSame('pending', $task->fresh()->status);

            $correctAnswers = array_column($case['data']['game_data']['questions'], 'answer');
            $this->actingAs($student, 'student')
                ->postJson(route('student.game.complete', $task), ['answers' => $correctAnswers])
                ->assertOk()
                ->assertJson([
                    'success' => true,
                    'passed' => true,
                    'xp_earned' => 20,
                ]);
            $this->assertSame('completed', $task->fresh()->status);
        }

        $this->assertSame(40, (int) StudentProgress::where('user_id', $student->id)->value('total_xp'));
    }

    public function test_dashboard_generates_quests_for_art_and_music_interests(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->artTask(),
                $this->musicTask(),
            ])),
        ]);

        $student = $this->studentWithInterests(['Art', 'Music']);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('class="theme-creative"', false)
            ->assertSee('The Art Studio')
            ->assertSee($this->artTask()['title'])
            ->assertSee($this->musicTask()['title'])
            ->assertSee('Start Quest')
            ->assertSee('2 Available');

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($this->artTask()['title'])
            ->assertSee($this->musicTask()['title'])
            ->assertSee('Start Quest')
            ->assertSee('2 Available');

        $this->assertEqualsCanonicalizing(
            ['art_color_theory', 'music_note_rhythm'],
            LearningTask::query()
                ->where('user_id', $student->id)
                ->where('status', 'pending')
                ->pluck('game_type')
                ->all()
        );
    }

    public function test_selected_dashboard_theme_is_scoped_to_each_student_account(): void
    {
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $musicStudent = $this->studentWithInterests(['Chess', 'Music']);
        $chessStudent = $this->studentWithInterests(['Chess']);

        $this->actingAs($musicStudent, 'student')
            ->get(route('student.dashboard', ['focus' => 'Music']))
            ->assertOk()
            ->assertSee('<body class="theme-music">', false);

        $this->actingAs($chessStudent, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('<body class="theme-chess">', false)
            ->assertDontSee('<body class="theme-music">', false);

        $this->actingAs($musicStudent, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('<body class="theme-music">', false);
    }

    public function test_dashboard_uses_ai_generated_art_and_music_icons_for_matching_themes(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->artTask(),
                $this->musicTask(),
            ])),
        ]);

        foreach ([
            ['Art', 'creative', '🖍️'],
            ['Music', 'music', '🎹'],
        ] as [$interest, $theme, $icon]) {
            $student = $this->studentWithInterests([$interest]);
            $preference = $student->preferences;
            $aiProfile = $preference->ai_profile;
            $aiProfile['visual_theme'] = $theme;
            $aiProfile['theme_icon'] = $icon;
            $preference->update(['ai_profile' => $aiProfile]);

            $this->actingAs($student, 'student')
                ->get(route('student.dashboard'))
                ->assertOk()
                ->assertSee('class="theme-'.$theme.'"', false)
                ->assertSee($theme === 'creative' ? 'The Art Studio' : 'The Music Room')
                ->assertSee($theme === 'creative' ? 'Your creative corner' : 'Find your rhythm')
                ->assertSee($icon);
        }
    }

    public function test_gemini_profile_prompt_requests_subject_specific_dashboard_icons(): void
    {
        Http::fake([
            '*' => Http::response([
                'candidates' => [[
                    'content' => [
                        'parts' => [[
                            'text' => json_encode([
                                'visual_theme' => 'creative',
                                'theme_icon' => '🖌️',
                            ], JSON_THROW_ON_ERROR),
                        ]],
                    ],
                ]],
            ]),
        ]);

        $profile = app(GeminiService::class)->generateProfile(
            ['Art'],
            'Explore color',
            'beginner'
        );

        $this->assertSame('🖌️', $profile['theme_icon']);
        Http::assertSent(fn (ClientRequest $request) => str_contains(
            $request['contents'][0]['parts'][0]['text'],
            'For music interests, choose exactly one music-related emoji or symbol'
        ) &&
            str_contains(
                $request['contents'][0]['parts'][0]['text'],
                'For art, drawing, painting, or design interests, choose exactly one art-related emoji'
            )
        );
    }

    public function test_dashboard_replaces_repeated_ai_quests_with_fresh_interest_matched_challenges(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->codingTask(),
                $this->chessTask(),
            ])),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);

        foreach ([$this->codingTask(), $this->chessTask()] as $oldTask) {
            $game = Game::query()->where('game_type', $oldTask['game_type'])->firstOrFail();
            LearningTask::create([
                'user_id' => $student->id,
                'game_id' => $game->id,
                'game_type' => $game->game_type,
                'title' => $oldTask['title'],
                'description' => $oldTask['description'],
                'category' => $game->category,
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'completed',
                'game_data' => $oldTask['game_data'],
            ]);
        }

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Pixel Garden - Round 2')
            ->assertSee('Knight Fork Finder - Round 2');

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Pixel Garden - Round 2')
            ->assertSee('Knight Fork Finder - Round 2')
            ->assertDontSee($this->codingTask()['title'])
            ->assertDontSee($this->chessTask()['title']);

        $newTasks = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'pending')
            ->get();

        $this->assertCount(2, $newTasks);
        $this->assertEqualsCanonicalizing(
            ['creative_coding', 'chess_tactics'],
            $newTasks->pluck('game_type')->all()
        );
        $this->assertTrue($newTasks->every(
            fn (LearningTask $task) => $task->game_id !== null
                && $task->game->game_type === $task->game_type
                && is_array($task->game_data)
        ));
        foreach ($newTasks as $task) {
            $this->actingAs($student, 'student')
                ->get(route('student.game.play', $task))
                ->assertOk();
        }
        Http::assertSentCount(3);
    }

    public function test_fallback_challenge_content_rotates_across_distinct_rounds_for_the_same_game_engine(): void
    {
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $codingGame = Game::query()->where('game_type', 'creative_coding')->get();
        $firstRound = app(GeminiService::class)->generateTasks(
            ['Coding'],
            'Practice coding',
            'beginner',
            1
        )['tasks'][0];
        $secondRound = app(GeminiService::class)->generateTasks(
            ['Coding'],
            'Practice coding',
            'beginner',
            1,
            ['creative_coding' => [$firstRound['title']]]
        )['tasks'][0];
        $thirdRound = app(GeminiService::class)->generateTasks(
            ['Coding'],
            'Practice coding',
            'beginner',
            1,
            ['creative_coding' => [$firstRound['title'], $secondRound['title']]]
        )['tasks'][0];

        $this->assertCount(1, $codingGame);
        $this->assertSame('creative_coding', $firstRound['game_type']);
        $this->assertSame('creative_coding', $secondRound['game_type']);
        $this->assertSame('creative_coding', $thirdRound['game_type']);
        $this->assertSame('Circle Orbit - Round 1', $firstRound['title']);
        $this->assertSame('Pixel Garden - Round 2', $secondRound['title']);
        $this->assertSame('Mirror Mosaic - Round 3', $thirdRound['title']);
        $this->assertNotSame(
            $firstRound['game_data']['challenges'],
            $secondRound['game_data']['challenges']
        );
        $this->assertNotSame(
            $secondRound['game_data']['challenges'],
            $thirdRound['game_data']['challenges']
        );
        $this->assertIsString($firstRound['game_data']['instructions']);
        $this->assertNotEmpty($firstRound['game_data']['challenges']);
        $this->assertNotEmpty($secondRound['game_data']['challenges']);
        $this->assertNotEmpty($thirdRound['game_data']['challenges']);

        foreach ([$firstRound, $secondRound, $thirdRound] as $round) {
            $this->assertSame(
                ['creative_coding', 'coding'],
                [$round['game_type'], $round['category']]
            );
        }
    }

    public function test_dashboard_persists_only_valid_tasks_for_the_current_student_once(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->codingTask(),
                $this->chessTask(),
                $this->mathTask(),
            ])),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Ask your learning assistant')
            ->assertSee(route('student.learning-materials.index'))
            ->assertSee('Open navigation menu')
            ->assertSee('id="studentSidebar"', false)
            ->assertSee('AI Personalized')
            ->assertSee('Theme')
            ->assertDontSee('dashboard-expedition')
            ->assertDontSee('ECOQUEST FIELD GUIDE')
            ->assertSee('2 Available')
            ->assertSee('Start Quest')
            ->assertSee('Available Quests')
            ->assertDontSee('Day Streak');

        $tasks = LearningTask::query()
            ->where('user_id', $student->id)
            ->get();

        $this->assertCount(2, $tasks);
        $this->assertEqualsCanonicalizing(
            ['creative_coding', 'chess_tactics'],
            $tasks->pluck('game_type')->all()
        );
        $this->assertTrue($tasks->every(
            fn (LearningTask $task) => $task->game_id !== null
                && $task->game->game_type === $task->game_type
                && $task->user_id === $student->id
        ));
        $this->assertTrue($tasks->every(
            fn (LearningTask $task) => is_array($task->game_data)
        ));

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk();

        $this->assertSame(2, LearningTask::query()->where('user_id', $student->id)->count());
        Http::assertSentCount(3);
    }

    public function test_completed_quests_are_hidden_and_replaced_with_a_new_challenge(): void
    {
        $repeatedTask = $this->codingTask();
        $newTask = array_replace($this->codingTask(), [
            'title' => 'Draw a spiral with a loop',
            'description' => 'Use a JavaScript loop to create a colorful spiral.',
        ]);
        Http::fake([
            '*' => Http::response($this->geminiResponse([$repeatedTask, $newTask])),
        ]);

        $student = $this->studentWithInterests(['Coding']);
        $game = Game::query()->where('game_type', 'creative_coding')->firstOrFail();
        $completedTask = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $game->id,
            'game_type' => $game->game_type,
            'title' => $repeatedTask['title'],
            'description' => $repeatedTask['description'],
            'category' => $game->category,
            'difficulty' => 'beginner',
            'xp' => 25,
            'status' => 'pending',
            'game_data' => $repeatedTask['game_data'],
        ]);

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $completedTask), [
                'code' => 'ctx.fillRect(10, 10, 20, 20);',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'passed' => true,
                'xp_earned' => 25,
            ]);

        $this->assertSame('completed', $completedTask->fresh()->status);

        $stalePendingTask = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $game->id,
            'game_type' => $game->game_type,
            'title' => $repeatedTask['title'],
            'description' => $repeatedTask['description'],
            'category' => $game->category,
            'difficulty' => 'beginner',
            'xp' => 25,
            'status' => 'pending',
            'game_data' => $repeatedTask['game_data'],
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($newTask['title']);

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($newTask['title'])
            ->assertDontSee($repeatedTask['title'])
            ->assertSee('1 Available')
            ->assertHeader('Cache-Control', 'must-revalidate, no-cache, no-store, private');

        $this->assertSame('completed', $completedTask->fresh()->status);
        $this->assertSame('superseded', $stalePendingTask->fresh()->status);
        $this->actingAs($student, 'student')
            ->get(route('student.game.play', $stalePendingTask))
            ->assertRedirect(route('student.dashboard'));
        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $stalePendingTask), [
                'code' => 'ctx.fillRect(10, 10, 20, 20);',
            ])
            ->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'This quest is no longer available.',
            ]);
        $this->assertSame(
            1,
            LearningTask::query()
                ->where('user_id', $student->id)
                ->where('status', 'pending')
                ->count()
        );
        Http::assertSent(fn (ClientRequest $request) => str_contains(
            $request['contents'][0]['parts'][0]['text'],
            $repeatedTask['title']
        ));

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($newTask['title'])
            ->assertDontSee($repeatedTask['title']);
        Http::assertSentCount(4);
    }

    public function test_interest_course_unlocks_levels_after_25_completions_without_exceeding_the_stage_cap(): void
    {
        $initialOutputBufferLevel = ob_get_level();
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $student = $this->studentWithInterests(['Chess']);
        $game = Game::query()->where('game_type', 'chess_tactics')->firstOrFail();
        $gameData = $this->chessTask()['game_data'];

        for ($index = 1; $index <= 24; $index++) {
            LearningTask::create([
                'user_id' => $student->id,
                'game_id' => $game->id,
                'game_type' => $game->game_type,
                'title' => 'Beginner chess practice '.$index,
                'description' => 'Practice a beginner chess concept.',
                'category' => 'chess',
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'completed',
                'game_data' => $gameData,
            ]);
        }

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertDontSee('Beginner stage');

        $this->get(route('student.progress'))
            ->assertOk()
            ->assertSee('Beginner stage')
            ->assertSee('24/25 games');

        $pendingBeginnerTasks = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'pending')
            ->get();
        $this->assertCount(1, $pendingBeginnerTasks);
        $this->assertSame('beginner', $pendingBeginnerTasks->first()->difficulty);
        $this->assertSame(25, LearningTask::query()
            ->where('user_id', $student->id)
            ->where('category', 'chess')
            ->where('difficulty', 'beginner')
            ->count());
        $this->assertDatabaseMissing('certificates', ['user_id' => $student->id]);

        $finalBeginnerTask = $pendingBeginnerTasks->first();
        $this->postJson(route('student.game.complete', $finalBeginnerTask), [
            'answers' => [$finalBeginnerTask->game_data['puzzles'][0]['answer']],
        ])->assertOk()->assertJsonPath('course_progress.active_level', 'intermediate');

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertDontSee('Intermediate stage');
        $this->get(route('student.progress'))
            ->assertOk()
            ->assertSee('Intermediate stage');

        $pendingIntermediateTasks = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'pending')
            ->get();
        $this->assertCount(1, $pendingIntermediateTasks);
        $this->assertTrue($pendingIntermediateTasks->every(
            fn (LearningTask $task) => $task->difficulty === 'intermediate'
        ));
        $this->assertSame(25, LearningTask::query()
            ->where('user_id', $student->id)
            ->where('category', 'chess')
            ->where('difficulty', 'beginner')
            ->count());

        while (ob_get_level() > $initialOutputBufferLevel) {
            ob_end_clean();
        }
    }

    public function test_student_gets_one_interest_certificate_after_all_75_games_are_completed(): void
    {
        $student = $this->studentWithInterests(['Chess']);
        $game = Game::query()->where('game_type', 'chess_tactics')->firstOrFail();
        $gameData = $this->chessTask()['game_data'];
        $finalTask = null;

        foreach (['beginner' => 25, 'intermediate' => 25, 'advanced' => 25] as $difficulty => $count) {
            for ($index = 1; $index <= $count; $index++) {
                $finalTask = LearningTask::create([
                    'user_id' => $student->id,
                    'game_id' => $game->id,
                    'game_type' => $game->game_type,
                    'title' => ucfirst($difficulty).' chess challenge '.$index,
                    'description' => 'Solve an interest-matched chess challenge.',
                    'category' => 'chess',
                    'difficulty' => $difficulty,
                    'xp' => 20,
                    'status' => $difficulty === 'advanced' && $index === $count
                        ? 'pending'
                        : 'completed',
                    'game_data' => $gameData,
                ]);
            }
        }

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $finalTask), [
                'answers' => [$gameData['puzzles'][0]['answer']],
            ])
            ->assertOk()
            ->assertJsonPath('certificate.title', 'Chess Mastery Certificate')
            ->assertJsonPath('certificate.status', 'pending_review');

        $this->assertDatabaseHas('certificates', [
            'user_id' => $student->id,
            'interest' => 'chess',
            'title' => 'Chess Mastery Certificate',
            'status' => 'pending_review',
        ]);
        $this->assertSame(1, Certificate::query()
            ->where('user_id', $student->id)
            ->where('interest', 'chess')
            ->count());

        $certificate = Certificate::query()
            ->where('user_id', $student->id)
            ->where('interest', 'chess')
            ->firstOrFail();
        $this->get(route('student.certificates.show', $certificate))
            ->assertNotFound();
    }

    public function test_invalid_pending_task_does_not_block_a_valid_interest_matched_quest(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->codingTask(),
            ])),
        ]);

        $student = $this->studentWithInterests(['Coding']);
        $codingGame = Game::query()
            ->where('game_type', 'creative_coding')
            ->firstOrFail();

        LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $codingGame->id,
            'game_type' => $codingGame->game_type,
            'title' => 'Stale music task',
            'description' => 'This legacy task has a mismatched category.',
            'category' => 'music',
            'difficulty' => 'beginner',
            'xp' => 20,
            'game_data' => ['instructions' => 'This task is not playable.'],
            'status' => 'pending',
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($this->codingTask()['title']);

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Build a chessboard pattern')
            ->assertSee('1 Available');

        $validTask = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('title', $this->codingTask()['title'])
            ->firstOrFail();

        $this->assertSame($codingGame->id, $validTask->game_id);
        $this->assertSame('coding', $validTask->category);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk();

        $this->assertSame(
            1,
            LearningTask::query()
                ->where('user_id', $student->id)
                ->where('title', $this->codingTask()['title'])
                ->count()
        );
        Http::assertSentCount(4);
    }

    public function test_students_can_find_published_learning_materials_but_not_unpublished_materials(): void
    {
        $student = $this->studentWithInterests(['Coding']);
        $topic = Topic::create([
            'name' => 'Programming',
            'description' => 'Learn programming concepts.',
            'is_active' => true,
        ]);
        $publishedMaterial = LearningMaterial::create([
            'title' => 'Introduction to Loops',
            'topic' => 'Programming',
            'topic_id' => $topic->id,
            'description' => 'A beginner guide to loops.',
            'content' => 'Loops repeat a set of instructions.',
            'difficulty' => 'beginner',
            'is_published' => true,
        ]);
        $unpublishedMaterial = LearningMaterial::create([
            'title' => 'Draft Programming Lesson',
            'topic' => 'Programming',
            'topic_id' => $topic->id,
            'content' => 'This is still a draft.',
            'difficulty' => 'beginner',
            'is_published' => false,
        ]);

        $this->actingAs($student, 'student');

        $indexView = app(StudentLearningMaterialController::class)->index(
            app(LearningMaterialProgressionService::class)
        );
        $visibleMaterials = $indexView->getData()['materials']->getCollection();

        $this->assertTrue($visibleMaterials->contains('id', $publishedMaterial->id));
        $this->assertFalse($visibleMaterials->contains('id', $unpublishedMaterial->id));

        $detailView = app(StudentLearningMaterialController::class)->show(
            $publishedMaterial,
            app(LearningMaterialProgressionService::class)
        );
        $this->assertSame(
            $publishedMaterial->id,
            $detailView->getData()['learningMaterial']->id
        );
        $this->assertSame(
            'Loops repeat a set of instructions.',
            $detailView->getData()['learningMaterial']->content
        );

        try {
            app(StudentLearningMaterialController::class)->show(
                $unpublishedMaterial,
                app(LearningMaterialProgressionService::class)
            );
            $this->fail('Unpublished learning materials must remain inaccessible.');
        } catch (HttpExceptionInterface $exception) {
            $this->assertSame(404, $exception->getStatusCode());
        }
    }

    public function test_learning_material_pagination_keeps_active_topic_and_search_filters(): void
    {
        $student = $this->studentWithInterests(['Coding']);
        $topic = Topic::create([
            'name' => 'Pagination Topic',
            'description' => 'A topic used to verify material pagination.',
            'is_active' => true,
        ]);

        foreach (range(1, 10) as $number) {
            LearningMaterial::create([
                'title' => "Pagination Lesson {$number}",
                'topic' => $topic->name,
                'topic_id' => $topic->id,
                'description' => 'pagination lesson search phrase',
                'content' => "Lesson content {$number}.",
                'difficulty' => 'beginner',
                'is_published' => true,
            ]);
        }

        $this->actingAs($student, 'student');
        request()->query->replace([
            'topic' => $topic->id,
            'search' => 'pagination lesson',
            'page' => 1,
        ]);

        $firstPage = app(StudentLearningMaterialController::class)
            ->index(app(LearningMaterialProgressionService::class))
            ->getData()['materials'];

        $this->assertSame(10, $firstPage->total());
        $this->assertSame(1, $firstPage->firstItem());
        $this->assertSame(9, $firstPage->lastItem());

        $pageTwoUrl = collect(UrlWindow::make($firstPage, 1))
            ->filter(fn ($element) => is_array($element))
            ->flatMap(fn (array $element) => array_values($element))
            ->first(fn (string $url) => str_contains($url, 'page=2'));

        $this->assertNotNull($pageTwoUrl);
        parse_str((string) parse_url($pageTwoUrl, PHP_URL_QUERY), $query);
        $this->assertSame((string) $topic->id, $query['topic']);
        $this->assertSame('pagination lesson', $query['search']);

        $view = app(StudentLearningMaterialController::class)->index(
            app(LearningMaterialProgressionService::class)
        );
        $outputBufferLevel = ob_get_level();
        try {
            $html = $view->render();
        } finally {
            while (ob_get_level() > $outputBufferLevel) {
                ob_end_clean();
            }
        }
        $this->assertStringContainsString('Showing', $html);
        $this->assertStringContainsString('class="pagination"', $html);
        $this->assertStringContainsString('class="page-link"', $html);

        request()->query->replace([
            'topic' => $topic->id,
            'search' => 'pagination lesson',
            'page' => 2,
        ]);

        $secondPage = app(StudentLearningMaterialController::class)
            ->index(app(LearningMaterialProgressionService::class))
            ->getData()['materials'];

        $this->assertSame(10, $secondPage->total());
        $this->assertSame(10, $secondPage->firstItem());
        $this->assertSame(10, $secondPage->lastItem());
        $this->assertFalse($secondPage->hasMorePages());
        $this->assertStringContainsString('topic='.$topic->id, (string) $secondPage->previousPageUrl());
        $this->assertStringContainsString('search=pagination', (string) $secondPage->previousPageUrl());
    }

    public function test_learning_material_quiz_must_be_passed_before_the_next_topic_level_unlocks(): void
    {
        $initialOutputBufferLevel = ob_get_level();
        Http::fake([
            '*' => Http::response($this->geminiQuizResponse([
                ['question' => 'What does a loop do?', 'answer' => 'Repeats instructions', 'options' => ['Repeats instructions', 'Deletes variables', 'Changes a browser', 'Stops a program']],
                ['question' => 'What is an algorithm?', 'answer' => 'Steps for solving a problem', 'options' => ['Steps for solving a problem', 'A type of computer', 'A color palette', 'A database table']],
                ['question' => 'What does sequence mean?', 'answer' => 'Steps in order', 'options' => ['Steps in order', 'Random choices', 'Repeated errors', 'A stored password']],
                ['question' => 'What does selection use?', 'answer' => 'A condition', 'options' => ['A condition', 'A picture', 'A keyboard', 'A file name']],
                ['question' => 'What is debugging?', 'answer' => 'Finding and fixing problems', 'options' => ['Finding and fixing problems', 'Drawing a canvas', 'Sorting folders', 'Adding a password']],
            ])),
        ]);

        $student = $this->studentWithInterests(['Coding']);
        $topic = Topic::create([
            'name' => 'Coding Course',
            'description' => 'A sequential coding course.',
            'is_active' => true,
        ]);
        $firstLevel = LearningMaterial::create([
            'title' => 'Coding Level One',
            'topic' => $topic->name,
            'topic_id' => $topic->id,
            'content' => 'A lesson about algorithms, sequence, selection, and repetition.',
            'difficulty' => 'beginner',
            'is_published' => true,
        ]);
        $secondLevel = LearningMaterial::create([
            'title' => 'Coding Level Two',
            'topic' => $topic->name,
            'topic_id' => $topic->id,
            'content' => 'A second lesson about programming decisions.',
            'difficulty' => 'beginner',
            'is_published' => true,
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.learning-materials.show', $secondLevel))
            ->assertRedirect(route('student.learning-materials.show', $firstLevel));

        $this->get(route('student.learning-materials.show', $firstLevel))
            ->assertOk()
            ->assertDontSee('Lesson Quiz');

        $this->post(route('student.learning-materials.complete', $firstLevel))
            ->assertRedirect(route('student.learning-materials.show', $firstLevel));

        $this->get(route('student.learning-materials.show', $firstLevel))
            ->assertOk()
            ->assertSee('Lesson Quiz')
            ->assertSee('What does a loop do?')
            ->assertSee('Submit quiz');

        $questions = $this->sampleLessonQuiz();
        $failingAnswers = array_column($questions, 'answer');
        $failingAnswers[0] = 'Wrong';
        $failingAnswers[1] = 'Wrong';
        $failingAnswers[2] = 'Wrong';

        $this->post(route('student.learning-materials.quiz.submit', $firstLevel), [
            'answers' => $failingAnswers,
        ])->assertRedirect(route('student.learning-materials.show', $firstLevel));

        $this->assertNull(
            LearningMaterialProgress::query()
                ->where('user_id', $student->id)
                ->where('learning_material_id', $firstLevel->id)
                ->value('quiz_completed_at')
        );
        $this->get(route('student.learning-materials.show', $secondLevel))
            ->assertRedirect(route('student.learning-materials.show', $firstLevel));

        $passingAnswers = array_column($questions, 'answer');
        $passingAnswers[4] = 'Wrong';
        $this->post(route('student.learning-materials.quiz.submit', $firstLevel), [
            'answers' => $passingAnswers,
        ])->assertRedirect(route('student.learning-materials.show', $firstLevel));

        $this->assertSame(
            80,
            LearningMaterialProgress::query()
                ->where('user_id', $student->id)
                ->where('learning_material_id', $firstLevel->id)
                ->value('quiz_score')
        );

        $this->post(route('student.learning-materials.quiz.submit', $firstLevel), [
            'answers' => array_fill(0, 5, 'Wrong'),
        ])->assertStatus(409);
        $this->assertNotNull(
            LearningMaterialProgress::query()
                ->where('user_id', $student->id)
                ->where('learning_material_id', $firstLevel->id)
                ->value('quiz_completed_at')
        );

        $this->get(route('student.learning-materials.show', $secondLevel))
            ->assertOk()
            ->assertSee('Level 2')
            ->assertSee('Mark as completed');

        while (ob_get_level() > $initialOutputBufferLevel) {
            ob_end_clean();
        }
    }

    public function test_daily_mission_uses_an_auto_approved_game_quest(): void
    {
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $student = $this->studentWithInterests(['Art']);
        $topic = Topic::create([
            'name' => 'Art & Design',
            'description' => 'A design course.',
            'is_active' => true,
        ]);
        $lesson = LearningMaterial::create([
            'title' => 'Color and Contrast',
            'topic' => $topic->name,
            'topic_id' => $topic->id,
            'content' => 'Contrast helps important shapes stand out.',
            'difficulty' => 'beginner',
            'is_published' => true,
        ]);

        $response = $this->actingAs($student, 'student')
            ->get(route('student.dashboard'));
        $task = LearningTask::query()->where('user_id', $student->id)->firstOrFail();

        $response
            ->assertOk()
            ->assertSee('Daily Mission')
            ->assertSee($task->title);
        $this->assertSame('approved', $task->review_status);

        while (ob_get_level() > 1) {
            ob_end_clean();
        }
    }

    public function test_project_challenges_are_separate_from_game_quests_and_admin_reviews_submissions_for_xp(): void
    {
        Http::fake([
            '*' => Http::response($this->projectChallengeResponse()),
        ]);
        $student = $this->studentWithInterests(['Coding', 'Art']);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Project Challenges')
            ->assertSee('Your project challenge is under admin review')
            ->assertSee('Automatic challenge checks: Passed')
            ->assertSee('Build a practical portfolio website')
            ->assertSee('This project challenge is waiting for admin approval.')
            ->assertSee('Waiting for admin approval');

        $challenge = ProjectChallenge::query()
            ->where('user_id', $student->id)
            ->latest()
            ->firstOrFail();
        $this->assertSame('Coding', $challenge->interest);
        $this->assertSame('passed', $challenge->ai_verification_status);
        $this->assertSame('pending_review', $challenge->review_status);
        $this->assertSame(0, ProjectChallengeSubmission::query()->count());
        $this->assertSame(0, LearningTask::query()->where('title', $challenge->title)->count());

        $admin = UserRegister::create([
            'name' => 'Project Reviewer',
            'email' => 'project-reviewer@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.project-challenges.index'))
            ->assertOk()
            ->assertSee($challenge->title)
            ->assertSee('Needs review');
        $this->put(route('admin.project-challenges.review', $challenge), [
            'decision' => 'approve',
            'interest' => 'Coding',
            'title' => 'Build a practical portfolio website',
            'project_brief' => 'Design and build a responsive portfolio that presents a student’s work.',
            'objectives' => [
                'Create a responsive page layout.',
                'Present at least three projects clearly.',
                'Check the page on desktop and mobile.',
            ],
            'deliverables' => [
                'A public project repository.',
                'A deployed portfolio link.',
            ],
            'difficulty' => 'beginner',
            'xp' => 45,
            'review_note' => 'Practical and suitable for the student.',
        ])->assertRedirect(route('admin.project-challenges.show', $challenge));
        $this->assertSame('approved', $challenge->fresh()->review_status);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.project-challenges.index'))
            ->assertOk()
            ->assertSee($challenge->title)
            ->assertSee('Approved');

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Build a practical portfolio website')
            ->assertSee('Start challenge');

        $this->actingAs($student, 'student')
            ->get(route('student.project-challenges.show', $challenge))
            ->assertOk()
            ->assertSee('Project brief')
            ->assertSee('Automatic challenge checks: Passed')
            ->assertSee('Submit your project');
        $this->post(route('student.project-challenges.submit', $challenge), [
            'project_url' => 'https://github.com/student/portfolio',
            'reflection' => 'I built a responsive portfolio with three project cards and checked the layout on mobile.',
        ])->assertRedirect(route('student.project-challenges.show', $challenge));

        $submission = ProjectChallengeSubmission::query()->firstOrFail();
        $this->actingAs($admin, 'admin')
            ->put(route('admin.project-challenges.submissions.review', [$challenge, $submission]), [
                'decision' => 'request_revision',
                'review_note' => 'Please add a mobile screenshot to the repository.',
            ])
            ->assertRedirect(route('admin.project-challenges.show', $challenge));
        $this->assertSame('revision_requested', $submission->fresh()->status);

        $this->actingAs($student, 'student')
            ->get(route('student.project-challenges.show', $challenge))
            ->assertOk()
            ->assertSee('Please add a mobile screenshot to the repository.');
        Storage::fake('public');
        $this->post(route('student.project-challenges.submit', $challenge), [
            'evidence_image' => UploadedFile::fake()->createWithContent(
                'planting.png',
                base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/jXcAAAAASUVORK5CYII=')
            ),
            'reflection' => 'I planted a native sapling, photographed the work, and wrote down how I will care for it.',
        ])->assertRedirect(route('student.project-challenges.show', $challenge));

        $submission = ProjectChallengeSubmission::query()->orderByDesc('id')->firstOrFail();
        $this->assertNull($submission->project_url);
        $this->assertNotNull($submission->evidence_image);
        Storage::disk('public')->assertExists($submission->evidence_image);
        $this->actingAs($student, 'student')
            ->get(route('student.project-challenges.show', $challenge))
            ->assertOk()
            ->assertSee('Uploaded project evidence');

        $this->actingAs($admin, 'admin')
            ->get(route('admin.project-challenges.show', $challenge))
            ->assertOk()
            ->assertSee('Uploaded project evidence');
        $this->actingAs($admin, 'admin')
            ->put(route('admin.project-challenges.submissions.review', [$challenge, $submission]), [
                'decision' => 'accept',
                'review_note' => 'The submitted project meets the objectives.',
            ])
            ->assertRedirect(route('admin.project-challenges.show', $challenge));
        $this->assertSame('accepted', $submission->fresh()->status);
        $this->assertSame(2, ProjectChallengeSubmission::query()->where('project_challenge_id', $challenge->id)->count());
        $this->assertSame(45, (int) StudentProgress::query()->where('user_id', $student->id)->value('total_xp'));
        $this->assertNotNull($challenge->fresh()->xp_awarded_at);

        $this->actingAs($student, 'student')
            ->get(route('student.project-challenges.show', $challenge))
            ->assertOk()
            ->assertSee('Your project has been accepted.');

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Your project challenge for today has been delivered');
        $this->assertSame(1, ProjectChallenge::query()->where('user_id', $student->id)->count());
        Http::assertSent(fn (ClientRequest $request) => str_contains(
            $request->body(),
            'Build a practical portfolio website'
        ) && str_contains($request->body(), 'real-world learning activities')
            && str_contains($request->body(), 'plant and care for a suitable native plant'));
    }

    public function test_students_receive_at_most_one_project_challenge_per_day(): void
    {
        $this->travelTo(now()->startOfDay());
        $student = $this->studentWithInterests(['Coding']);
        $previousChallenge = ProjectChallenge::create([
            'user_id' => $student->id,
            'interest' => 'Coding',
            'title' => 'Completed project from yesterday',
            'project_brief' => 'Complete a useful project connected to coding and explain what you learned.',
            'objectives' => ['Build a small tool.', 'Explain how it works.', 'Reflect on what you learned.'],
            'deliverables' => ['A project file.', 'A short reflection.'],
            'difficulty' => 'beginner',
            'xp' => 40,
            'review_status' => 'approved',
        ]);
        $previousChallenge->created_at = now()->subDay();
        $previousChallenge->save();
        ProjectChallengeSubmission::create([
            'project_challenge_id' => $previousChallenge->id,
            'user_id' => $student->id,
            'reflection' => 'I completed and reviewed the project.',
            'status' => 'accepted',
        ]);

        $challengeGenerationCount = 0;
        Http::fake(function (ClientRequest $request) use (&$challengeGenerationCount) {
            if (str_contains($request->body(), 'You design practical, real-world learning activities')) {
                $challengeGenerationCount++;

                return Http::response($this->projectChallengeResponse(
                    $challengeGenerationCount === 1
                        ? 'Coding activity for the day'
                        : 'Coding activity for tomorrow'
                ));
            }

            return Http::response([], 200);
        });

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Coding activity for the day');
        $todayChallenge = ProjectChallenge::query()
            ->where('user_id', $student->id)
            ->latest()
            ->firstOrFail();
        $this->assertSame('Coding', $todayChallenge->interest);

        ProjectChallengeSubmission::create([
            'project_challenge_id' => $todayChallenge->id,
            'user_id' => $student->id,
            'reflection' => "I completed and reviewed today's project.",
            'status' => 'accepted',
        ]);
        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Your project challenge for today has been delivered');
        $this->assertSame(2, ProjectChallenge::query()->where('user_id', $student->id)->count());
        $this->assertSame(1, $challengeGenerationCount);

        $this->travelTo(now()->addDay());
        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Coding activity for tomorrow');
        $this->assertSame(3, ProjectChallenge::query()->where('user_id', $student->id)->count());
        $this->assertSame(2, $challengeGenerationCount);
    }

    public function test_configured_ai_verification_rules_reject_challenges_that_do_not_meet_objective_count(): void
    {
        SiteSetting::create([
            'key' => 'ai_verification',
            'value' => [
                'minimum_objectives' => 4,
                'minimum_brief_characters' => 40,
                'maximum_xp' => 75,
            ],
        ]);
        Http::fake([
            '*' => Http::response($this->projectChallengeResponse()),
        ]);

        $challenge = app(GeminiService::class)->generateProjectChallenge(
            ['Coding'],
            'Learn by building something useful',
            'beginner'
        );

        $this->assertNull($challenge);
        Http::assertSent(fn (ClientRequest $request) => str_contains(
            $request->body(),
            '4-5 measurable objectives'
        ) && str_contains($request->body(), '20 to 75'));
    }

    public function test_progress_page_paginates_completed_quests_and_shows_started_materials(): void
    {
        $student = $this->studentWithInterests(['Coding']);

        foreach (range(1, 10) as $number) {
            LearningTask::create([
                'user_id' => $student->id,
                'title' => "Completed quest {$number}",
                'description' => 'A completed learning quest.',
                'category' => 'coding',
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'completed',
                'game_data' => [],
            ]);

            $material = LearningMaterial::create([
                'title' => "Progress lesson {$number}",
                'topic' => 'Programming',
                'content' => 'Lesson content.',
                'difficulty' => 'beginner',
                'is_published' => true,
            ]);

            LearningMaterialProgress::create([
                'user_id' => $student->id,
                'learning_material_id' => $material->id,
                'started_at' => now()->subDays($number),
                'completed_at' => $number % 2 === 0 ? now()->subDays($number - 1) : null,
            ]);
        }

        $this->actingAs($student, 'student')
            ->get(route('student.progress'))
            ->assertOk()
            ->assertSee('Completed quest 10')
            ->assertSee('Progress lesson 10')
            ->assertSee('In progress')
            ->assertSee('Completed')
            ->assertSee('Quiz pending')
            ->assertSee('Interest Course Progress')
            ->assertSee('Coding')
            ->assertSee('10/25 games')
            ->assertDontSee('Badges Earned')
            ->assertSee('completed_page=2')
            ->assertSee('materials_page=2');

        $secondQuestPage = $this->actingAs($student, 'student')
            ->get(route('student.progress', ['completed_page' => 2]))
            ->assertOk()
            ->assertSee('Completed quest 2')
            ->assertSee('Progress lesson 10');
        $this->assertStringNotContainsString('Completed quest 10', $secondQuestPage->getContent());

        $secondMaterialPage = $this->actingAs($student, 'student')
            ->get(route('student.progress', ['materials_page' => 2]))
            ->assertOk()
            ->assertSee('Progress lesson 2')
            ->assertSee('Completed quest 10');
        $this->assertStringNotContainsString('Progress lesson 10', $secondMaterialPage->getContent());

        while (ob_get_level() > 1) {
            ob_end_clean();
        }
    }

    public function test_quest_champion_uses_completed_quest_records_and_is_awarded_once(): void
    {
        $initialOutputBufferLevel = ob_get_level();
        $this->seed(BadgeSeeder::class);
        $student = $this->studentWithInterests(['Coding']);
        StudentProgress::create([
            'user_id' => $student->id,
            'total_xp' => 265,
            'level' => 3,
            'completed_tasks' => 9,
            'subject_xp' => [],
        ]);

        foreach (range(1, 12) as $number) {
            LearningTask::create([
                'user_id' => $student->id,
                'title' => "Legacy completed quest {$number}",
                'description' => 'Previously completed quest.',
                'category' => 'coding',
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'completed',
                'game_data' => [],
            ]);
        }

        $this->actingAs($student, 'student')
            ->get(route('student.badges'))
            ->assertOk()
            ->assertSee('Quest Champion');

        $champion = Badge::where('name', 'Quest Champion')->firstOrFail();
        $this->assertSame(
            1,
            StudentBadge::where('user_id', $student->id)
                ->where('badge_id', $champion->id)
                ->count()
        );

        $this->actingAs($student, 'student')
            ->get(route('student.progress'))
            ->assertOk()
            ->assertSee('Interest Course Progress')
            ->assertDontSee('Quest Champion')
            ->assertDontSee('Badges Earned');

        $this->assertSame(
            1,
            StudentBadge::where('user_id', $student->id)
                ->where('badge_id', $champion->id)
                ->count()
        );

        while (ob_get_level() > $initialOutputBufferLevel) {
            ob_end_clean();
        }
    }

    public function test_interest_material_seeder_adds_published_content_for_registered_interests_once(): void
    {
        $this->studentWithInterests(['Coding', 'Chess']);

        $this->seed(InterestLearningMaterialsSeeder::class);
        $this->seed(InterestLearningMaterialsSeeder::class);

        $topics = Topic::query()->orderBy('name')->get();
        $materials = LearningMaterial::query()->with('topicRelation')->get();

        $this->assertSame(['Chess', 'Coding'], $topics->pluck('name')->all());
        $this->assertCount(4, $materials);
        $this->assertTrue($materials->every(
            fn (LearningMaterial $material) => $material->is_published
                && $material->content !== ''
                && in_array($material->topic, ['Chess', 'Coding'], true)
                && $material->topicRelation?->name === $material->topic
        ));
    }

    public function test_dashboard_generates_missing_interest_tasks_when_other_pending_tasks_exist(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $this->codingTask(),
                $this->chessTask(),
                $this->mathTask(),
            ])),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);
        $codingGame = Game::query()->where('game_type', 'creative_coding')->firstOrFail();
        LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $codingGame->id,
            'game_type' => 'creative_coding',
            'title' => 'Existing coding quest',
            'description' => 'A coding quest already exists.',
            'category' => 'coding',
            'difficulty' => 'beginner',
            'xp' => 20,
            'status' => 'pending',
            'game_data' => $this->codingTask()['game_data'],
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk();

        $tasks = LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'pending')
            ->get();

        $this->assertCount(2, $tasks);
        $this->assertEqualsCanonicalizing(
            ['creative_coding', 'chess_tactics'],
            $tasks->pluck('game_type')->all()
        );
        $this->assertSame(
            ['Find the winning fork'],
            $tasks->where('game_type', 'chess_tactics')->pluck('title')->all()
        );

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk();

        $this->assertSame(2, LearningTask::query()->where('user_id', $student->id)->count());
        Http::assertSentCount(3);
    }

    public function test_dashboard_generates_fresh_quests_after_all_current_quests_are_completed(): void
    {
        $freshCodingTask = array_replace($this->codingTask(), [
            'title' => 'Draw a spiral with a loop',
        ]);
        $freshChessTask = array_replace($this->chessTask(), [
            'title' => 'Win material with a skewer',
        ]);
        Http::fake([
            '*' => Http::response($this->geminiResponse([
                $freshCodingTask,
                $freshChessTask,
            ])),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);

        foreach ([
            ['game_type' => 'creative_coding', 'category' => 'coding', 'title' => $this->codingTask()['title']],
            ['game_type' => 'chess_tactics', 'category' => 'chess', 'title' => $this->chessTask()['title']],
        ] as $completedTask) {
            $game = Game::query()->where('game_type', $completedTask['game_type'])->firstOrFail();
            LearningTask::create([
                'user_id' => $student->id,
                'game_id' => $game->id,
                'game_type' => $game->game_type,
                'title' => $completedTask['title'],
                'description' => 'Previously completed quest.',
                'category' => $completedTask['category'],
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'completed',
                'game_data' => $completedTask['game_type'] === 'creative_coding'
                    ? $this->codingTask()['game_data']
                    : $this->chessTask()['game_data'],
            ]);
        }

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($freshCodingTask['title'])
            ->assertSee($freshChessTask['title']);

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($freshCodingTask['title'])
            ->assertSee($freshChessTask['title']);

        $this->assertSame(2, LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'pending')
            ->count());
        $this->assertSame(2, LearningTask::query()
            ->where('user_id', $student->id)
            ->where('status', 'completed')
            ->count());

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk();

        $this->assertSame(4, LearningTask::query()->where('user_id', $student->id)->count());
        Http::assertSentCount(4);
    }

    public function test_dashboard_generates_interest_matched_fallback_quests_when_gemini_is_unavailable(): void
    {
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Circle Orbit - Round 1')
            ->assertSee('Forcing Moves - Round 1')
            ->assertDontSee('No new quests could be loaded')
            ->assertDontSee('Try again');

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Circle Orbit - Round 1')
            ->assertSee('Forcing Moves - Round 1');

        $this->assertEqualsCanonicalizing(
            ['creative_coding', 'chess_tactics'],
            LearningTask::query()
                ->where('user_id', $student->id)
                ->where('status', 'pending')
                ->pluck('game_type')
                ->all()
        );
    }

    public function test_chess_engine_game_and_view_are_playable_but_unrelated_games_are_blocked(): void
    {
        $this->assertDatabaseHas('game_engines', [
            'engine_key' => 'chess_tactics',
            'view_name' => 'student.games.chess-tactics',
            'is_enabled' => true,
        ]);
        $this->assertDatabaseHas('games', [
            'game_type' => 'chess_tactics',
            'category' => 'chess',
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);
        $chessGame = Game::query()->where('game_type', 'chess_tactics')->firstOrFail();
        $chessTask = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $chessGame->id,
            'game_type' => 'chess_tactics',
            'title' => 'Find the winning fork',
            'description' => 'Solve the chess tactic.',
            'category' => 'chess',
            'difficulty' => 'beginner',
            'xp' => 20,
            'status' => 'pending',
            'game_data' => $this->chessTask()['game_data'],
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.game.play', $chessTask))
            ->assertOk()
            ->assertSee('CHESS TACTICS QUEST');

        $mathGame = Game::query()->where('game_type', 'arithmetic_speed')->firstOrFail();
        $mathTask = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $mathGame->id,
            'game_type' => 'arithmetic_speed',
            'title' => 'Math task not in preferences',
            'description' => 'This should not be playable for this student.',
            'category' => 'mathematics',
            'difficulty' => 'beginner',
            'xp' => 20,
            'status' => 'pending',
            'game_data' => $this->mathTask()['game_data'],
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.game.play', $mathTask))
            ->assertRedirect(route('student.dashboard'));
    }

    public function test_all_enabled_game_views_render_for_matching_student_interests(): void
    {
        $student = $this->studentWithInterests([
            'Coding',
            'Chess',
            'Mathematics',
            'Environment',
            'Art',
            'Music',
        ]);

        $taskCases = [
            [
                'game_type' => 'arithmetic_speed',
                'title' => 'Math practice',
                'category' => 'mathematics',
                'game_data' => [
                    'instructions' => 'Answer each question.',
                    'questions' => [
                        ['question' => '2 + 2', 'answer' => '4', 'options' => ['3', '4']],
                    ],
                ],
                'expected' => 'Math Speed Challenge',
            ],
            [
                'game_type' => 'creative_coding',
                'title' => 'Draw a pattern',
                'category' => 'coding',
                'game_data' => [
                    'instructions' => 'Draw a pattern.',
                    'starter_code' => 'const canvas = document.getElementById("previewCanvas"); const ctx = canvas.getContext("2d"); ctx.fillRect(0, 0, 10, 10);',
                    'challenges' => ['Draw at least one visible shape.'],
                ],
                'expected' => 'Draw a pattern',
            ],
            [
                'game_type' => 'sorting_challenge',
                'title' => 'Sort the materials',
                'category' => 'environment',
                'game_data' => [
                    'instructions' => 'Sort these materials.',
                    'items' => [
                        ['label' => 'Paper', 'correct_category' => 'Recyclable'],
                        ['label' => 'Food scraps', 'correct_category' => 'Compost'],
                    ],
                ],
                'expected' => 'Paper',
            ],
            [
                'game_type' => 'chess_tactics',
                'title' => 'Solve the chess tactic',
                'category' => 'chess',
                'game_data' => $this->chessTask()['game_data'],
                'expected' => 'Solve the chess tactic',
            ],
            [
                'game_type' => 'art_color_theory',
                'title' => 'Build a warm palette',
                'category' => 'art',
                'game_data' => $this->artTask()['game_data'],
                'expected' => 'Build a warm palette',
            ],
            [
                'game_type' => 'music_note_rhythm',
                'title' => 'Find the steady beat',
                'category' => 'music',
                'game_data' => $this->musicTask()['game_data'],
                'expected' => 'Find the steady beat',
            ],
        ];

        foreach ($taskCases as $case) {
            $game = Game::query()->where('game_type', $case['game_type'])->firstOrFail();
            $task = LearningTask::create([
                'user_id' => $student->id,
                'game_id' => $game->id,
                'game_type' => $game->game_type,
                'title' => $case['title'],
                'description' => 'Test task description.',
                'category' => $case['category'],
                'difficulty' => 'beginner',
                'xp' => 20,
                'status' => 'pending',
                'game_data' => $case['game_data'],
            ]);

            $response = $this->actingAs($student, 'student')
                ->get(route('student.game.play', $task))
                ->assertOk();
            $response->assertSee($case['expected']);

            if ($case['game_type'] === 'creative_coding') {
                $this->assertStringContainsString('gameCanvas', $response->getContent());
                $response->assertSee('Draw at least one visible shape.');
            }
        }
    }

    public function test_wrong_answers_do_not_award_xp_and_correct_answers_are_graded_server_side(): void
    {
        $this->seed(BadgeSeeder::class);
        $student = $this->studentWithInterests(['Coding', 'Chess']);
        $chessGame = Game::query()->where('game_type', 'chess_tactics')->firstOrFail();
        $task = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $chessGame->id,
            'game_type' => 'chess_tactics',
            'title' => 'Find the winning fork',
            'description' => 'Solve the chess tactic.',
            'category' => 'chess',
            'difficulty' => 'beginner',
            'xp' => 30,
            'status' => 'pending',
            'game_data' => $this->chessTask()['game_data'],
        ]);

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'score' => 100,
                'answers' => ['Move the rook'],
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'passed' => false,
                'xp_earned' => 0,
            ]);

        $this->assertSame('pending', $task->fresh()->status);
        $this->assertSame(0, (int) StudentProgress::where('user_id', $student->id)->value('total_xp'));

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'score' => 0,
                'answers' => ['Knight to e5'],
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'passed' => true,
                'xp_earned' => 30,
                'total_xp' => 30,
                'badges' => [
                    ['name' => 'First Quest'],
                ],
            ]);

        $this->assertSame('completed', $task->fresh()->status);
        $this->assertDatabaseHas('student_badges', [
            'user_id' => $student->id,
            'badge_id' => Badge::query()->where('name', 'First Quest')->value('id'),
        ]);

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'answers' => ['Knight to e5'],
            ])
            ->assertStatus(409);

        $this->assertSame(30, (int) StudentProgress::where('user_id', $student->id)->value('total_xp'));
        $this->assertSame(1, (int) StudentProgress::where('user_id', $student->id)->value('completed_tasks'));
    }

    public function test_math_task_ignores_client_score_and_awards_xp_only_for_correct_answers(): void
    {
        $student = $this->studentWithInterests(['Mathematics']);
        $game = Game::query()->where('game_type', 'arithmetic_speed')->firstOrFail();
        $task = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $game->id,
            'game_type' => 'arithmetic_speed',
            'title' => 'Arithmetic challenge',
            'description' => 'Solve two arithmetic problems.',
            'category' => 'mathematics',
            'difficulty' => 'beginner',
            'xp' => 40,
            'status' => 'pending',
            'game_data' => [
                'instructions' => 'Answer both questions.',
                'questions' => [
                    ['question' => '5 + 7', 'answer' => 12, 'options' => [11, 12, 13]],
                    ['question' => '9 - 4', 'answer' => 5, 'options' => [4, 5, 6]],
                ],
            ],
        ]);

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'score' => 100,
                'answers' => ['wrong', 'wrong'],
            ])
            ->assertOk()
            ->assertJson([
                'passed' => false,
                'xp_earned' => 0,
            ]);

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'score' => 0,
                'answers' => ['12', '5'],
            ])
            ->assertOk()
            ->assertJson([
                'passed' => true,
                'correct_answers' => 2,
                'total_questions' => 2,
                'xp_earned' => 40,
                'total_xp' => 40,
            ]);
    }

    public function test_creative_coding_can_retry_after_a_submission_that_does_not_complete_the_quest(): void
    {
        $student = $this->studentWithInterests(['Coding']);
        $game = Game::query()->where('game_type', 'creative_coding')->firstOrFail();
        $task = LearningTask::create([
            'user_id' => $student->id,
            'game_id' => $game->id,
            'game_type' => 'creative_coding',
            'title' => 'Draw a pattern',
            'description' => 'Draw a visual pattern on the canvas.',
            'category' => 'coding',
            'difficulty' => 'beginner',
            'xp' => 25,
            'status' => 'pending',
            'game_data' => [
                'instructions' => 'Draw a visual pattern.',
                'starter_code' => 'ctx.fillRect(10, 10, 20, 20);',
                'challenges' => ['Draw at least one visible shape.'],
            ],
        ]);

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'code' => '// ctx.fillRect(10, 10, 20, 20);',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'passed' => false,
                'xp_earned' => 0,
            ]);

        $this->assertSame('pending', $task->fresh()->status);
        $this->assertSame(0, (int) StudentProgress::where('user_id', $student->id)->value('total_xp'));

        $this->actingAs($student, 'student')
            ->postJson(route('student.game.complete', $task), [
                'code' => 'ctx.fillRect(10, 10, 20, 20);',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'passed' => true,
                'xp_earned' => 25,
            ]);

        $this->assertSame('completed', $task->fresh()->status);
    }

    public function test_recommendations_chat_is_authenticated_and_personalized(): void
    {
        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    ['content' => ['parts' => [['text' => json_encode(['reply' => 'Try a chess tactic after your coding practice.'])]]]],
                ],
            ]),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);
        $this->actingAs($student, 'student')
            ->postJson(route('student.ai-recommendations.chat'), [
                'message' => 'What should I learn next?',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'reply' => 'Try a chess tactic after your coding practice.',
                'mode' => 'gemini',
            ]);

        Http::assertSent(fn (ClientRequest $request) => str_contains($request['contents'][0]['parts'][0]['text'], 'Coding')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Chess')
        );
    }

    public function test_recommendations_chat_remains_usable_when_gemini_is_unreachable(): void
    {
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $student = $this->studentWithInterests(['Coding', 'Chess']);
        $this->actingAs($student, 'student')
            ->postJson(route('student.ai-recommendations.chat'), [
                'message' => 'Can you recommend what to learn next?',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'mode' => 'offline',
            ])
            ->assertJsonPath(
                'reply',
                'A good next step is to choose one topic from Coding, Chess and practice it with a small challenge. Which topic would you like to explore first?'
            );

        Http::assertSentCount(2);
    }

    public function test_dashboard_shows_top_xp_ranking_and_the_students_position(): void
    {
        $initialOutputBufferLevel = ob_get_level();
        Http::fake([
            '*' => Http::response([], 503),
        ]);

        $student = $this->studentWithInterests(['Coding']);
        StudentProgress::create([
            'user_id' => $student->id,
            'total_xp' => 25,
            'level' => 1,
            'completed_tasks' => 0,
        ]);

        $leader = UserRegister::create([
            'name' => 'Leaderboard Leader',
            'email' => 'leader@example.test',
            'password' => 'password',
            'role' => 'student',
        ]);
        StudentProgress::create([
            'user_id' => $leader->id,
            'total_xp' => 100,
            'level' => 1,
            'completed_tasks' => 0,
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee(route('student.leaderboard'))
            ->assertDontSee('Leaderboard Leader');

        $this->get(route('student.leaderboard'))
            ->assertOk()
            ->assertSee('Leaderboard Leader')
            ->assertSee('Your rank')
            ->assertSee('#2')
            ->assertSee('You');

        while (ob_get_level() > $initialOutputBufferLevel) {
            ob_end_clean();
        }
    }

    public function test_admin_xp_corrections_require_a_reason_and_are_audited(): void
    {
        $admin = UserRegister::create([
            'name' => 'Leaderboard Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $student = UserRegister::create([
            'name' => 'Ranked Student',
            'email' => 'ranked@example.test',
            'password' => 'password',
            'role' => 'student',
        ]);
        UserRegister::create([
            'name' => 'Other Student',
            'email' => 'other@example.test',
            'password' => 'password',
            'role' => 'student',
        ]);
        StudentProgress::create([
            'user_id' => $student->id,
            'total_xp' => 150,
            'level' => 4,
            'completed_tasks' => 8,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.leaderboard.index', ['search' => 'ranked@']))
            ->assertOk()
            ->assertSee('Ranked Student')
            ->assertSee('ranked@example.test')
            ->assertDontSee('Other Student');

        $this->post(route('admin.leaderboard.xp.adjust', $student), [
            'xp_change' => 100,
            'reason' => 'Verified missing quest reward',
            'search' => 'ranked@',
        ])->assertRedirect(route('admin.leaderboard.index', ['search' => 'ranked@']));

        $this->assertSame(250, (int) $student->progress()->value('total_xp'));
        $this->assertSame(4, (int) $student->progress()->value('level'));
        $this->assertSame(8, (int) $student->progress()->value('completed_tasks'));

        $this->post(route('admin.leaderboard.xp.adjust', $student), [
            'xp_change' => -251,
            'reason' => 'Invalid over-adjustment',
        ])->assertSessionHasErrors('xp_change');
        $this->assertSame(250, (int) $student->progress()->value('total_xp'));

        $this->post(route('admin.leaderboard.xp.adjust', $student), [
            'xp_change' => 10,
        ])->assertSessionHasErrors('reason');

        $this->post(route('admin.leaderboard.xp.adjust', $student), [
            'xp_change' => 0,
            'reason' => 'No-op',
        ])->assertSessionHasErrors('xp_change');

        $this->post(route('admin.leaderboard.xp.adjust', $student), [
            'xp_change' => -50,
            'reason' => 'Remove duplicated quest reward',
        ])->assertRedirect(route('admin.leaderboard.index'));

        $this->assertSame(200, (int) $student->progress()->value('total_xp'));
        $this->assertSame(2, LeaderboardXpAdjustment::count());
        $this->assertDatabaseHas('leaderboard_xp_adjustments', [
            'user_id' => $student->id,
            'admin_id' => $admin->id,
            'xp_change' => 100,
            'reason' => 'Verified missing quest reward',
        ]);
        $this->assertDatabaseHas('leaderboard_xp_adjustments', [
            'user_id' => $student->id,
            'admin_id' => $admin->id,
            'xp_change' => -50,
            'reason' => 'Remove duplicated quest reward',
        ]);

        $this->get(route('admin.leaderboard.index'))
            ->assertOk()
            ->assertSee('XP adjustment history')
            ->assertSee('Verified missing quest reward')
            ->assertSee('Remove duplicated quest reward');
    }

    public function test_ai_generated_game_quests_are_playable_without_admin_approval(): void
    {
        Http::fake([
            '*' => Http::response($this->geminiResponse([$this->codingTask()])),
        ]);

        $student = $this->studentWithInterests(['Coding']);

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee($this->codingTask()['title'])
            ->assertSee('Start Quest')
            ->assertDontSee('Awaiting approval');

        $task = LearningTask::query()
            ->where('user_id', $student->id)
            ->firstOrFail();
        $this->assertSame('approved', $task->review_status);

        $this->get(route('student.game.play', $task))
            ->assertOk()
            ->assertSee($this->codingTask()['game_data']['instructions']);
        $this->postJson(route('student.game.complete', $task), [
            'code' => 'ctx.fillRect(10, 10, 20, 20);',
        ])->assertOk()->assertJson([
            'passed' => true,
            'xp_earned' => $this->codingTask()['xp'],
            'total_xp' => $this->codingTask()['xp'],
        ]);
    }

    private function studentWithInterests(array $interests): UserRegister
    {
        $student = UserRegister::create([
            'name' => 'Feature Student',
            'email' => uniqid('student-', true).'@example.test',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        StudentPreference::create([
            'user_id' => $student->id,
            'education_level' => 'Secondary',
            'class_semester' => '10',
            'institution' => 'Test School',
            'experience_level' => 'beginner',
            'interests' => $interests,
            'learning_goal' => 'Learn through play',
            'experience_preference' => 'Interactive',
            'ai_profile' => [
                'visual_theme' => 'chess',
                'rank_title' => 'Knight Tactician',
                'dashboard_title' => 'Test Dashboard',
            ],
        ]);

        return $student;
    }

    private function createTestSchema(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('game_type')->nullable();
            $table->string('difficulty')->default('beginner');
            $table->timestamps();
        });

        Schema::create('game_engines', function (Blueprint $table) {
            $table->id();
            $table->string('engine_key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('view_name')->nullable();
            $table->json('supported_interests')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });

        Schema::create('user_registers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('student');
            $table->timestamps();
        });

        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value');
            $table->timestamps();
        });

        Schema::create('student_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('education_level');
            $table->string('class_semester');
            $table->string('institution');
            $table->string('experience_level');
            $table->json('interests');
            $table->string('learning_goal');
            $table->string('experience_preference');
            $table->json('ai_profile')->nullable();
            $table->timestamps();
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('topic');
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->text('description')->nullable();
            $table->longText('content');
            $table->string('difficulty')->default('beginner');
            $table->string('image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('learning_material_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('learning_material_id');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->json('quiz_data')->nullable();
            $table->unsignedTinyInteger('quiz_score')->nullable();
            $table->timestamp('quiz_completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'learning_material_id']);
        });

        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('total_xp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('completed_tasks')->default(0);
            $table->json('subject_xp')->nullable();
            $table->timestamps();
        });

        Schema::create('leaderboard_xp_adjustments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->integer('xp_change');
            $table->string('reason', 500);
            $table->timestamps();
        });

        Schema::create('learning_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('game_id')->nullable();
            $table->string('game_type')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('difficulty')->default('beginner');
            $table->integer('xp')->default(10);
            $table->enum('status', ['pending', 'completed', 'superseded'])->default('pending');
            $table->string('review_status', 20)->default('approved');
            $table->text('review_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->json('game_data')->nullable();
            $table->timestamps();
        });

        Schema::create('project_challenges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('interest');
            $table->string('title');
            $table->text('project_brief');
            $table->json('objectives');
            $table->json('deliverables');
            $table->string('difficulty');
            $table->unsignedSmallInteger('xp');
            $table->string('review_status')->default('pending_review');
            $table->string('ai_verification_status')->default('passed');
            $table->text('review_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('xp_awarded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('project_challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_challenge_id');
            $table->unsignedBigInteger('user_id');
            $table->string('project_url', 2048)->nullable();
            $table->string('evidence_image')->nullable();
            $table->text('reflection');
            $table->string('status')->default('submitted');
            $table->text('review_note')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('requirement_type');
            $table->unsignedInteger('requirement_value');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('student_badges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('badge_id');
            $table->timestamp('awarded_at');
            $table->timestamps();
            $table->unique(['user_id', 'badge_id']);
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->string('interest')->nullable();
            $table->unsignedBigInteger('quest_attempt_id')->nullable()->unique();
            $table->unsignedBigInteger('issued_by')->nullable();
            $table->string('certificate_number')->unique();
            $table->string('title')->default('Certificate of Achievement');
            $table->string('status')->default('issued');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'interest']);
        });
    }

    private function geminiResponse(array $tasks): array
    {
        return [
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => json_encode(['tasks' => $tasks], JSON_THROW_ON_ERROR)],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function geminiQuizResponse(array $questions): array
    {
        return [
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => json_encode(['questions' => $questions], JSON_THROW_ON_ERROR)],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function sampleLessonQuiz(): array
    {
        return [
            ['question' => 'What does a loop do?', 'answer' => 'Repeats instructions', 'options' => ['Repeats instructions', 'Deletes variables', 'Changes a browser', 'Stops a program']],
            ['question' => 'What is an algorithm?', 'answer' => 'Steps for solving a problem', 'options' => ['Steps for solving a problem', 'A type of computer', 'A color palette', 'A database table']],
            ['question' => 'What does sequence mean?', 'answer' => 'Steps in order', 'options' => ['Steps in order', 'Random choices', 'Repeated errors', 'A stored password']],
            ['question' => 'What does selection use?', 'answer' => 'A condition', 'options' => ['A condition', 'A picture', 'A keyboard', 'A file name']],
            ['question' => 'What is debugging?', 'answer' => 'Finding and fixing problems', 'options' => ['Finding and fixing problems', 'Drawing a canvas', 'Sorting folders', 'Adding a password']],
        ];
    }

    private function codingTask(): array
    {
        return [
            'title' => 'Build a chessboard pattern',
            'description' => 'Use a JavaScript loop to draw a repeating board pattern.',
            'category' => 'coding',
            'game_type' => 'creative_coding',
            'difficulty' => 'beginner',
            'xp' => 25,
            'game_data' => [
                'instructions' => 'Create a repeating visual pattern using a loop.',
                'starter_code' => 'const canvas = document.getElementById("previewCanvas");',
            ],
        ];
    }

    private function chessTask(): array
    {
        return [
            'title' => 'Find the winning fork',
            'description' => 'Choose the move that wins material with a knight fork.',
            'category' => 'chess',
            'game_type' => 'chess_tactics',
            'difficulty' => 'beginner',
            'xp' => 30,
            'game_data' => [
                'instructions' => 'Select the best move in each chess tactics position.',
                'puzzles' => [
                    [
                        'question' => 'Which move creates a knight fork?',
                        'answer' => 'Knight to e5',
                        'options' => ['Knight to e5', 'Move the rook', 'Advance the pawn'],
                    ],
                ],
            ],
        ];
    }

    private function artTask(): array
    {
        return [
            'title' => 'Build a warm palette',
            'description' => 'Identify the color relationship used by a warm palette.',
            'category' => 'art',
            'game_type' => 'art_color_theory',
            'difficulty' => 'beginner',
            'xp' => 20,
            'game_data' => [
                'instructions' => 'Choose the correct color-theory answer for each question.',
                'questions' => [
                    [
                        'question' => 'Which set is made entirely of warm colors?',
                        'answer' => 'Red, orange, and yellow',
                        'options' => [
                            'Red, orange, and yellow',
                            'Blue, green, and violet',
                            'Blue, orange, and green',
                        ],
                    ],
                ],
            ],
        ];
    }

    private function musicTask(): array
    {
        return [
            'title' => 'Find the steady beat',
            'description' => 'Recognize a basic note value and its rhythm.',
            'category' => 'music',
            'game_type' => 'music_note_rhythm',
            'difficulty' => 'beginner',
            'xp' => 20,
            'game_data' => [
                'instructions' => 'Choose the correct music and rhythm answer for each question.',
                'questions' => [
                    [
                        'question' => 'In common time, how many beats does a quarter note usually receive?',
                        'answer' => 'One beat',
                        'options' => ['One beat', 'Two beats', 'Four beats'],
                    ],
                ],
            ],
        ];
    }

    private function mathTask(): array
    {
        return [
            'title' => 'Math Speed Challenge',
            'description' => 'Answer arithmetic questions quickly.',
            'category' => 'mathematics',
            'game_type' => 'arithmetic_speed',
            'difficulty' => 'beginner',
            'xp' => 20,
            'game_data' => [
                'instructions' => 'Answer the questions.',
                'questions' => [
                    [
                        'question' => '2 + 2',
                        'answer' => '4',
                        'options' => ['3', '4', '5'],
                    ],
                ],
            ],
        ];
    }
}
