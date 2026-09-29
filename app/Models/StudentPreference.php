<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPreference extends Model
{
    //
    protected $table = 'student_preferences';
    protected $fillable = [
        'user_id',
        'education_level',
        'class_semester',
        'institution',
        'experience_level',
        'interests',
        'learning_goal',
        'experience_preference',
        'ai_profile',
    ];
    // It automatically json_decode() / json_encode() the $caste
    protected $casts = [
        'interests' => 'array',
        'ai_profile' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }
}
