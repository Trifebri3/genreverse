<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'lesson_id',
        'title',
        'passing_score',
        'attempt_limit',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'passing_score' => 'integer',
            'attempt_limit' => 'integer',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(LearningModule::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('sort_order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
