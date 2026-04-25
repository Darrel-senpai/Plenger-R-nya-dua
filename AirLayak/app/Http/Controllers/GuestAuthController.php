<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GuestAuthController extends Controller
{
    public function login()
    {
        $guest = User::create([
            'name' => 'Tamu ' . rand(1000, 9999),
            'auth_type' => 'guest',
            'email' => null,
            'google_id' => null,
        ]);

        Auth::login($guest);

        return redirect()->route('welcome');
    }
}