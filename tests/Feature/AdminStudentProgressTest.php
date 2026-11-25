<?php

namespace Tests\Feature;

use App\Models\StudentProgress;
use App\Models\UserRegister;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminStudentProgressTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('user_registers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('student');
            $table->timestamps();
        });

        Schema::create('student_progress', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('total_xp')->default(0);
            $table->integer('level')->default(1);
            $table->integer('completed_tasks')->default(0);
            $table->json('subject_xp')->nullable();
            $table->timestamps();
        });
    }

    public function test_admin_can_view_each_students_progress_read_only(): void
    {
        $admin = UserRegister::create([
            'name' => 'Admin User',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'admin',
        ]);
        $student = UserRegister::create([
            'name' => 'Progress Student',
            'email' => 'student@example.test',
            'password' => 'password',
            'role' => 'student',
        ]);
        StudentProgress::create([
            'user_id' => $student->id,
            'total_xp' => 240,
            'level' => 3,
            'completed_tasks' => 8,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.student-progress.index'))
            ->assertOk()
            ->assertSee('Progress Student')
            ->assertSee('Level 3')
            ->assertSee('240 XP')
            ->assertHeader('Cache-Control', 'must-revalidate, no-cache, no-store, private')
            ->assertSee('View details')
            ->assertSee(route('admin.students.show', $student), false)
            ->assertDontSee('Edit progress');
    }
}
