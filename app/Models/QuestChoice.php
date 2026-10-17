<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestChoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'stage_id',
        'choice_text',
        'next_stage_id',
        'feedback',
        'impact_summary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(QuestStage::class, 'stage_id');
    }

    public function nextStage(): BelongsTo
    {
        return $this->belongsTo(QuestStage::class, 'next_stage_id');
    }
}
