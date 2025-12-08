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
        'album_id',
        'title',
        'slug',
        'display_artist',
        'filename',
        'playtime',
        'track_number',
        'path'
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }
}
