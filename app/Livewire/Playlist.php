<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\PlaylistSong;
use App\Traits\ManagesPlaylists;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Models\Playlist as ModelsPlaylist;

class Playlist extends Component
{
    use WithPagination, ManagesPlaylists;

    public ModelsPlaylist $playlist;

    public function handleSort(int $item, int $position): void
    {
        $playlist_song = PlaylistSong::query()
            ->where('playlist_id', $this->playlist->id)
            ->where('song_id', $item)
            ->firstOrFail();

        DB::transaction(function () use ($playlist_song, $position): void {
            $current = $playlist_song->position;
            $after = $position + 1;

            if ($current === $after) return;

            $playlist_song->update(['position' => -1]);

            $block = PlaylistSong::query()
                ->where('playlist_id', $this->playlist->id)
                ->whereBetween('position', [min($current, $after), max($current, $after)]);

            $need_to_shift_down = $current < $after;

            $need_to_shift_down ? $block->decrement('position') : $block->increment('position');

            $playlist_song->update(['position' => $after]);
        });
    }

    #[On('playlist-saved')]
    public function render(): View
    {
        return view('livewire.playlist', [
            'songs' => $this->playlist
                ->songs()
                ->withPivot('position')
                ->with('album:id,name,artwork_url')
                ->orderBy('position')
                ->paginate(50)
        ]);
    }
}
