<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_material_progress', function (
            Blueprint $table
        ) {
            $table->json('quiz_data')->nullable();
            $table->unsignedTinyInteger('quiz_score')->nullable();
            $table->timestamp('quiz_completed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('learning_material_progress', function (
            Blueprint $table
        ) {
            $table->dropColumn([
                'quiz_data',
                'quiz_score',
                'quiz_completed_at',
            ]);
        });
    }
};
