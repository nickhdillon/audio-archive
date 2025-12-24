<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Traits\ManagesQueue;
use App\Models\PlaylistSong;
use App\Interfaces\PlaysSongs;
use App\Traits\ManagesPlaylist;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Models\Playlist as ModelsPlaylist;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Playlist extends Component implements PlaysSongs
{
    use WithPagination, ManagesQueue, ManagesPlaylist;

    public ModelsPlaylist $playlist;

    public string $search = '';

    public function playSongs(bool $shuffle = false): void
    {
        $this->play(
            songs: $this->playlist->songs()
                ->orderBy('position')
                ->pluck('song_id'),
            source: 'playlist',
            shuffle: $shuffle
        );
    }

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
                ->when(Str::length($this->search) >= 1, function (Builder $query): void {
                    $query->where('title', 'like', "%{$this->search}%");
                })
                ->orderBy('position')
                ->paginate(50)
        ]);
    }
}
