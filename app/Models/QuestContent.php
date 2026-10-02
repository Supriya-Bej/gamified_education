<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestContent extends Model
{
    protected $fillable = ['task_id', 'lesson', 'quiz'];

    protected $casts = ['quiz' => 'array'];
}