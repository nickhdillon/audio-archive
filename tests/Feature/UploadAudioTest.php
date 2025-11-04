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

it('can remove file', function () {
    $file = UploadedFile::fake()->image('test1.mp3');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('removeFile', 0)
        ->assertHasNoErrors();
});

it('can upload and submit an mp3 file', function () {
    $file = UploadedFile::fake()->image('test1.mp3');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists("users/1/files/Unknown Artist/Unknown Album/{$file->getClientOriginalName()}"));
});

it('can upload and submit an m4a file', function () {
    $file = UploadedFile::fake()->image('test2.m4a');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists("users/1/files/Unknown Artist/Unknown Album/{$file->getClientOriginalName()}"));
});

test('component can render', function () {
    livewire(UploadAudio::class)
        ->assertHasNoErrors();
});
