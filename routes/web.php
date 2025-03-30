<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    Route::view('venta', 'venta')
    ->middleware(['auth', 'verified'])
    ->name('venta');
    Route::view('cliente', 'cliente')
    ->middleware(['auth', 'verified'])
    ->name('cliente');
    Route::view('producto', 'producto')
    ->middleware(['auth', 'verified'])
    ->name('producto');
    Route::view('categoria', 'categoria')
    ->middleware(['auth', 'verified'])
    ->name('categoria');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
