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
use Livewire\Attributes\Renderless;
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

    #[Renderless]
    public function updateSongOrder(array $list): void
    {
        $this->updateSortablePositions(
            $list,
            fn (array $item) => PlaylistSong::query()
                ->where('playlist_id', $this->playlist->id)
                ->where('song_id', $item['value'])
                ->first()
        );
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
