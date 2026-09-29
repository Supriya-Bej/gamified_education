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
        Schema::create('task_completions', function (Blueprint $table) {
            $table->id();
           $table->foreignId('user_id')
                ->constrained('user_registers')
                ->onDelete('cascade');

            $table->foreignId('task_id')
                ->constrained('tasks')
                ->onDelete('cascade');

            $table->text('answer');

            $table->integer('xp_earned');

            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Same student cannot complete the same task twice
            $table->unique(['user_id', 'task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_completions');
    }
};
