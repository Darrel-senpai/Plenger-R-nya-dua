<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GuestAuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::post('/auth/guest', [GuestAuthController::class, 'login'])->name('auth.guest');

Route::get('/login', fn() => view('login'))->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
});