<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Contracts\View\View;

class Artists extends Component
{
    public function render(): View
    {
        return view('livewire.artists', [
            'artists' => auth()
                ->user()
                ->artists()
                ->withCount(['albums', 'songs'])
                ->paginate(50)
        ]);
    }
}
