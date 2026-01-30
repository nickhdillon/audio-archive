<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Traits\ManagesQueue;
use App\Interfaces\PlaysSongs;
use App\Traits\ManagesPlaylist;
use Illuminate\Contracts\View\View;
use App\Models\Artist as ModelsArtist;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Artist extends Component implements PlaysSongs
{
    use ManagesQueue, ManagesPlaylist;

    public ModelsArtist $artist;

    public string $search = '';

    public string $tab = 'albums';

    public function playSongs(bool $shuffle = false): void
    {
        $this->play(
            songs: $this->artist->songs()
                ->orderBy('title')
                ->pluck('songs.id'),
            source: 'playlist',
            shuffle: $shuffle
        );
    }

    public function render(): View
    {
        return view('livewire.artist', [
            'has_nested_albums' => $this->artist->albums()
                ->whereHas('children')
                ->exists(),
            'albums' => $this->artist->albums()
                ->withCount(['children', 'songs'])
                ->whereNull('parent_id')
                ->when($this->tab === 'albums', function (Builder $query): void {
                    $query->where('name', 'like', "%{$this->search}%");
                })
                ->orderBy('name')
                ->get(),
            'songs' => $this->artist->songs()
                ->with('album.artist')
                ->when($this->tab === 'songs', function (Builder $query): void {
                    $query->where('title', 'like', "%{$this->search}%");
                })
                ->orderBy('title')
                ->get()
        ]);
    }
}
