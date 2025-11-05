<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Livewire\Artists;

use function Pest\Livewire\livewire;

beforeEach(function () {    
    $this->actingAs(
        User::factory()
        ->has(Song::factory(5))
        ->create()
    );
});

test('can see artists', function () {
    livewire(Artists::class)
        ->assertSee(Song::first()->artist)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Artists::class)
        ->assertHasNoErrors();
});
