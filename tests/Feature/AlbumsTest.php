<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Livewire\Albums;

use function Pest\Livewire\livewire;

beforeEach(function () {    
    $this->actingAs(
        User::factory()
        ->has(Song::factory(5))
        ->create()
    );
});

test('can see albums', function () {
    livewire(Albums::class)
        ->assertSee(Song::first()->album)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Albums::class)
        ->assertHasNoErrors();
});
