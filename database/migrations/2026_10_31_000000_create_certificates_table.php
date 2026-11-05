<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_registers')->cascadeOnDelete();
            $table->foreignId('task_id')->constrained('learning_tasks')->cascadeOnDelete();
            $table->foreignId('quest_attempt_id')->unique()->constrained('quest_attempts')->cascadeOnDelete();
            $table->foreignId('issued_by')->nullable()->constrained('user_registers')->nullOnDelete();
            $table->string('certificate_number')->unique();
            $table->string('title')->default('Certificate of Achievement');
            $table->string('status')->default('issued');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
