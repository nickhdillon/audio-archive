<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Song;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Traits\ManagesPlaylists;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Songs extends Component
{
    use WithPagination, ManagesPlaylists;

    public string $search = '';

    public function render(): View
    {
        return view('livewire.songs', [
            'songs' => Song::query()
                ->with([
                    'album:id,artist_id,name,artwork_url',
                    'album.artist:id,name'
                ])
                ->whereRelation('album.artist', 'user_id', auth()->id())
                ->when(Str::length($this->search) >= 1, function (Builder $query): void {
                    $query->where('title', 'like', "%{$this->search}%");
                })
                ->orderBy('title')
                ->paginate(20)
        ]);
    }
}
