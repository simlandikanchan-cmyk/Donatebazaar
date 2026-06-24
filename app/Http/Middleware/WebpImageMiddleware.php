<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebpImageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only process HTML responses
        if (!str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();

        // Replace all jpg/jpeg/png image src with webp if file exists
        $content = preg_replace_callback(
            '/\b(src|srcset)=(["\'])([^"\']+\.(jpg|jpeg|png))(["\'])/i',
            function ($matches) {
                $attr    = $matches[1]; // src or srcset
                $quote   = $matches[2]; // " or '
                $url     = $matches[3]; // full URL or path
                $closing = $matches[5]; // closing quote

                $webpUrl = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $url);

                // Convert URL to local path to check if webp exists
                $localPath = $this->urlToPath($webpUrl);

                if ($localPath && file_exists($localPath)) {
                    return "{$attr}={$quote}{$webpUrl}{$closing}";
                }

                return $matches[0]; // return original if webp not found
            },
            $content
        );

        $response->setContent($content);

        return $response;
    }

    private function urlToPath(string $url): ?string
    {
        // Handle /storage/ URLs
        if (str_contains($url, '/storage/')) {
            $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            return storage_path('app/public/' . $path);
        }

        // Handle asset URLs like /images/
        $urlPath = parse_url($url, PHP_URL_PATH);
        if ($urlPath) {
            return public_path(ltrim($urlPath, '/'));
        }

        return null;
    }
}