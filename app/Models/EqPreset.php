<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class EqPreset extends Model
{
    /** @use HasFactory<\Database\Factories\EqPresetsFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gains',
        'is_system'
    ];

    protected function casts(): array
    {
        return [
            'gains' => 'array',
            'is_system' => 'boolean'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
