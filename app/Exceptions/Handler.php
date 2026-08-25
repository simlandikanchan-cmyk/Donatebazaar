<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Throwable;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof InsufficientWalletBalanceException) {
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($e instanceof InvalidSettlementTransitionException) {
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], Response::HTTP_CONFLICT);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($e instanceof DuplicateRequestException) {
            if ($request->expectsJson()) {
                return new JsonResponse([
                    'message' => $e->getMessage(),
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            return back()->with('error', $e->getMessage());
        }

        if ($e instanceof InvalidSignatureException) {
            \Log::warning('Invalid webhook signature', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            return response()->json(['error' => 'Invalid signature'], Response::HTTP_UNAUTHORIZED);
        }

        return parent::render($request, $e);
    }

    public function report(Throwable $e)
    {
        if ($e instanceof \Spatie\WebhookClient\Exceptions\WebhookFailed) {
            \Log::critical('Webhook processing failed', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return;
        }

        if ($e instanceof InsufficientWalletBalanceException) {
            \Log::warning('Wallet debit rejected', [
                'message' => $e->getMessage(),
            ]);

            return;
        }

        parent::report($e);
    }
}
