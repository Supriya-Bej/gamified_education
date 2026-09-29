<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('user_registers')
                ->onDelete('cascade');

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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_preferences');
    }
};
