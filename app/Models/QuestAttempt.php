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
}