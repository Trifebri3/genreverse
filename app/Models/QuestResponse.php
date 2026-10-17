<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestResponse extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'attempt_id',
        'stage_id',
        'choice_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuestAttempt::class, 'attempt_id');
    }

    public function stage(): BelongsTo
    {
        return $this->belongsTo(QuestStage::class, 'stage_id');
    }

    public function choice(): BelongsTo
    {
        return $this->belongsTo(QuestChoice::class, 'choice_id');
    }
}
