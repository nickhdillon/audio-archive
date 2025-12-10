<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Traits\ManagesPlaylists;
use Illuminate\Contracts\View\View;
use App\Models\Album as ModelsAlbum;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Album extends Component
{
    use ManagesPlaylists;

    public ModelsAlbum $album;
    
    public function mount(): void
    {
        $this->album->loadMissing([
            'artist',
            'songs' => function (HasMany $query): HasMany {
                return $query->orderBy('track_number');
            }
        ]);
    }

    public function render(): View
    {
        return view('livewire.album');
    }
}
