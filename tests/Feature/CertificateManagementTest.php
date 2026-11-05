<?php

namespace Tests\Feature;

use App\Models\Certificate;
use App\Models\LearningTask;
use App\Models\QuestAttempt;
use App\Models\QuestContent;
use App\Models\StudentPreference;
use App\Models\UserRegister;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CertificateManagementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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

        Schema::create('learning_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('difficulty')->default('beginner');
            $table->string('status')->default('pending');
            $table->timestamps();
        });

        Schema::create('quest_attempts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('task_id');
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('correct_count');
            $table->unsignedTinyInteger('total');
            $table->boolean('passed')->default(false);
            $table->unsignedInteger('xp_awarded')->default(0);
            $table->json('answers')->nullable();
            $table->timestamps();
        });

        Schema::create('quest_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id')->unique();
            $table->longText('lesson');
            $table->json('quiz');
            $table->timestamps();
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

    public function test_admin_can_issue_and_revoke_certificate_for_a_perfect_quest(): void
    {
        $admin = $this->user('admin', 'admin@example.test');
        $student = $this->user('student', 'student@example.test');
        $attempt = $this->attempt($student, 100, true);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.certificates.index'))
            ->assertOk()
            ->assertSee('Issue certificate')
            ->assertSee('100%');

        $this->post(route('admin.certificates.issue', $attempt))
            ->assertRedirect(route('admin.certificates.index'))
            ->assertSessionHas('success');

        $certificate = Certificate::where('quest_attempt_id', $attempt->id)->firstOrFail();
        $this->assertSame('issued', $certificate->status);
        $this->assertSame($admin->id, $certificate->issued_by);
        $this->assertNotEmpty($certificate->certificate_number);

        $this->actingAs($student, 'student')
            ->get(route('student.certificates.show', $certificate))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee($attempt->task->title)
            ->assertSee($certificate->certificate_number)
            ->assertSee('Digital signature of '.$admin->name, false);

        $this->actingAs($admin, 'admin')
            ->post(route('admin.certificates.revoke', $certificate))
            ->assertRedirect(route('admin.certificates.index'))
            ->assertSessionHas('success');

        $this->assertSame('revoked', $certificate->fresh()->status);

        $this->actingAs($student, 'student')
            ->get(route('student.certificates.show', $certificate))
            ->assertNotFound();
    }

    public function test_admin_cannot_issue_certificate_for_a_non_perfect_attempt(): void
    {
        $admin = $this->user('admin', 'admin@example.test');
        $student = $this->user('student', 'student@example.test');
        $attempt = $this->attempt($student, 99, true);

        $this->actingAs($admin, 'admin')
            ->from(route('admin.certificates.index'))
            ->post(route('admin.certificates.issue', $attempt))
            ->assertRedirect(route('admin.certificates.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseMissing('certificates', [
            'quest_attempt_id' => $attempt->id,
        ]);
    }

    public function test_interest_course_certificate_requires_admin_approval_before_student_can_view_it(): void
    {
        $initialOutputBufferLevel = ob_get_level();
        $admin = $this->user('admin', 'admin@example.test');
        $student = $this->user('student', 'student@example.test');
        StudentPreference::create([
            'user_id' => $student->id,
            'education_level' => 'Secondary',
            'class_semester' => '10',
            'institution' => 'Test School',
            'experience_level' => 'beginner',
            'interests' => ['Chess'],
            'learning_goal' => 'Learn chess',
            'experience_preference' => 'Interactive',
        ]);
        $finalTask = null;

        foreach (['beginner' => 25, 'intermediate' => 25, 'advanced' => 25] as $difficulty => $count) {
            for ($index = 1; $index <= $count; $index++) {
                $finalTask = LearningTask::create([
                    'user_id' => $student->id,
                    'title' => ucfirst($difficulty).' Chess Quest '.$index,
                    'category' => 'chess',
                    'difficulty' => $difficulty,
                    'status' => 'completed',
                ]);
            }
        }

        $certificate = Certificate::create([
            'user_id' => $student->id,
            'task_id' => $finalTask->id,
            'interest' => 'chess',
            'certificate_number' => 'EQ-COURSE-TEST',
            'title' => 'Chess Mastery Certificate',
            'status' => 'pending_review',
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.certificates.index'))
            ->assertOk()
            ->assertSee('No approved certificates yet')
            ->assertDontSee('Chess Mastery Certificate');
        $this->get(route('student.certificates.show', $certificate))->assertNotFound();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.certificates.index'))
            ->assertOk()
            ->assertSee('Interest Course Certificates')
            ->assertSee('Chess Mastery Certificate')
            ->assertSee('Approve & issue');

        $this->post(route('admin.certificates.approve', $certificate))
            ->assertRedirect(route('admin.certificates.index'))
            ->assertSessionHas('success');

        $this->assertSame('issued', $certificate->fresh()->status);
        $this->assertSame($admin->id, $certificate->fresh()->issued_by);
        $this->assertNotNull($certificate->fresh()->issued_at);

        $this->actingAs($student, 'student')
            ->get(route('student.certificates.show', $certificate))
            ->assertOk()
            ->assertSee($student->name)
            ->assertSee('Chess Mastery Certificate')
            ->assertSee('approved by an EcoQuest administrator');

        while (ob_get_level() > $initialOutputBufferLevel) {
            ob_end_clean();
        }
    }

    public function test_perfect_result_offers_the_certificate_after_admin_issues_it(): void
    {
        $admin = $this->user('admin', 'admin@example.test');
        $student = $this->user('student', 'student@example.test');
        $attempt = $this->attempt($student, 100, true);

        QuestContent::create([
            'task_id' => $attempt->task_id,
            'lesson' => 'A lesson about renewable energy.',
            'quiz' => [[
                'question' => 'Which source is renewable?',
                'options' => ['Coal', 'Solar'],
                'answer' => 1,
                'explanation' => 'Solar energy is renewable.',
            ]],
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.quest.result', $attempt))
            ->assertOk()
            ->assertSee('eligible for an achievement certificate')
            ->assertDontSee('View your certificate');

        $this->actingAs($admin, 'admin')
            ->post(route('admin.certificates.issue', $attempt))
            ->assertRedirect(route('admin.certificates.index'));

        $certificate = Certificate::where('quest_attempt_id', $attempt->id)->firstOrFail();

        $this->actingAs($student, 'student')
            ->get(route('student.quest.result', $attempt))
            ->assertOk()
            ->assertSee(route('student.certificates.show', $certificate));
    }

    public function test_student_can_retake_a_passed_quest_for_a_perfect_score_without_earning_xp_twice(): void
    {
        $student = $this->user('student', 'student@example.test');
        $previousAttempt = $this->attempt($student, 90, true);
        $previousAttempt->task->update(['status' => 'completed']);

        $quiz = [];
        for ($index = 0; $index < 10; $index++) {
            $quiz[] = [
                'question' => 'Question '.$index,
                'options' => ['Correct', 'Incorrect'],
                'answer' => 0,
                'explanation' => 'Correct answer.',
            ];
        }

        QuestContent::create([
            'task_id' => $previousAttempt->task_id,
            'lesson' => 'Quest lesson.',
            'quiz' => $quiz,
        ]);

        $this->actingAs($student, 'student')
            ->get(route('student.quest.quiz', $previousAttempt->task_id))
            ->assertOk()
            ->assertSee('Question 0');

        $response = $this->post(route('student.quest.submit', $previousAttempt->task_id), [
            'answers' => array_fill(0, 10, 0),
        ]);

        $perfectAttempt = QuestAttempt::where('task_id', $previousAttempt->task_id)
            ->where('score', 100)
            ->firstOrFail();

        $response->assertRedirect(route('student.quest.result', $perfectAttempt));
        $this->assertSame(0, $perfectAttempt->xp_awarded);
        $this->assertSame(2, QuestAttempt::where('task_id', $previousAttempt->task_id)->count());

        $this->get(route('student.quest.quiz', $previousAttempt->task_id))
            ->assertRedirect(route('student.dashboard'));
    }

    private function user(string $role, string $email): UserRegister
    {
        return UserRegister::create([
            'name' => ucfirst($role).' User',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => $role,
        ]);
    }

    private function attempt(UserRegister $student, int $score, bool $passed): QuestAttempt
    {
        $task = LearningTask::create([
            'user_id' => $student->id,
            'title' => 'Green Energy Quest',
        ]);

        return QuestAttempt::create([
            'user_id' => $student->id,
            'task_id' => $task->id,
            'score' => $score,
            'correct_count' => $score === 100 ? 10 : 9,
            'total' => 10,
            'passed' => $passed,
            'xp_awarded' => 10,
            'answers' => [],
        ]);
    }
}
