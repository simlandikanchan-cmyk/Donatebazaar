<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if (! $user->isAccountActive()) {
                return redirect()->route('login')->with('error', 'Your account has been deactivated.');
            }

            if ($user->isFundraiserSuspended()) {
                return redirect()->route('login')->with('error', 'Your account has been suspended.');
            }
        }

        return $next($request);
    }
}
