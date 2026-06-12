<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
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
        $response->header(
            'Content-Security-Policy',
            "default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' cdn.tailwindcss.com; "
            . "style-src 'self' 'unsafe-inline' cdn.tailwindcss.com; "
            . "img-src 'self' data: https:; "
            . "font-src 'self' data:; "
            . "connect-src 'self'; "
            . "frame-ancestors 'self';"
        );

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
