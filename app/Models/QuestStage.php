<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'quest_id',
        'stage_key',
        'title',
        'story_content',
        'is_ending',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_ending' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }

    public function choices(): HasMany
    {
        return $this->hasMany(QuestChoice::class, 'stage_id')->orderBy('sort_order');
    }
}
