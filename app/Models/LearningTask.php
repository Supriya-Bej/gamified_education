<?php

namespace App\Models;

use App\Models\Game;

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
}
