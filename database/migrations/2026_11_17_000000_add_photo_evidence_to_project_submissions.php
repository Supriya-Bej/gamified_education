<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_challenge_submissions', function (Blueprint $table) {
            $table->string('project_url', 2048)->nullable()->change();
            $table->string('evidence_image')->nullable()->after('project_url');
        });
    }

    public function down(): void
    {
        Schema::table('project_challenge_submissions', function (Blueprint $table) {
            $table->dropColumn('evidence_image');
            $table->string('project_url', 2048)->nullable(false)->change();
        });
    }
};
