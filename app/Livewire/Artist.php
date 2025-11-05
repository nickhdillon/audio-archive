<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class Artist extends Component
{
    public string $artist;

    public Collection $albums;

    public Collection $songs;

    public function mount(): void
    {
        $this->albums = auth()
            ->user()
            ->songs()
            ->select(
                'album',
                DB::raw('COUNT(*) as song_count')
            )
            ->where('artist', $this->artist)
            ->groupBy('album')
            ->orderBy('album')
            ->get();

        $this->songs = auth()
            ->user()
            ->songs()
            ->where('artist', $this->artist)
            ->orderBy('title')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.artist');
    }
}
