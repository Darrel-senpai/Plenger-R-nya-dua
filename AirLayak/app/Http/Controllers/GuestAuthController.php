<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GuestAuthController extends Controller
{
    public function login()
    {
        $guest = User::firstOrCreate(
            ['email' => 'dummy@gmail.com'],
            [
                'name'      => 'Tamu',
                'auth_type' => 'guest',
            ]
        );

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        Auth::login($guest, false);

        return redirect()->route('homepage')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }
}