<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Vite::useCspNonce();

        $nonce = Vite::cspNonce();
        view()->share('cspNonce', $nonce);

        $response = $next($request);

        // Prevent clickjacking attacks
        $response->header('X-Frame-Options', 'SAMEORIGIN');

        // Prevent browsers from inferring MIME types
        $response->header('X-Content-Type-Options', 'nosniff');

        // Enable browser XSS protection
        $response->header('X-XSS-Protection', '1; mode=block');

        // Control referrer information
        $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        $csp = "default-src 'self'; "
            ."script-src 'self' 'nonce-{$nonce}' cdn.tailwindcss.com"
            .(app()->environment('local') ? ' http://127.0.0.1:5173' : '')
            .'; '
            ."style-src 'self' 'nonce-{$nonce}' cdn.tailwindcss.com"
            .(app()->environment('local') ? ' http://127.0.0.1:5173' : '')
            .'; '
            ."img-src 'self' data: https:; "
            ."font-src 'self' data: fonts.bunny.net; "
            ."connect-src 'self'"
            .(app()->environment('local') ? ' http://127.0.0.1:5173 ws://127.0.0.1:5173' : '')
            .'; '
            ."frame-ancestors 'self';";

        $response->header('Content-Security-Policy', $csp);

        // HSTS (HTTP Strict Transport Security)
        if (app()->environment('production')) {
            $response->header(
                'Strict-Transport-Security',
                'max-age=63072000; includeSubDomains; preload'
            );
        }

        // Permissions Policy (formerly Feature Policy)
        $response->header(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), magnetometer=(), gyroscope=(), accelerometer=()'
        );

        return $response;
    }
}
