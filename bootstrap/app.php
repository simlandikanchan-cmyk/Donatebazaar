<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAccountStatus;
use App\Http\Middleware\WebpImageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ── Register the 'admin' middleware alias ──
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'account.active' => CheckAccountStatus::class,
        ]);

        // ── Auto-serve WebP images globally ──
        $middleware->web(append: [
            WebpImageMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
