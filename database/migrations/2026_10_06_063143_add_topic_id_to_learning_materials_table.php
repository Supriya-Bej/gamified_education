<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_materials', function (Blueprint $table) {
            $table->foreignId('topic_id')
                ->nullable()
                ->after('topic')
                ->constrained('topics')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('learning_materials', function (Blueprint $table) {
            $table->dropForeign(['topic_id']);
            $table->dropColumn('topic_id');
        });
    }
};