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
        // Jika sudah login, arahkan sesuai rolenya
        if (Auth::check()) {
            if (Auth::user()->isInstansi()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('dashboard'); // Atau 'homepage'
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            // Handle error (e.g., log it, show an error message, etc.)
            return redirect('/login')->with('error', 'Autentikasi gagal. Silakan coba lagi.');
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            // Buat user baru dengan default role 'warga'
            $user = User::create([
                'google_id' => $googleUser->getId(),
                'name'      => $googleUser->getName(),
                'email'     => $googleUser->getEmail(),
                'auth_type' => 'google',
                'role'      => 'warga', // PASTIKAN ADA DEFAULT ROLE
            ]);
        } else {
            // Jika user sudah ada (misal sebelumnya daftar manual via email) 
            // tapi baru pertama kali klik Google Login, update google_id-nya
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'auth_type' => 'google'
                ]);
            }
        }

        Auth::login($user, true);

        // PENGECEKAN REDIRECT SESUAI ROLE
        if ($user->isInstansi()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard'); // Arahkan warga ke dashboard warga
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}