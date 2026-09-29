<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningTask extends Model
{
    protected $table = 'learning_tasks';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'difficulty',
        'xp',
        'status',
    ];
}
