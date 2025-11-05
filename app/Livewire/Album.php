<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class Album extends Component
{
    public string $album;

    public function render(): View
    {
        return view('livewire.album', [
            'songs' => auth()
                ->user()
                ->songs()
                ->where('album', $this->album)
                ->orderBy('title')
                ->get()
        ]);
    }
}
