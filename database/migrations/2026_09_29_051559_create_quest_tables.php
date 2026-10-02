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
        Schema::create('quest_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->unique()->constrained('learning_tasks')->cascadeOnDelete();
            $table->longText('lesson');
            $table->json('quiz');
            $table->timestamps();
        });

        Schema::create('quest_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_registers')->cascadeOnDelete();
            $table->foreignId('task_id')->constrained('learning_tasks')->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('correct_count');
            $table->unsignedTinyInteger('total');
            $table->boolean('passed')->default(false);
            $table->unsignedInteger('xp_awarded')->default(0);
            $table->json('answers')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quest_attempts');
        Schema::dropIfExists('quest_contents');
    }
};
