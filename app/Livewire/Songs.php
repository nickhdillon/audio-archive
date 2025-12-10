<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Traits\ManagesPlaylists;
use Illuminate\Contracts\View\View;

class Songs extends Component
{
    use WithPagination, ManagesPlaylists;

    public function render(): View
    {
        return view('livewire.songs', [
            'songs' => auth()->user()->songs()
        ]);
    }
}
