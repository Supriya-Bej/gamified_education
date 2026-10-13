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
        Schema::table('learning_tasks', function (Blueprint $table) {
            $table->json('game_data')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('learning_tasks', function (Blueprint $table) {
            $table->dropColumn('game_data');
        });
    }
};
