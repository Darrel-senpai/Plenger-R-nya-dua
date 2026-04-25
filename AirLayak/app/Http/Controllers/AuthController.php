<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'nullable|in:warga,pdam,dinkes',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // === DEBUG: Tambahkan log ini sementara ===
        \Log::info('Login successful', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'auth_type' => $user->auth_type,
            'is_instansi' => $user->isInstansi(),
            'is_active' => $user->is_active ?? 'N/A',
        ]);
        // ===========================================

        if (isset($user->is_active) && !$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
        }

        $selectedRole = $request->input('role', 'warga');

        if (in_array($selectedRole, ['pdam', 'dinkes'], true)) {
            if (!$user->isInstansi()) {
                \Log::warning('User is not instansi', ['user_id' => $user->id]);
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Akun Anda bukan akun instansi.']);
            }
            
            if (!$user->isAdmin() && $user->role !== $selectedRole) {
                \Log::warning('Role mismatch', [
                    'user_role' => $user->role,
                    'selected_role' => $selectedRole,
                ]);
                Auth::logout();
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => "Akun Anda terdaftar sebagai {$user->role}, bukan {$selectedRole}."]);
            }
        }

        \Log::info('Redirecting to dashboard', [
            'is_instansi' => $user->isInstansi(),
            'redirect_to' => $user->isInstansi() ? 'admin.dashboard' : 'dashboard',
        ]);

        if ($user->isInstansi()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}