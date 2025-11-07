<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Artist;
use App\Livewire\Artists;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(
        User::factory()
            ->has(Artist::factory(3))
            ->create()
    );
});

test('can see artists', function () {
    livewire(Artists::class)
        ->assertSee(Artist::first()->name)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Artists::class)
        ->assertHasNoErrors();
});
