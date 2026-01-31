<?php

declare(strict_types=1);

use App\Models\User;
use App\Livewire\UploadAudio;
use Illuminate\Http\UploadedFile;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    Storage::fake('s3');

    $this->actingAs(User::factory()->create());
});

it('can upload and submit an mp3 file for audio bible', function () {
    $path = $this->post(route('filepond.upload'), [
        'file' => UploadedFile::fake()->image('1 Chronicles Chapter 1.mp3')
    ])
    ->getContent();

    livewire(UploadAudio::class)
        ->set('files', [$path])
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
    $path = $this->post(route('filepond.upload'), [
        'file' => UploadedFile::fake()->image('test1.mp3')
    ])
    ->getContent();

    livewire(UploadAudio::class)
        ->set('files', [$path])
        ->set('metadata', [[
            'artist' => 'Unknown Artist',
            'album' => 'Unknown Album',
            'sub_albums' => [],
            'title' => 'Test 1',
            'filename' => 'test1.mp3',
            'track_number' => 1,
            'playtime' => '3:00',
            'display_artist' => 'Unknown Artist',
        ]])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists('users-test/1/files/unknown-artist/unknown-album/test1.mp3'));
});

it('can upload and submit an m4a file', function () {
    $path = $this->post(route('filepond.upload'), [
        'file' => UploadedFile::fake()->image('test2.m4a')
    ])
    ->getContent();

    livewire(UploadAudio::class)
        ->set('files', [$path])
        ->set('metadata', [[
            'artist' => 'Unknown Artist',
            'album' => 'Unknown Album',
            'sub_albums' => [],
            'title' => 'Test 2',
            'filename' => 'test2.m4a',
            'track_number' => 1,
            'playtime' => '2:00',
            'display_artist' => 'Unknown Artist',
        ]])
        ->call('submit')
        ->assertHasNoErrors();

    $this->assertTrue(Storage::disk('s3')->exists('users-test/1/files/unknown-artist/unknown-album/test2.m4a'));
});

it('can delete an uploaded file', function () {
    $file = UploadedFile::fake()->image('test3.mp3');

    $content = $this->post(route('filepond.upload'), ['file' => $file])->getContent();

    $this->delete(route('filepond.revert'), ['path' => $content])->assertNoContent();
});

test('component can render', function () {
    livewire(UploadAudio::class)
        ->assertHasNoErrors();
});
