<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = ['user_id', 'category', 'rating', 'message', 'status'];

    public function student()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }
}