<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfInstansi
{
    private const INSTANSI_ROLES = ['admin', 'pdam', 'dinkes'];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && in_array($user->role, self::INSTANSI_ROLES, true)) {
            return redirect()->route('admin.dashboard');
        }
        return $next($request);
    }
}