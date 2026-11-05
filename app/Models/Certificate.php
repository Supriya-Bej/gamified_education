<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'interest',
        'quest_attempt_id',
        'issued_by',
        'certificate_number',
        'title',
        'status',
        'issued_at',
        'revoked_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }

    public function task()
    {
        return $this->belongsTo(LearningTask::class, 'task_id');
    }

    public function attempt()
    {
        return $this->belongsTo(QuestAttempt::class, 'quest_attempt_id');
    }

    public function issuer()
    {
        return $this->belongsTo(UserRegister::class, 'issued_by');
    }
}
