<?php

use Livewire\Volt\Volt;
use App\Livewire\Artists;
use App\Livewire\UploadAudio;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Artists::class)->name('artists');
    Route::get('/upload-audio', UploadAudio::class)->name('upload');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});
