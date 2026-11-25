<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectChallengeSubmission extends Model
{
    protected $fillable = [
        'project_challenge_id',
        'user_id',
        'project_url',
        'evidence_image',
        'reflection',
        'status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(ProjectChallenge::class, 'project_challenge_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, 'reviewed_by');
    }
}
