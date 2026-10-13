<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameEngine extends Model
{
    protected $fillable = [
        'engine_key',
        'name',
        'description',
        'view_name',
        'supported_interests',
        'is_enabled',
    ];

    protected function casts(): array
    {
        return [
            'supported_interests' => 'array',
            'is_enabled' => 'boolean',
        ];
    }
}
