<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');

        $vite = '';
        $hotFile = public_path('hot');
        if (! app()->environment('production') && is_file($hotFile)) {
            $origin = trim((string) file_get_contents($hotFile));
            if ($origin !== '') {
                $vite = rtrim($origin, '/').' ';
            }
        }

        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        $csp = "default-src 'self'; "
            ."script-src 'self' 'nonce-{$nonce}' 'unsafe-inline' "
            .$vite
            .'https://checkout.razorpay.com '
            .'https://cdn.jsdelivr.net '
            .'https://unpkg.com '
            .'https://www.googletagmanager.com; '
            ."style-src 'self' 'unsafe-inline' "
            .$vite
            .'https://fonts.googleapis.com '
            .'https://cdnjs.cloudflare.com; '
            ."img-src 'self' data: https: "
            .$vite
            .'; '
            ."font-src 'self' "
            .$vite
            .'https://fonts.gstatic.com '
            .'https://cdnjs.cloudflare.com; '
            ."connect-src 'self' ".$vite
            .'; '
            ."frame-src 'self' "
            .'https://www.google.com '
            .'https://accounts.google.com '
            .'https://api.razorpay.com '
            .'https://checkout.razorpay.com; '
            ."object-src 'none'; "
            ."base-uri 'self'; "
            ."form-action 'self'";

        $response->headers->set('Content-Security-Policy', $csp);

        if (app()->environment('production') || config('app.force_https')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
