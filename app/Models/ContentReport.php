<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'content_type',
        'content_id',
        'reason',
        'description',
        'status',
    ];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(ModerationAction::class, 'report_id');
    }
}
