<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $allowedOrigins = config('cors.allowed_origins');

        $origin = $request->header('Origin');

        // if (in_array($origin, $allowedOrigins)) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        header('Access-Control-Allow-Credentials: true');
        // }

        // Menambahkan header Content-Security-Policy (CSP)
        header('Content-Security-Policy: default-src \'self\'; script-src \'self\';');

        // Menghapus header X-Powered-By
        header_remove('X-Powered-By');

        return $next($request);
    }
}
