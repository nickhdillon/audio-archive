<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Models\Album;
use App\Models\Artist;
use App\Livewire\Songs;
use App\Models\Playlist;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(
        User::factory()
            ->has(
                Artist::factory()
                    ->has(Album::factory()
                        ->has(Song::factory(5))
                    )
                )
            ->hasPlaylists(Playlist::factory())
            ->create()
    );
});

it('can see songs', function () {
    livewire(Songs::class)
        ->assertSeeText(Song::first()->title)
        ->assertHasNoErrors();
});

it('can search songs', function () {
    $song_title = Song::first()->title;

    livewire(Songs::class)
        ->set('search', $song_title)
        ->assertSeeText($song_title)
        ->assertHasNoErrors();
});

it('can add song to playlist', function () {
    livewire(Songs::class)
        ->call('addToPlaylist', Playlist::first()->id, Song::factory()->create()->id)
        ->assertHasNoErrors();

    $this->assertDatabaseCount('playlist_song', 1);
});

it('cannot add duplicate song to playlist', function () {
    $song_id = Song::factory()->create()->id;

    livewire(Songs::class)
        ->call('addToPlaylist', Playlist::first()->id, $song_id)
        ->call('addToPlaylist', Playlist::first()->id, $song_id)
        ->assertHasNoErrors();

    $this->assertDatabaseCount('playlist_song', 1);
});

test('component can render', function () {
    livewire(Songs::class)
        ->assertHasNoErrors();
});
