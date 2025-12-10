<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use Livewire\Component;
use App\Models\SongQueue;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\PlaylistSong;
use App\Traits\ManagesPlaylists;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Playlist as ModelsPlaylist;

class Playlist extends Component
{
    use WithPagination, ManagesPlaylists;

    public ModelsPlaylist $playlist;

    public function play(): void
    {
        $user = auth()->user();

        SongQueue::where('user_id', $user->id)->delete();

        $songs = $this->playlist->songs()
            ->orderBy('position')
            ->pluck('song_id');

        $queue_data = $songs->map(fn(int $song_id, int $index): array => [
            'user_id' => $user->id,
            'song_id' => $song_id,
            'position' => $index,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        SongQueue::insert($queue_data);

        $disk = Storage::disk('s3');

        $artwork_url_prefix = config('filesystems.disks.s3.url');

        $queue = $user->queue()
            ->with(['song', 'song.album'])
            ->orderBy('position')
            ->oldest()
            ->get()
            ->map(function (SongQueue $item) use ($disk, $artwork_url_prefix): array {
                return [
                    'id' => $item->id,
                    'title' => $item->song->title,
                    'artist' => $item->song->display_artist,
                    'path' => $disk->url($item->song->path),
                    'playtime' => $item->song->playtime,
                    'artwork' => "{$artwork_url_prefix}{$item->song->album->artwork_url}",
                ];
            });

        $this->dispatch('start-playlist', queue: $queue);

        Flux::toast(
            variant: 'success',
            text: 'Playlist added to queue',
        );
    }

    public function handleSort(int $item, int $position): void
    {
        $playlist_song = PlaylistSong::query()
            ->where('playlist_id', $this->playlist->id)
            ->where('song_id', $item)
            ->firstOrFail();

        DB::transaction(function () use ($playlist_song, $position): void {
            $current = $playlist_song->position;
            $after = $position + 1;

            if ($current === $after) return;

            $playlist_song->update(['position' => -1]);

            $block = PlaylistSong::query()
                ->where('playlist_id', $this->playlist->id)
                ->whereBetween('position', [min($current, $after), max($current, $after)]);

            $need_to_shift_down = $current < $after;

            $need_to_shift_down ? $block->decrement('position') : $block->increment('position');

            $playlist_song->update(['position' => $after]);
        });
    }

    #[On('playlist-saved')]
    public function render(): View
    {
        return view('livewire.playlist', [
            'songs' => $this->playlist
                ->songs()
                ->withPivot('position')
                ->with('album:id,name,artwork_url')
                ->orderBy('position')
                ->paginate(50)
        ]);
    }
}
