<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Greeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role_title',
        'institution',
        'message',
        'photo_path',
        'status',
        'is_featured',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }
}
