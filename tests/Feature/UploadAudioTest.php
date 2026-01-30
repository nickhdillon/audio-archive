<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Str;
use App\Livewire\UploadAudio;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    Storage::fake('s3');

    $this->actingAs(User::factory()->create());
});

it('can upload and submit an mp3 file for audio bible', function () {
    $file = UploadedFile::fake()->image('1 Chronicles Chapter 1.mp3');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->set('metadata', [[
            'artist' => 'Thomas Nelson',
            'album' => 'NKJV Word Of Promise',
            'sub_albums' => [
                ['name' => 'Old Testament', 'slug' => 'old-testament', 'order' => 1],
                ['name' => '1 Chronicles', 'slug' => '1-chronicles', 'order' => 1],
            ],
            'title' => 'Chapter 1',
            'filename' => 'chapter-1.mp3',
            'track_number' => 1,
            'playtime' => '3:00',
            'display_artist' => 'Thomas Nelson',
        ]])
        ->call('submit')
        ->assertHasNoErrors();

    $filename = 'old-testament/1-chronicles/chapter-1';

    $this->assertTrue(Storage::disk('s3')->exists("users-test/1/files/thomas-nelson/nkjv-word-of-promise/{$filename}.mp3"));
});

it('can upload and submit an mp3 file', function () {
    $file = UploadedFile::fake()->image('test1.mp3');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists("users-test/1/files/unknown-artist/unknown-album/{$file->getClientOriginalName()}"));
});

it('can upload and submit an m4a file', function () {
    $file = UploadedFile::fake()->image('test2.m4a');

    livewire(UploadAudio::class)
        ->set('files', [$file])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists("users-test/1/files/unknown-artist/unknown-album/{$file->getClientOriginalName()}"));
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
