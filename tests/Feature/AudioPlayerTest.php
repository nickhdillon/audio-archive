<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Enums\Repeat;
use App\Models\Album;
use App\Models\Artist;
use App\Models\SongQueue;
use App\Livewire\AudioPlayer;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(
        User::factory()
            ->has(
                Artist::factory(3)
                    ->has(Album::factory()
                        ->has(Song::factory(5))
                    )
            )
            ->create()
    );
});

it('can sort songs in the queue', function () {
    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    livewire(AudioPlayer::class)
        ->call('addToQueue', $song_1->id)
        ->call('addToQueue', $song_2->id)
        ->call('addToQueue', $song_3->id)
        ->assertCount('queue', 3)
        ->call('handleSort', $song_1->id, 3)
        ->assertHasNoErrors();

    expect(SongQueue::find($song_1->id)->position)->toBe(2);
    expect(SongQueue::find($song_2->id)->position)->toBe(1);
    expect(SongQueue::find($song_3->id)->position)->toBe(3);
});

it('can add a song to the queue', function () {
    livewire(AudioPlayer::class)
        ->call('addToQueue', Song::first()->id)
        ->assertCount('queue', 1)
        ->assertHasNoErrors();
});

test('adding an existing song to the queue updates the position', function () {
    $song_id = Song::first()->id;

    livewire(AudioPlayer::class)
        ->call('addToQueue', $song_id)
        ->call('addToQueue', $song_id)
        ->assertCount('queue', 1)
        ->assertHasNoErrors();

    expect(SongQueue::first()->position)->toBe(2);
});

it('can remove a song from the queue', function () {
    livewire(AudioPlayer::class)
        ->call('addToQueue', Song::first()->id)
        ->assertCount('queue', 1)
        ->call('removeFromQueue', Song::first()->id)
        ->assertCount('queue', 0)
        ->assertHasNoErrors();
});

it('can shuffle the queue', function () {
    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    $component = livewire(AudioPlayer::class)
        ->call('addToQueue', $song_1->id)
        ->call('addToQueue', $song_2->id)
        ->call('addToQueue', $song_3->id)
        ->assertCount('queue', 3)
        ->assertHasNoErrors();

    expect(SongQueue::find($song_1->id)->position)->toBe(1);
    expect(SongQueue::find($song_2->id)->position)->toBe(2);
    expect(SongQueue::find($song_3->id)->position)->toBe(3);

    $component->call('shuffle', $song_1->id);

    expect(SongQueue::find($song_1->id)->position)->toBe(1);
});

it('did not shuffle the queue when shuffle is off', function () {
    auth()->user()->update(['shuffle' => true]);
    
    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    $component = livewire(AudioPlayer::class)
        ->call('addToQueue', $song_1->id)
        ->call('addToQueue', $song_2->id)
        ->call('addToQueue', $song_3->id)
        ->assertCount('queue', 3)
        ->assertHasNoErrors();

    expect(SongQueue::find($song_1->id)->position)->toBe(1);
    expect(SongQueue::find($song_2->id)->position)->toBe(2);
    expect(SongQueue::find($song_3->id)->position)->toBe(3);

    $component->call('shuffle', $song_1->id);

    expect(SongQueue::find($song_1->id)->position)->toBe(1);
    expect(SongQueue::find($song_2->id)->position)->toBe(2);
    expect(SongQueue::find($song_3->id)->position)->toBe(3);
});

it('can repeat the queue', function () {
    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    livewire(AudioPlayer::class)
        ->call('addToQueue', $song_1->id)
        ->call('addToQueue', $song_2->id)
        ->call('addToQueue', $song_3->id)
        ->assertCount('queue', 3)
        ->call('repeat')
        ->assertHasNoErrors();

    expect(auth()->user()->repeat)->toBe(Repeat::ALL);
});

it('can repeat one song in the queue', function () {
    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    livewire(AudioPlayer::class)
        ->call('addToQueue', $song_1->id)
        ->call('addToQueue', $song_2->id)
        ->call('addToQueue', $song_3->id)
        ->assertCount('queue', 3)
        ->call('repeat')
        ->call('repeat')
        ->assertHasNoErrors();

    expect(auth()->user()->repeat)->toBe(Repeat::ONE);
});

it('can turn repeat off', function () {
    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    livewire(AudioPlayer::class)
        ->call('addToQueue', $song_1->id)
        ->call('addToQueue', $song_2->id)
        ->call('addToQueue', $song_3->id)
        ->assertCount('queue', 3)
        ->call('repeat')
        ->call('repeat')
        ->call('repeat')
        ->assertHasNoErrors();

    expect(auth()->user()->repeat)->toBe(Repeat::OFF);
});

test('component can render', function () {
    livewire(AudioPlayer::class)
        ->assertHasNoErrors();
});
