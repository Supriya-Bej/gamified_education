<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_registers')->cascadeOnDelete();
            $table->string('interest', 100);
            $table->string('title');
            $table->text('project_brief');
            $table->json('objectives');
            $table->json('deliverables');
            $table->string('difficulty', 20);
            $table->unsignedSmallInteger('xp');
            $table->string('review_status', 20)->default('pending_review')->index();
            $table->text('review_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('user_registers')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('xp_awarded_at')->nullable();
            $table->timestamps();
        });

        Schema::create('project_challenge_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_challenge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('user_registers')->cascadeOnDelete();
            $table->string('project_url', 2048);
            $table->text('reflection');
            $table->string('status', 30)->default('submitted')->index();
            $table->text('review_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('user_registers')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(
                ['project_challenge_id', 'created_at'],
                'project_challenge_submissions_challenge_created_idx'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_challenge_submissions');
        Schema::dropIfExists('project_challenges');
    }
};
