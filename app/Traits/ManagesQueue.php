<?php

declare(strict_types=1);

namespace App\Traits;

use App\Enums\Repeat;
use App\Models\SongQueue;

trait ManagesQueue
{
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
}
