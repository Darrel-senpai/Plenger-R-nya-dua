<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsInstansi
{
    private const ALLOWED_ROLES = ['admin', 'pdam', 'dinkes'];

    public function handle(Request $request, Closure $next, ?string $requiredRole = null): Response
    {
        $user = $request->user();

        // === DEBUG LOGGING ===
        Log::info('EnsureUserIsInstansi middleware', [
            'url' => $request->fullUrl(),
            'user_present' => $user !== null,
            'user_id' => $user?->id,
            'role' => $user?->role,
            'session_id' => session()->getId(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ]);
        // =====================

        if (!$user) {
            Log::warning('Middleware: No user, redirecting to login');
            return $request->expectsJson()
                ? response()->json(['message' => 'Authentication required.'], 401)
                : redirect()->route('login');
        }

        if (isset($user->is_active) && !$user->is_active) {
            Log::warning('Middleware: User inactive');
            Auth::logout();;
            return redirect()->route('login')
                ->withErrors(['email' => 'Akun Anda tidak aktif.']);
        }

        if (!in_array($user->role, self::ALLOWED_ROLES, true)) {
            Log::warning('Middleware: Role not allowed', ['role' => $user->role]);
            return $request->expectsJson()
                ? response()->json(['message' => 'Forbidden.'], 403)
                : redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses panel instansi.');
        }

        if ($requiredRole && $user->role !== $requiredRole) {
            Log::warning('Middleware: Role mismatch', [
                'user_role' => $user->role,
                'required_role' => $requiredRole,
            ]);
            return $request->expectsJson()
                ? response()->json(['message' => 'Forbidden.'], 403)
                : redirect()->route('admin.dashboard')
                    ->with('error', 'Akses terbatas.');
        }

        Log::info('Middleware: User passed, proceeding');
        return $next($request);
    }
}