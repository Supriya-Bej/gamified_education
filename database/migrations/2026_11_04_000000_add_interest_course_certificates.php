<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('interest')->nullable();
            $table->foreignId('quest_attempt_id')->nullable()->change();
            $table->unique(['user_id', 'interest']);
        });
    }

    public function down(): void
    {
        if (DB::table('certificates')->whereNull('quest_attempt_id')->exists()) {
            throw new RuntimeException(
                'Course certificates exist and cannot be rolled back without deleting them.'
            );
        }

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'interest']);
            $table->dropColumn('interest');
            $table->foreignId('quest_attempt_id')->nullable(false)->change();
        });
    }
};
