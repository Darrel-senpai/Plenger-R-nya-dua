<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GuestAuthController extends Controller
{
    public function login()
    {

        $guest = User::where('email', 'dummy@gmail.com')
            ->first();

        if(!$guest) {
            $guest = User::create([
                'name'      => 'Tamu',
                'auth_type' => 'guest',
                'email'     => 'dummy@gmail.com'
            ]);
        }

        Auth::login($guest, false);
    
        return redirect()->route('homepage');
    }
}