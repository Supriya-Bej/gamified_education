<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('user_registers')->cascadeOnDelete();
            $table->string('category', 30)->default('general'); // general, bug, suggestion, content
            $table->unsignedTinyInteger('rating')->nullable();   // 1-5
            $table->text('message');
            $table->string('status', 10)->default('new');        // new, read
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};