<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\Playlist;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\View\View;

class PlaylistForm extends Component
{
    public ?Playlist $playlist = null;

    #[Validate(['required', 'string'])]
    public string $name = '';

    public function mount(): void
    {
        if ($this->playlist) {
            $this->name = $this->playlist->name;
        }
    }

    public function submit(): void
    {
        $validated_data = $this->validate();

        if ($this->playlist) {
            Playlist::query()
                ->where('id', $this->playlist->id)
                ->update($validated_data);
        } else {
            auth()->user()->playlists()->create($validated_data);
        }

        $this->dispatch('playlist-saved');

        if (! $this->playlist) $this->reset();

        Flux::toast(
            variant: 'success',
            text: 'Playlist successfully ' . ($this->playlist ? 'updated' : 'created'),
        );

        Flux::modals()->close();
    }

    public function delete(): void
    {
        $this->playlist?->delete();

        Flux::toast(
            variant: 'success',
            text: 'Playlist successfully deleted',
        );

        $this->redirectRoute('playlists', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.playlist-form');
    }
}
