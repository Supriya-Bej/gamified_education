<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningTask extends Model
{
    protected $table = 'learning_tasks';

    protected $fillable = [
        'user_id',
        'game_id',
        'game_type',
        'title',
        'description',
        'category',
        'difficulty',
        'xp',
        'status',
        'review_status',
        'review_note',
        'reviewed_by',
        'reviewed_at',
        'game_data',
    ];

    // public function game()
    // {
    //     return $this->belongsTo(Game::class);
    // }
    public function user()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(UserRegister::class, 'reviewed_by');
    }

    protected function casts(): array
    {
        return [
            'game_data' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }
}
