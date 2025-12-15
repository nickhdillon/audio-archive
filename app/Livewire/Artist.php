<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Traits\ManagesPlaylists;
use Illuminate\Contracts\View\View;
use App\Models\Artist as ModelsArtist;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Artist extends Component
{
    use ManagesPlaylists;

    public ModelsArtist $artist;
    
    public string $search = '';

    public string $tab = 'albums';

    public function render(): View
    {
        return view('livewire.artist', [
            'albums' => $this->artist->albums()
                ->withCount('songs')
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
