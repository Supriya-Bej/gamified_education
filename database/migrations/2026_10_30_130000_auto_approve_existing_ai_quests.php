<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('learning_tasks')
            ->where('status', 'pending')
            ->where('review_status', 'pending_review')
            ->update([
                'review_status' => 'approved',
                'review_note' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]);
    }

    public function down(): void
    {
        // Auto-approved quests must remain playable if this migration is rolled back.
    }
};
