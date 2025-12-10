<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;

class Playlists extends Component
{
    #[On('playlist-saved')]
    public function render(): View
    {
        return view('livewire.playlists', [
            'playlists' => auth()
                ->user()
                ->playlists()
                ->withCount('songs')
                ->with('songs.album:id,artwork_url')
                ->orderBy('name')
                ->get()
        ]);
    }
}
