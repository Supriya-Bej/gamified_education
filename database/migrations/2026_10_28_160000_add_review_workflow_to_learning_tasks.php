<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_tasks', function (Blueprint $table) {
            $table->string('review_status', 20)->default('approved')->index();
            $table->text('review_note')->nullable();
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('user_registers')
                ->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('learning_tasks', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropIndex(['review_status']);
            $table->dropColumn(['review_status', 'review_note', 'reviewed_by', 'reviewed_at']);
        });
    }
};
