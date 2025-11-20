<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\SongQueue;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Storage;

class AudioPlayer extends Component
{
    #[Computed]
    public function queue(): Collection
    {
        $disk = Storage::disk('s3');

        return auth()
            ->user()
            ->queue()
            ->with([
                'song:id,title,album_id,playtime,path',
                'song.album:id,name,artist_id',
                'song.album.artist:id,name',
            ])
            ->orderBy('position')
            ->oldest()
            ->get()
            ->map(function (SongQueue $item) use ($disk): array {
                return [
                    'id' => $item->id,
                    'title' => $item->song->title,
                    'artist' => $item->song->album->artist->name,
                    'path' => $disk->url($item->song->path),
                    'playtime' => $item->song->playtime,
                ];
            });
    }

    #[On('add-to-queue'), Renderless]
    public function addToQueue(int $song_id): void
    {
        $user_id = auth()->id();

        $existing = SongQueue::query()
            ->where('user_id', $user_id)
            ->where('song_id', $song_id)
            ->first();

        $max_position = SongQueue::where('user_id', $user_id)->max('position') ?? 0;

        if ($existing) {
            $existing->update(['position' => $max_position + 1]);
        } else {
            SongQueue::create([
                'user_id' => $user_id,
                'song_id' => $song_id,
                'position' => $max_position + 1,
            ]);
        }

        $this->dispatch('queue-updated', queue: $this->queue());

        Flux::toast(
            variant: 'success',
            text: 'Added to queue',
        );
    }

    #[Renderless]
    public function removeFromQueue(int $id): void
    {
        $user_id = auth()->id();

        SongQueue::query()
            ->where('id', $id)
            ->where('user_id', $user_id)
            ->delete();

        $this->dispatch('queue-updated', queue: $this->queue());

        Flux::toast(
            variant: 'success',
            text: 'Removed from queue',
        );
    }

    public function render(): View
    {
        return view('livewire.audio-player');
    }
}
