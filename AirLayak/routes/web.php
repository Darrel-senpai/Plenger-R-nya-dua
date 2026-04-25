<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GuestAuthController;
use Illuminate\Support\Facades\Route;
use Pest\Support\View;

Route::get('/', function () {
    return redirect('/login');
});

// Google
Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Guest
Route::get('/auth/guest',           [GuestAuthController::class, 'login'])->name('auth.guest');

Route::get('/login', fn() => View('login'))->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('welcome'))->name('welcome');
    Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
});