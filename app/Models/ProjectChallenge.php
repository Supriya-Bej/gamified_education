<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProjectChallenge extends Model
{
    protected $fillable = [
        'user_id',
        'interest',
        'title',
        'project_brief',
        'objectives',
        'deliverables',
        'difficulty',
        'xp',
        'review_status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
        'xp_awarded_at',
    ];

    protected function casts(): array
    {
        return [
            'objectives' => 'array',
            'deliverables' => 'array',
            'reviewed_at' => 'datetime',
            'xp_awarded_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, 'reviewed_by');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectChallengeSubmission::class);
    }

    public function latestSubmission(): HasOne
    {
        return $this->hasOne(ProjectChallengeSubmission::class)->latestOfMany();
    }
}
