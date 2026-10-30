<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboard_xp_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('user_registers')
                ->cascadeOnDelete();
            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('user_registers')
                ->nullOnDelete();
            $table->integer('xp_change');
            $table->string('reason', 500);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard_xp_adjustments');
    }
};
