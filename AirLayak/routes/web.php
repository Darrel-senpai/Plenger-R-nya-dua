<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use Pest\Support\View;

Route::get('/', function () {
    return redirect('/login');
});

// Google
Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('/login', fn() => View('login'))->name('login');
Route::get('/dashboard', fn() => View('welcome'))->name('welcome');
