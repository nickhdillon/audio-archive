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

it('can see artists', function () {
    livewire(Artists::class)
        ->assertSeeText(Artist::first()->name)
        ->assertHasNoErrors();
});

it('can search artists', function () {
    $artist_name = Artist::first()->name;

    livewire(Artists::class)
        ->set('search', $artist_name)
        ->assertSeeText($artist_name)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Artists::class)
        ->assertHasNoErrors();
});
