<?php

declare(strict_types=1);

namespace App\Traits;

use Closure;
use Flux\Flux;
use App\Enums\Repeat;
use App\Models\SongQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

trait ManagesQueue
{
	public function play(Collection $songs, string $source, bool $shuffle = false): void
    {
        $user = auth()->user();

        SongQueue::where('user_id', $user->id)->delete();

        if ($shuffle) $songs = $songs->shuffle();

        $queue_data = $songs->map(fn(int $song_id, int $index): array => [
            'user_id' => $user->id,
            'song_id' => $song_id,
            'position' => $index,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        SongQueue::insert($queue_data);

        $disk = Storage::disk('s3');

        $queue = $user->queue()
            ->with(['song', 'song.album'])
            ->orderBy('position')
            ->oldest()
            ->get()
            ->map(function (SongQueue $item) use ($disk): array {
                return [
                    'id' => $item->id,
                    'title' => $item->song->title,
                    'artist' => $item->song->display_artist,
                    'path' => $disk->url($item->song->path),
                    'playtime' => $item->song->playtime,
                    'artwork' => $disk->url($item->song->album->artwork_url),
                ];
            });

        $this->dispatch('replace-queue', queue: $queue);

        Flux::toast(
            variant: 'success',
            text: $shuffle ? "Shuffling {$source}" : Str::title($source) . ' added to queue',
        );
    }

	public function shuffle(int $current_song_id): void
	{
		$user = auth()->user();

		if ($user->shuffle) {
			$user->update(['shuffle' => false]);

			return;
		}

		$current_song = SongQueue::query()
			->where('user_id', $user->id)
			->where('song_id', $current_song_id)
			->first();

		$current_position = $current_song->position;

		$songs_to_shuffle = SongQueue::query()
			->where('user_id', $user->id)
			->where('position', '>', $current_position)
			->inRandomOrder()
			->get();

		$next_position = $current_position + 1;

		$songs_to_shuffle->each(function (SongQueue $song) use (&$next_position): void {
			$song->update(['position' => $next_position++]);
		});

		$user->update(['shuffle' => true]);
	}

	public function repeat(): void
	{
		$user = auth()->user();

		$next = match ($user->repeat) {
			Repeat::OFF => Repeat::ALL,
			Repeat::ALL => Repeat::ONE,
			Repeat::ONE => Repeat::OFF
		};

		$user->update(['repeat' => $next]);

		$this->dispatch('repeat-changed', value: $next);
	}

	/**
     * @param array<int, array{order: int, value: string}> $list
     * @param Closure $resolve_model
     */
	protected function updateSortablePositions(array $list, Closure $resolve_model): void
	{
		collect($list)->each(function (array $item) use ($resolve_model): void {
			$model = $resolve_model($item);
	
			if (! $model) return;
	
			$model->update(['position' => $item['order']]);
		});
	}
}
