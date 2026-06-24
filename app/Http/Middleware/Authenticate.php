<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * This method is called automatically by Laravel when an unauthenticated
     * user tries to access a route protected by the 'auth' middleware.
     *
     * - For API / AJAX requests (expecting JSON): return null → triggers a
     *   401 Unauthenticated JSON response automatically.
     * - For normal browser requests: redirect to the login page.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}