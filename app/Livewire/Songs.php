<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class Songs extends Component
{
    public function render(): View
    {
        return view('livewire.songs', [
            'songs' => auth()
                ->user()
                ->songs()
                ->orderBy('title')
                ->paginate(20)
        ]);
    }
}
