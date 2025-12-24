<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Database\Eloquent\Builder;

class Artists extends Component
{
    public string $search = '';

    public function render(): View
    {
        return view('livewire.artists', [
            'artists' => auth()
                ->user()
                ->artists()
                ->withCount(['albums', 'songs'])
                ->when(Str::length($this->search) >= 1, function (Builder $query): void {
                    $query->where('name', 'like', "%{$this->search}%");
                })
                ->orderBy('name')
                ->paginate(50)
        ]);
    }
}
