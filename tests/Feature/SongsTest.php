<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Song;
use App\Models\Album;
use App\Models\Artist;
use App\Livewire\Songs;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(
        User::factory()
            ->has(
                Artist::factory()
                    ->has(Album::factory()
                        ->has(Song::factory(5))
                    )
                )
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
