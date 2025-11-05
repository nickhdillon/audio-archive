<?php

use App\Livewire\Album;
use App\Livewire\Songs;
use Livewire\Volt\Volt;
use App\Livewire\Artist;
use App\Livewire\Albums;
use App\Livewire\Artists;
use App\Livewire\UploadAudio;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/artists', Artists::class)->name('artists');
    Route::get('artists/{artist}', Artist::class)->name('artist');

    Route::get('albums', Albums::class)->name('albums');
    Route::get('albums/{album}', Album::class)->name('album');

    Route::get('songs', Songs::class)->name('songs');
    
    Route::get('/upload-audio', UploadAudio::class)->name('upload');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});
