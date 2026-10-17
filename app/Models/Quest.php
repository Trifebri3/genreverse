<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'version',
        'difficulty',
        'xp_reward',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'xp_reward' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    public function stages(): HasMany
    {
        return $this->hasMany(QuestStage::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuestAttempt::class);
    }
}
