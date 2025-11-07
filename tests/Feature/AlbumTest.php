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

test('can see songs', function () {
	$album = ModelsAlbum::first();

    livewire(Album::class, ['album' => $album])
        ->assertSee($album->songs()->first()->title)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Album::class, ['album' => ModelsAlbum::first()])
        ->assertHasNoErrors();
});
