<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class Albums extends Component
{
    public function render(): View
    {
        return view('livewire.albums', [
            'albums' => auth()
                ->user()
                ->albums()
                ->with(['artist', 'songs'])
                ->get()
        ]);
    }
}
