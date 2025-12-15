<?php

declare(strict_types=1);

namespace App\Traits;

use Flux\Flux;
use App\Models\Song;
use App\Models\Playlist;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Illuminate\Database\Eloquent\Collection;

trait ManagesPlaylists
{
    public Collection $playlists;

    #[On('playlist-saved')]
	public function mountManagesPlaylists(): void
    {
        $this->playlists = auth()
            ->user()
            ->playlists()
            ->orderBy('name')
            ->get();
    }

    #[Renderless]
    public function addToPlaylist(Playlist $playlist, Song $song): void
    {
        if ($playlist->songs()->where('song_id', $song->id)->exists()) {
            Flux::toast(
                variant: 'danger',
                text: 'Song is already in playlist',
            );

            return;
        }

        $next_position = $playlist->songs()->max('position') + 1;

        $playlist->songs()->attach($song, ['position' => $next_position]);
        
        Flux::toast(
            variant: 'success',
            text: 'Added to playlist',
        );
    }
}
