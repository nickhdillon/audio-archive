<?php

declare(strict_types=1);

use App\Models\Song;
use App\Models\User;
use App\Models\Album;
use App\Models\Artist;
use App\Livewire\Albums;
use Illuminate\Support\Str;
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

test('can see albums', function () {
    livewire(Albums::class)
        ->assertSee(Str::headline(Album::first()->name))
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(Albums::class)
        ->assertHasNoErrors();
});
