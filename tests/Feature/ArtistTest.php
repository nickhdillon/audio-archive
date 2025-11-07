<?php

declare(strict_types=1);

use App\Models\Song;
use App\Models\User;
use App\Models\Album;
use App\Livewire\Artist;
use Illuminate\Support\Str;
use function Pest\Livewire\livewire;
use App\Models\Artist as ModelsArtist;

beforeEach(function () {    
    $this->actingAs(
        User::factory()
            ->has(
                ModelsArtist::factory(3)
                    ->has(Album::factory()
                        ->has(Song::factory(5))
                    )
            )
            ->create()
    );
});

test('can see albums', function () {
	$artist = ModelsArtist::first();

    livewire(Artist::class, ['artist' => $artist])
        ->assertSee(Str::headline($artist->albums()->first()->name))
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Artist::class, ['artist' => ModelsArtist::first()])
        ->assertHasNoErrors();
});
