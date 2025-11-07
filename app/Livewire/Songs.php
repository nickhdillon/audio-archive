<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Contracts\View\View;

class Songs extends Component
{
    use WithPagination;

    public function render(): View
    {
        return view('livewire.songs', [
            'songs' => auth()->user()->songs()
        ]);
    }
}
