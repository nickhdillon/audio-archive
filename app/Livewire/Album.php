<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Traits\ManagesQueue;
use App\Traits\ManagesPlaylist;
use App\Interfaces\PlaysSongs;
use Illuminate\Contracts\View\View;
use App\Models\Album as ModelsAlbum;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Album extends Component implements PlaysSongs
{
    use ManagesQueue, ManagesPlaylist;

    public ModelsAlbum $album;

    public string $search = '';

    public function playSongs(bool $shuffle = false): void
    {
        $this->play(
            songs: $this->album->songs()
                ->orderBy('track_number')
                ->pluck('id'),
            source: 'playlist',
            shuffle: $shuffle
        );
    }

    public function render(): View
    {
        $this->album->loadMissing(['artist', 'songs']);

        return view('livewire.album', [
            'songs' => $this->album
                ->songs()
                ->with('album:id,name,artwork_url')
                ->when(Str::length($this->search) >= 1, function (Builder $query): void {
                    $query->where('title', 'like', "%{$this->search}%");
                })
                ->orderBy('track_number')
                ->get()
        ]);
    }
}
