<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Badge extends Model
{
    protected $table = 'badges';

    protected $fillable = [
        'name',
        'description',
        'icon',
        'requirement_type',
        'requirement_value',
        'is_active',
    ];

    protected $casts = [
        'requirement_value' => 'integer',
        'is_active' => 'boolean',
    ];

    public function studentBadges(): HasMany
    {
        return $this->hasMany(StudentBadge::class, 'badge_id');
    }
}
