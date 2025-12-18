<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Playlists extends Component
{
    public string $search = '';

    #[On('playlist-saved')]
    public function render(): View
    {
        return view('livewire.playlists', [
            'playlists' => auth()
                ->user()
                ->playlists()
                ->withCount('songs')
                ->with('songs.album:id,artwork_url')
                ->when(Str::length($this->search) >= 1, function (Builder $query): void {
                    $query->where('name', 'like', "%{$this->search}%");
                })
                ->orderBy('name')
                ->get()
        ]);
    }
}
