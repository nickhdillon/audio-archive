<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Traits\ManagesQueue;
use App\Interfaces\PlaysSongs;
use App\Traits\ManagesPlaylist;
use Illuminate\Contracts\View\View;
use App\Models\Album as ModelsAlbum;
use Illuminate\Database\Eloquent\Builder;

class Album extends Component implements PlaysSongs
{
    use ManagesQueue, ManagesPlaylist;

    public ModelsAlbum $album;

    public string $search = '';

    public function playSongs(bool $shuffle = false): void
    {
        $this->play(
            songs: $this->album->orderedSongs()->pluck('id'),
            source: 'playlist',
            shuffle: $shuffle
        );
    }

    public function render(): View
    {
        $this->album->loadMissing(['artist', 'songs.album:id,name,artwork_url', 'children']);

        $child_albums = $this->album->children()
            ->withCount(['children', 'songs'])
            ->when(Str::length($this->search), function (Builder $query): void {
                $query->where('name', 'like', "%{$this->search}%");
            })
            ->orderBy('order')
            ->get();

        $has_bible_books = $child_albums->contains(fn(ModelsAlbum $child): bool => $child->is_bible_book);

        return view('livewire.album', [
            'breadcrumbs' => $this->album->breadcrumbs(),
            'songs' => $this->album->orderedSongs($this->search),
            'child_albums' => $child_albums,
            'has_bible_books' => $has_bible_books
        ]);
    }
}
