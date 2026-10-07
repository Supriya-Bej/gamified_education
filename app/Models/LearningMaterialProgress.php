<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class LearningMaterialProgress extends Model
{
    protected $table = 'learning_material_progress';

    protected $fillable = [
        'user_id',
        'learning_material_id',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(
            UserRegister::class,
            'user_id'
        );
    }

    public function learningMaterial()
    {
        return $this->belongsTo(
            LearningMaterial::class,
            'learning_material_id'
        );
    }
}