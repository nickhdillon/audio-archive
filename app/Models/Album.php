<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /** @use HasFactory<\Database\Factories\AlbumFactory> */
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'parent_id',
        'name',
        'slug',
        'artwork_url',
        'order'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(Album::class, 'parent_id')->orderBy('order');
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class)->orderBy('track_number');
    }
}
