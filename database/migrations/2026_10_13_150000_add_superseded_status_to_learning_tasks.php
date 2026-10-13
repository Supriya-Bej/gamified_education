<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learning_tasks', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'superseded'])
                ->default('pending')
                ->change();
        });
    }

    public function down(): void
    {
        DB::table('learning_tasks')
            ->where('status', 'superseded')
            ->update(['status' => 'pending']);

        Schema::table('learning_tasks', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed'])
                ->default('pending')
                ->change();
        });
    }
};
