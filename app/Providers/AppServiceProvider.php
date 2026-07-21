<?php

namespace App\Providers;

use App\Contracts\Gateway\GatewayInterface;
use App\Gateways\RazorpayGateway;
use App\Models\Campaign;
use App\Services\FundraiserLevelService;
use App\Services\LaravelNotificationService;
use App\Services\NotificationService;
use App\View\Composers\CampaignShowComposer;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Facades\Health;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FundraiserLevelService::class);
        $this->app->singleton(NotificationService::class, LaravelNotificationService::class);
        $this->app->singleton(GatewayInterface::class, function ($app) {
            $keyId = config('services.razorpay.key_id');
            $keySecret = config('services.razorpay.key_secret');
            $webhookSecret = config('services.razorpay.webhook_secret');

            if (! $keyId || ! $keySecret || ! $webhookSecret) {
                if ($app->environment('production')) {
                    throw new \RuntimeException('Razorpay credentials are not configured. Set RAZORPAY_KEY, RAZORPAY_SECRET, and RAZORPAY_WEBHOOK_SECRET in your environment.');
                }

                $keyId = $keyId ?: 'test_key';
                $keySecret = $keySecret ?: 'test_secret';
                $webhookSecret = $webhookSecret ?: 'test_webhook_secret';
            }

            return new RazorpayGateway(
                keyId: $keyId,
                keySecret: $keySecret,
                webhookSecret: $webhookSecret
            );
        });

        if ($this->app->environment('local')) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bootstrap pagination styling
        Paginator::useBootstrapFive();

        // Eager-load category on every {campaign} route binding
        Route::bind('campaign', function ($value) {
            return Campaign::with('category')
                ->where(is_numeric($value) ? 'id' : 'slug', $value)
                ->firstOrFail();
        });

        View::composer('campaigns.show', CampaignShowComposer::class);

        // Health Checks
        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            DebugModeCheck::new(),
        ]);
    }
}
