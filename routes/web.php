<?php

use App\Livewire\ServiceIndex;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;



Volt::route('/', 'home')->name('home');
Volt::route('invoice/{slug}', 'invoice')->name('invoice');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Volt::route('dashboard', 'dashboard')->name('dashboard');
    Volt::route('service', 'service-index')->name('service.index');
    Volt::route('service/add', 'service-create')->name('service.create');
    Volt::route('service/{slug}/edit', 'service-create')->name('service.edit');
    Volt::route('transaksi/add', 'transaksi-create')->name('transaksi.create');
    Volt::route('/transaksi', 'transaksi-index')->name('transaksi.index');
});

require __DIR__ . '/auth.php';
