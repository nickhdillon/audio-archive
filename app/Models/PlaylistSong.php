<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistSong extends Model
{
    /** @use HasFactory<\Database\Factories\PlaylistSongFactory> */
    use HasFactory;

    protected $table = 'playlist_song';

    protected $fillable = [
        'playlist_id',
        'song_id',
        'position',
    ];
}
