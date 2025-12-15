<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Playlist;
use App\Livewire\PlaylistForm;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(
        User::factory()
            ->hasPlaylists(Playlist::factory())
            ->create()
    );
});

it('can create a playlist', function () {
    livewire(PlaylistForm::class)
        ->set('name', 'Metal')
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('playlists', 2);
});

it('can update a playlist', function () {
    livewire(PlaylistForm::class, ['playlist' => Playlist::first()])
        ->set('name', 'Updated name')
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('playlists', 1);
});

it('can delete a playlist', function () {
    livewire(PlaylistForm::class, ['account' => Playlist::first()])
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('playlists', 1);
});

test('component can render', function () {
    livewire(PlaylistForm::class)
        ->assertHasNoErrors();
});
