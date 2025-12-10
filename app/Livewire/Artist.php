<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Traits\ManagesPlaylists;
use Illuminate\Contracts\View\View;
use App\Models\Artist as ModelsArtist;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Artist extends Component
{
    use ManagesPlaylists;

    public ModelsArtist $artist;

    public function mount(): void
    {
        $this->artist->loadMissing([
            'albums' => function (HasMany $query): HasMany {
                return $query->withCount('songs')->orderBy('name');
            },
            'songs' => function (HasManyThrough $query): HasManyThrough {
                return $query->with('album.artist')->orderBy('title');
            }
        ]);
    }

    public function render(): View
    {
        return view('livewire.artist');
    }
}
