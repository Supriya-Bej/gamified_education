<?php

namespace Tests\Feature;

use App\Http\Controllers\StudentLearningMaterialController;
use App\Models\Badge;
use App\Models\Game;
use App\Models\LearningMaterial;
use App\Models\LearningMaterialProgress;
use App\Models\LearningTask;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
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
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Color Theory Studio')
            && str_contains($request['contents'][0]['parts'][0]['text'], 'Music Notes & Rhythm')
            && ! str_contains($request['contents'][0]['parts'][0]['text'], 'Math Speed Challenge')
            && ! str_contains($request['contents'][0]['parts'][0]['text'], 'Sorting Challenge')
        );
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
            ->assertSee($this->artTask()['title'])
            ->assertSee($this->musicTask()['title'])
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
        Http::assertSentCount(1);
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
        Http::assertSentCount(1);
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
        Http::assertSentCount(1);
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
        Http::assertSentCount(1);
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

    public function test_dashboard_daily_mission_links_to_the_next_interest_lesson_or_quiz(): void
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

        $this->actingAs($student, 'student')
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Daily Mission')
            ->assertSee('Start lesson: Color and Contrast')
            ->assertSee('Start lesson')
            ->assertSee(route('student.learning-materials.show', $lesson));

        LearningMaterialProgress::create([
            'user_id' => $student->id,
            'learning_material_id' => $lesson->id,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        $this->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Take the quiz: Color and Contrast')
            ->assertSee('Continue to quiz');

        while (ob_get_level() > 1) {
            ob_end_clean();
        }
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
            ->assertSee('Quest Champion');

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
        Http::assertSentCount(1);
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
        Http::assertSentCount(1);
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
            $table->string('game_type')->nullable()->unique();
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
            $table->json('game_data')->nullable();
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
