<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningMaterial extends Model
{
    protected $table = 'learning_materials';

    protected $fillable = [
        'title',
        'topic',
        'topic_id',
        'description',
        'content',
        'difficulty',
        'image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function topicRelation()
    {
        return $this->belongsTo(
            Topic::class,
            'topic_id'
        );
    }

    public function progress()
    {
        return $this->hasMany(
            LearningMaterialProgress::class,
            'learning_material_id'
        );
    }
}