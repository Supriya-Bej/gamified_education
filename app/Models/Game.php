<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    protected $table = 'games';

    protected $fillable = [
        'subject_id',
        'name',
        'description',
        'category',
        'game_type',
        'difficulty',
    ];

    public function learningTasks(): HasMany
    {
        return $this->hasMany(LearningTask::class);
    }
}
