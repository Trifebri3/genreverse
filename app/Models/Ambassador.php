<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ambassador extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'region_id',
        'organization_name',
        'verification_status',
        'verified_by',
        'verified_at',
        'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'valid_until' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
