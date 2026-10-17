<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quest_id',
        'current_stage_id',
        'quest_version',
        'status',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'quest_version' => 'integer',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quest(): BelongsTo
    {
        return $this->belongsTo(Quest::class);
    }

    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(QuestStage::class, 'current_stage_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(QuestResponse::class, 'attempt_id');
    }
}
