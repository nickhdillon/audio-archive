<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    /** @use HasFactory<\Database\Factories\SongFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filename',
        'title',
        'album',
        'track_number',
        'playtime',
        'artist',
        'genre',
        'path'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
