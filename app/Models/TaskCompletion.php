<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    protected $table = 'task_completions';

    protected $fillable = [
        'user_id',
        'task_id',
        'answer',
        'xp_earned',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }
}
