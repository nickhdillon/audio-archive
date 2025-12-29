<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Models\SongQueue;
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

it('can see songs', function () {
	$playlist = ModelsPlaylist::first();

    livewire(Playlist::class, ['playlist' => $playlist])
        ->assertSee($playlist->songs()->first()->title)
        ->assertHasNoErrors();
});

it('can search songs', function () {
	$playlist = ModelsPlaylist::first();

    $song_title = $playlist->songs()->first()->title;

    livewire(Playlist::class, ['playlist' => $playlist])
        ->set('search', $song_title)
        ->assertSeeText($song_title)
        ->assertHasNoErrors();
});

it('can play a playlist', function () {
    $playlist = ModelsPlaylist::first();
    
    foreach ($playlist->songs as $song) {
        SongQueue::create([
            'user_id' => 1,
            'song_id' => $song->id
        ]);
    }

    expect(SongQueue::count())->toBe(3);

    $playlist_2 = ModelsPlaylist::factory()->for(User::first())->create();

    $songs = Song::factory(4)->create();

    foreach ($songs as $i => $song) {
        PlaylistSong::factory()->create([
            'playlist_id' => $playlist_2->id,
            'song_id' => $song->id,
            'position' => $i + 1,
        ]);
    }

    livewire(Playlist::class, ['playlist' => $playlist_2])
        ->call('playSongs', shuffle: false)
        ->assertDispatched('replace-queue')
        ->assertHasNoErrors();

    expect(SongQueue::count())->toBe(4);
});

it('can sort songs in the queue', function () {
    $playlist = ModelsPlaylist::first();

    $song_1 = Song::first();
    $song_2 = Song::find(2);
    $song_3 = Song::find(3);

    $list = [
        [
            'order' => 3,
            'value' => 1,
        ],
        [
            'order' => 1,
            'value' => 2,
        ],
        [
            'order' => 2,
            'value' => 3,
        ],
    ];

    livewire(Playlist::class, ['playlist' => $playlist])
        ->call('updateSongOrder', $list)
        ->assertHasNoErrors();

    expect(PlaylistSong::find($song_1->id)->position)->toBe(3);
    expect(PlaylistSong::find($song_2->id)->position)->toBe(1);
    expect(PlaylistSong::find($song_3->id)->position)->toBe(2);
});

test('component can render', function () {
    livewire(Playlist::class, ['playlist' => ModelsPlaylist::first()])
        ->assertHasNoErrors();
});
