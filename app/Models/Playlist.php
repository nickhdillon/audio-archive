<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Playlist extends Model
{
    /** @use HasFactory<\Database\Factories\PlaylistFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $playlist): void {
            $playlist->slug = Str::slug($playlist->name);
        });

        static::updating(function (self $playlist): void {
            if ($playlist->isDirty('name')) {
                $playlist->slug = Str::slug($playlist->name);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class)->withPivot('position');
    }
}
