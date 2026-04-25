<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureGuestUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle($request, Closure $next)
    {
 
        if (!Auth::check() && session('auth_type') === 'guest') {
            $guest = new \App\Models\User([
                'id' => session('guest_id'),
                'name' => 'Tamu',
            ]); 

            Auth::setUser($guest);
        }

        return $next($request);
    }
}
