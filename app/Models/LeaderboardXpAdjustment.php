<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaderboardXpAdjustment extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'xp_change',
        'reason',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(UserRegister::class, 'admin_id');
    }
}
