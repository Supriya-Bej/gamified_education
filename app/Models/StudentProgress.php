<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';

    protected $fillable = [
        'user_id',
        'total_xp',
        'level',
        'completed_tasks',
        'subject_xp',
    ];

    protected $casts = [
        'subject_xp' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }
}
