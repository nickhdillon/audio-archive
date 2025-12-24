<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Albums extends Component
{
    public string $search = '';

    public function render(): View
    {
        return view('livewire.albums', [
            'albums' => auth()
                ->user()
                ->albums()
                ->with(['artist', 'songs'])
                ->when(Str::length($this->search) >= 1, function (Builder $query): void {
                    $query->where('albums.name', 'like', "%{$this->search}%");
                })
                ->orderBy('name')
                ->get()
        ]);
    }
}
