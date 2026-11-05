<?php

namespace Tests\Feature;

use App\Models\UserRegister;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentProfilePasswordTest extends TestCase
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
    }

    public function test_student_can_change_password_after_verifying_current_password(): void
    {
        $student = $this->student();

        $this->actingAs($student, 'student')
            ->put(route('student.profile.password.update'), [
                'current_password' => 'current-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('student.profile'))
            ->assertSessionHas('success', 'Password updated successfully.');

        $this->assertTrue(Hash::check('new-password-123', $student->fresh()->password));
    }

    public function test_student_cannot_change_password_with_an_incorrect_current_password(): void
    {
        $student = $this->student();
        $originalHash = $student->password;

        $this->actingAs($student, 'student')
            ->from(route('student.profile'))
            ->put(route('student.profile.password.update'), [
                'current_password' => 'incorrect-password',
                'password' => 'new-password-123',
                'password_confirmation' => 'new-password-123',
            ])
            ->assertRedirect(route('student.profile'))
            ->assertSessionHasErrors('current_password');

        $this->assertSame($originalHash, $student->fresh()->password);
    }

    public function test_new_password_must_be_confirmed_and_at_least_eight_characters(): void
    {
        $student = $this->student();
        $originalHash = $student->password;

        $this->actingAs($student, 'student')
            ->from(route('student.profile'))
            ->put(route('student.profile.password.update'), [
                'current_password' => 'current-password',
                'password' => 'short',
                'password_confirmation' => 'different',
            ])
            ->assertRedirect(route('student.profile'))
            ->assertSessionHasErrors(['password']);

        $this->assertSame($originalHash, $student->fresh()->password);
    }

    private function student(): UserRegister
    {
        return UserRegister::create([
            'name' => 'Test Student',
            'email' => 'student@example.test',
            'password' => Hash::make('current-password'),
            'role' => 'student',
        ]);
    }
}
