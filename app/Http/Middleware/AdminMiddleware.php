<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Allow only authenticated admin users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Not logged in → redirect
        if (!$user) {
            return redirect()->route('login');
        }

        // Not admin → block
        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admins only.');
        }

        return $next($request);
    }
}