<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Playlist;
use App\Livewire\Playlists;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(
        User::factory()
            ->has(Playlist::factory(3))
            ->create()
    );
});

test('can see playlists', function () {
    livewire(Playlists::class)
        ->assertSee(Playlist::first()->name)
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Playlists::class)
        ->assertHasNoErrors();
});
