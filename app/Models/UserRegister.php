<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentPreference;
use App\Models\StudentBadge;

use Illuminate\Foundation\Auth\User as Authenticatable;

class UserRegister extends Authenticatable
{
    protected $table = 'user_registers';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
    ];

    public function preferences()
    {
        return $this->hasOne(StudentPreference::class, 'user_id');
    }

    public function progress()
    {
        return $this->hasOne(StudentProgress::class, 'user_id');
    }

    public function taskCompletions()
    {
        return $this->hasMany(
            TaskCompletion::class,
            'user_id'
        );
    }

    public function studentBadges()
    {
        return $this->hasMany(StudentBadge::class, 'user_id');
    }
}
