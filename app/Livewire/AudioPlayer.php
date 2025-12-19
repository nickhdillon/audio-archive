<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\SongQueue;
use Livewire\Attributes\On;
use App\Traits\ManagesQueue;
use App\Traits\ManagesPlaylists;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class AudioPlayer extends Component
{
    use ManagesQueue, ManagesPlaylists;

    #[On('start-playlist'), Computed]
    public function queue(): Collection
    {
        $disk = Storage::disk('s3');

        return auth()
            ->user()
            ->queue()
            ->with([
                'song:id,title,album_id,display_artist,playtime,path',
                'song.album:id,name,artwork_url'
            ])
            ->orderBy('position')
            ->oldest()
            ->get()
            ->map(function (SongQueue $item) use ($disk): array {
                return [
                    'id' => $item->id,
                    'song_id' => $item->song->id,
                    'title' => $item->song->title,
                    'artist' => $item->song->display_artist,
                    'path' => $disk->url($item->song->path),
                    'playtime' => $item->song->playtime,
                    'album' => $item->song->album->name,
                    'artwork' => $disk->url($item->song->album->artwork_url)
                ];
            });
    }

    public function handleSort(int $item, int $position): void
    {
        $song = SongQueue::findOrFail($item);

        DB::transaction(function () use ($song, $position): void {
            $current = $song->position;
            $after = $position - 1;

            if ($current === $after) return;

            $song->update(['position' => -1]);

            $block = SongQueue::whereBetween('position', [min($current, $after), max($current, $after)]);

            $need_to_shift_down = $current < $after;

            $need_to_shift_down ? $block->decrement('position') : $block->increment('position');

            $song->update(['position' => $after]);
        });
    }

    #[On('add-to-queue')]
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

    #[On('remove-from-queue')]
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
