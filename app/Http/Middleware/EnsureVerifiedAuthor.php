<?php

namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
 
class EnsureVerifiedAuthor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
 
        if (!$user || (!$user->is_verified_author && $user->role !== 'admin')) {
            abort(403, 'Verified author status required.');
        }
 
        return $next($request);
    }
}