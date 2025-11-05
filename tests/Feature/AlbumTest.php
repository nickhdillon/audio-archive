<?php

declare(strict_types=1);

use App\Models\Song;
use App\Models\User;
use App\Livewire\Album;

use function Pest\Livewire\livewire;

beforeEach(function () {    
    $this->actingAs(
        User::factory()
        ->has(Song::factory(5))
        ->create()
    );
});

test('can see songs', function () {
	$song = Song::first();

    livewire(Album::class, ['album' => $song->album])
        ->assertSee($song->artist)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Album::class, ['album' => Song::first()->album])
        ->assertHasNoErrors();
});
