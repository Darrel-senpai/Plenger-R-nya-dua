<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        // composer require laravel/socialite
        // Redirect to Google's OAuth 2.0 server
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            // Handle error (e.g., log it, show an error message, etc.)
            return redirect('/')->with('error', 'Authentication failed. Please try again.');
        }

        // Find or create new user
        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'auth_type' => 'google',  // ← also add this
            ]
        );

        Auth::login($user, true);

        return redirect()->route('welcome');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
