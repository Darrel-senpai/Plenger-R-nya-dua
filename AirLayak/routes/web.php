<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GuestAuthController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\FormReportController;
use App\Http\Controllers\warnPDAM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/auth/google',          [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::get('/auth/guest', [GuestAuthController::class, 'login'])->name('auth.guest');

Route::get('/login', fn() => view('login'))->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/homepage', [HomepageController::class, 'homepage'])->name('homepage');
    Route::get('/logout', [GoogleAuthController::class, 'logout'])->name('auth.logout');
});

Route::get('/homepage', [HomepageController::class, 'homepage'])->name('homepage');
Route::get('/lapor', [FormReportController::class, 'create'])->name('reports.create');
Route::post('/lapor', [FormReportController::class, 'store'])->name('reports.store');

Route::get('/email', [warnPDAM::class, 'warnTechnician'])->name('email');