<?php

declare(strict_types=1);

use App\Models\Song;
use App\Models\User;
use App\Livewire\Artist;

use function Pest\Livewire\livewire;

beforeEach(function () {    
    $this->actingAs(
        User::factory()
        ->has(Song::factory(5))
        ->create()
    );
});

test('can see albums', function () {
	$song = Song::first();

    livewire(Artist::class, ['artist' => $song->artist])
        ->assertSee($song->album)
        ->assertHasNoErrors();
});

test('can see songs', function () {
	$song = Song::first();

    livewire(Artist::class, ['artist' => $song->artist])
        ->assertSee($song->title)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Artist::class, ['artist' => Song::first()->artist])
        ->assertHasNoErrors();
});
