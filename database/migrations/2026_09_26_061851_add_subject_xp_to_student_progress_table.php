<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add subject_xp JSON column to student_progress.
     *
     * Stores per-subject XP as a JSON object, e.g.:
     * {
     *   "chess": {"xp": 30, "level": 1, "completed_tasks": 1},
     *   "technology": {"xp": 0, "level": 1, "completed_tasks": 0}
     * }
     */
    public function up(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->json('subject_xp')->nullable()->after('completed_tasks');
        });
    }

    public function down(): void
    {
        Schema::table('student_progress', function (Blueprint $table) {
            $table->dropColumn('subject_xp');
        });
    }
};
