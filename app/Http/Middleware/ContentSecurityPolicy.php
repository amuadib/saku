<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $csp = "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com; " .
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
            "img-src 'self' data: https://*; " . // Mengizinkan gambar dari URL manapun (penting jika ada avatar luar)
            "font-src 'self' https://fonts.gstatic.com; " .
            "connect-src 'self'; " .
            "frame-ancestors 'none'; " . // Mencegah Clickjacking
            "object-src 'none'; " .
            "base-uri 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // 3. X-Content-Type-Options
        // Mencegah MIME-sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 4. Referrer-Policy
        // Hanya mengirim informasi referrer jika ke sesama HTTPS
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 5. Permissions-Policy
        // Membatasi akses API browser (Kamera, Mic, Geolocation) jika tidak dibutuhkan
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), interest-cohort=()');
        return $response;
    }
}
