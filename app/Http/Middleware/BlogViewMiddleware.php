<?php

// ─────────────────────────────────────────────────────────────────────────────
// FILE: app/Http/Middleware/BlogViewMiddleware.php
//
// Applied automatically on public blog show route to track views
// without polluting the controller.
// ─────────────────────────────────────────────────────────────────────────────
 
namespace App\Http\Middleware;
 
use App\Models\Blog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class BlogViewMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
 
        // Only on successful GET requests
        if ($request->isMethod('GET') && $response->getStatusCode() === 200) {
            $blog = $request->route('blog');
 
            if ($blog instanceof Blog && $blog->is_publicly_visible) {
                $blog->recordView(
                    auth()->id(),
                    $request->ip()
                );
            }
        }
 
        return $response;
    }
}
 