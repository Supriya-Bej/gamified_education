<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestAttempt extends Model
{
    protected $fillable = [
        'user_id', 'task_id', 'score', 'correct_count',
        'total', 'passed', 'xp_awarded', 'answers',
    ];

    protected $casts = [
        'passed' => 'boolean',
        'answers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }

    public function task()
    {
        return $this->belongsTo(LearningTask::class, 'task_id');
    }

    public function certificate()
    {
        return $this->hasOne(Certificate::class, 'quest_attempt_id');
    }
}