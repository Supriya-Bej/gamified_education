<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_challenges', function (Blueprint $table) {
            $table->string('ai_verification_status', 20)
                ->default('passed')
                ->after('review_status');
        });
    }

    public function down(): void
    {
        Schema::table('project_challenges', function (Blueprint $table) {
            $table->dropColumn('ai_verification_status');
        });
    }
};
