<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Livewire\Playlist;
use App\Models\PlaylistSong;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;
use App\Models\Playlist as ModelsPlaylist;

beforeEach(function () {
    Storage::fake('s3');

    $user = User::factory()->create();

    $playlist = ModelsPlaylist::factory()->for($user)->create();

    $songs = Song::factory(3)->create();

    foreach ($songs as $i => $song) {
        PlaylistSong::factory()->create([
            'playlist_id' => $playlist->id,
            'song_id' => $song->id,
            'position' => $i + 1,
        ]);
    }

    $this->actingAs($user);
});

test('can see songs', function () {
	$playlist = ModelsPlaylist::first();

    livewire(Playlist::class, ['playlist' => $playlist])
        ->assertSee($playlist->songs()->first()->title)
        ->assertHasNoErrors();
});

it('can sort songs in the queue', function () {
    $playlist = ModelsPlaylist::first();

    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    livewire(Playlist::class, ['playlist' => $playlist])
        ->call('handleSort', $song_1->id, 2)
        ->assertHasNoErrors();

    expect(PlaylistSong::find($song_1->id)->position)->toBe(3);
    expect(PlaylistSong::find($song_2->id)->position)->toBe(1);
    expect(PlaylistSong::find($song_3->id)->position)->toBe(2);
});

test('component can render', function () {
    livewire(Playlist::class, ['playlist' => ModelsPlaylist::first()])
        ->assertHasNoErrors();
});
