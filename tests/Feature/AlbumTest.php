<?php

declare(strict_types=1);

use App\Models\Song;
use App\Models\User;
use App\Models\Artist;
use App\Livewire\Album;
use App\Models\Album as ModelsAlbum;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(
        User::factory()
            ->has(
                Artist::factory()
                    ->has(ModelsAlbum::factory()
                        ->has(Song::factory(5))
                    )
                )
            ->create()
    );
});

it('can see songs', function () {
	$album = ModelsAlbum::first();

    livewire(Album::class, ['album' => $album])
        ->assertSee($album->songs()->first()->title)
        ->assertHasNoErrors();
});

it('can play album songs', function () {
    livewire(Album::class, ['album' => ModelsAlbum::first()])
        ->call('playSongs')
        ->assertHasNoErrors();
});

it('can search songs', function () {
    $album = ModelsAlbum::first();

    livewire(Album::class, ['album' => ModelsAlbum::first()])
        ->set('search', $album->name)
        ->assertSeeText($album->name)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Album::class, ['album' => ModelsAlbum::first()])
        ->assertHasNoErrors();
});
