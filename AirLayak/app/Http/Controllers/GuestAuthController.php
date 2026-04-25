<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GuestAuthController extends Controller
{
    public function login()
{
    $guest = User::create([
        'name'      => 'Tamu ' . rand(1000, 9999),
        'auth_type' => 'guest',
    ]);

    Auth::login($guest, false);
    session()->save();

    return redirect()->route('dashboard');
}
}