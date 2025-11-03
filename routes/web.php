<?php

use Livewire\Volt\Volt;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');

    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
});
