<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\CheckAccountStatus;
use App\Http\Middleware\SecureHeadersMiddleware;
use App\Http\Middleware\TrackPageLoad;
use App\Http\Middleware\WebpImageMiddleware;
use App\Providers\RiskServiceProvider;  // ← ADD THIS LINE
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
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'account.active' => CheckAccountStatus::class,
        ]);

        $middleware->web(append: [
            WebpImageMiddleware::class,
            SecureHeadersMiddleware::class,
            TrackPageLoad::class,
        ]);

        // Public endpoints that cannot send a CSRF token:
        // Razorpay webhook and the chatbot AJAX endpoint.
        $middleware->validateCsrfTokens(except: [
            'chatbot',
            'payment/webhook',
        ]);

        // Trusted reverse proxies allowed to set forwarded headers
        // (X-Forwarded-For / X-Forwarded-Proto). Configure per deployment
        // via the TRUSTED_PROXIES environment variable (set it as a real
        // server env var when using `php artisan config:cache`):
        //   - unset / empty : trust NO proxies (secure default — direct HTTPS)
        //   - "*"           : ONLY when exclusively behind a trusted proxy
        //                     (e.g. Cloudflare, an AWS/load balancer)
        //   - IPs/CIDRs     : e.g. "203.0.113.10,198.51.100.0/24"
        $trustedProxies = env('TRUSTED_PROXIES');

        if ($trustedProxies !== null && $trustedProxies !== '*') {
            $trustedProxies = array_map('trim', explode(',', (string) $trustedProxies));
        }

        $middleware->trustProxies(at: $trustedProxies);
    })
    ->withProviders([
        RiskServiceProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\App\Exceptions\InsufficientWalletBalanceException $e, $request) {
            if ($request->expectsJson()) {
                return new \Illuminate\Http\JsonResponse([
                    'message' => $e->getMessage(),
                ], 422);
            }

            return new \Illuminate\Http\RedirectResponse(
                back()->with('error', $e->getMessage())
            );
        });

        $exceptions->render(function (\App\Exceptions\InvalidSettlementTransitionException $e, $request) {
            if ($request->expectsJson()) {
                return new \Illuminate\Http\JsonResponse([
                    'message' => $e->getMessage(),
                ], 409);
            }

            return new \Illuminate\Http\RedirectResponse(
                back()->with('error', $e->getMessage())
            );
        });

        $exceptions->render(function (\App\Exceptions\DuplicateRequestException $e, $request) {
            if ($request->expectsJson()) {
                return new \Illuminate\Http\JsonResponse([
                    'message' => $e->getMessage(),
                ], 429);
            }

            return new \Illuminate\Http\RedirectResponse(
                back()->with('error', $e->getMessage())
            );
        });

        $exceptions->render(function (\App\Exceptions\InvalidSignatureException $e, $request) {
            \Illuminate\Support\Facades\Log::warning('Invalid webhook signature', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            return new \Illuminate\Http\JsonResponse([
                'error' => 'Invalid signature',
            ], 401);
        });

        $exceptions->report(function (\App\Exceptions\InsufficientWalletBalanceException $e) {
            \Illuminate\Support\Facades\Log::warning('Wallet debit rejected', [
                'message' => $e->getMessage(),
            ]);
        });

        $exceptions->report(function (\Spatie\WebhookClient\Exceptions\WebhookFailed $e) {
            \Illuminate\Support\Facades\Log::critical('Webhook processing failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })
    ->create();