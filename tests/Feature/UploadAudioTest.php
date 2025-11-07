<?php

declare(strict_types=1);

use App\Models\User;
use App\Livewire\UploadAudio;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Storage::fake('s3');
    
    $this->actingAs(User::factory()->create());
});

it('can upload and submit an mp3 file', function () {
    $file = UploadedFile::fake()->image('test1.mp3');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists("users/1/files/unknown-artist/unknown-album/{$file->getClientOriginalName()}"));
});

it('can upload and submit an m4a file', function () {
    $file = UploadedFile::fake()->image('test2.m4a');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists("users/1/files/unknown-artist/unknown-album/{$file->getClientOriginalName()}"));
});

it('can validate an uploaded file', function () {
    livewire(UploadAudio::class)
        ->set('files', [UploadedFile::fake()->image('test3.mp3')])
        ->call('validateUploadedFile')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(UploadAudio::class)
        ->assertHasNoErrors();
});
