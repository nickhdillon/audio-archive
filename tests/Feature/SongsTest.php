<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Livewire\Songs;

use function Pest\Livewire\livewire;

beforeEach(function () {    
    $this->actingAs(
        User::factory()
        ->has(Song::factory(5))
        ->create()
    );
});

test('can see songs', function () {
    livewire(Songs::class)
        ->assertSee(Song::first()->title)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Songs::class)
        ->assertHasNoErrors();
});
