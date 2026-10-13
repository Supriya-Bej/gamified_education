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
        Schema::create('game_engines', function (Blueprint $table) {
            $table->id();
            $table->string('engine_key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('view_name')->nullable();
            $table->json('supported_interests')->nullable();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_engines');
    }
};
