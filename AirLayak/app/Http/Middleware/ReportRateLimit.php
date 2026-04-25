<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ReportRateLimit
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $key = "report_ip_{$ip}";
        $maxPerDay = 2;

        $count = Cache::get($key, 0);

        if ($count >= $maxPerDay) {
            return response()->json([
                'success' => false,
                'message' => 'Kamu sudah mengirim laporan hari ini. Coba lagi besok.'
            ], 429);
        }

        Cache::put($key, $count + 1, now()->endOfDay());

        return $next($request);
    }
}