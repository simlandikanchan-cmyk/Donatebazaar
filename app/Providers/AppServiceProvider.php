<?php

namespace App\Providers;
use App\Gateways\RazorpayGateway;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Repositories\CampaignRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\DonationRepository;
use App\Repositories\SearchRepository;
use App\Repositories\SettlementRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\FundraiserLevelService;
use App\Services\LaravelNotificationService;
use App\Services\NotificationService;
use App\View\Composers\CampaignShowComposer;
use App\View\Composers\UserSidebarComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Facades\Health;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FundraiserLevelService::class);
        $this->app->singleton(NotificationService::class, LaravelNotificationService::class);

        $this->app->singleton(RazorpayGateway::class, function ($app) {
            $keyId = config('services.razorpay.key');
            $keySecret = config('services.razorpay.secret');

            if (! $keyId || ! $keySecret) {
                if (App::environment('production')) {
                    throw new \RuntimeException('Razorpay credentials are not configured. Set RAZORPAY_KEY and RAZORPAY_SECRET in the production environment.');
                }

                Log::warning('Razorpay credentials are not configured — falling back to sandbox placeholders.');

                return new RazorpayGateway(
                    keyId: 'rzp_test_key_placeholder',
                    keySecret: 'rzp_test_secret_placeholder',
                    webhookSecret: '',
                    api: new \Razorpay\Api\Api('rzp_test_key_placeholder', 'rzp_test_secret_placeholder')
                );
            }

            return new RazorpayGateway(
                keyId: $keyId,
                keySecret: $keySecret,
                webhookSecret: (string) config('services.razorpay.webhook_secret', ''),
                api: new \Razorpay\Api\Api($keyId, $keySecret)
            );
        });

        $this->app->singleton(GiftCardService::class);

        $this->app->singleton(DonationRepository::class);
        $this->app->singleton(CampaignRepository::class);
        $this->app->singleton(WalletRepository::class);
        $this->app->singleton(SettlementRepository::class);
        $this->app->singleton(UserRepository::class);
        $this->app->singleton(CategoryRepository::class);
        $this->app->singleton(SearchRepository::class);

        $this->app->singleton(\App\Services\Payment\PaymentOrderService::class);
        $this->app->singleton(\App\Services\Payment\PaymentVerificationService::class);
        $this->app->singleton(\App\Services\Payment\PaymentWebhookService::class);
        $this->app->singleton(\App\Services\Payment\DonationCompletionService::class);
        $this->app->singleton(\App\Services\Payment\RefundService::class);

        $this->app->singleton(\App\Services\Campaign\CampaignWorkflowService::class);
        $this->app->singleton(\App\Services\Campaign\CampaignQueryService::class);
        $this->app->singleton(\App\Services\Blog\AdminBlogService::class);

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

        // Named rate limiters for sensitive endpoints
        RateLimiter::for('webhooks', fn (\Illuminate\Http\Request $request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(120));
        RateLimiter::for('financial', fn (\Illuminate\Http\Request $request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(10));
        RateLimiter::for('gift-card', fn (\Illuminate\Http\Request $request) => \Illuminate\Cache\RateLimiting\Limit::perMinute(10));

        // Eager-load category + donations on every {campaign} route binding
        Route::bind('campaign', function ($value) {
            return Campaign::with(['category', 'donations'])
                ->where(is_numeric($value) ? 'id' : 'slug', $value)
                ->firstOrFail();
        });

        View::composer('campaigns.show', CampaignShowComposer::class);
        View::composer('partials.user-sidebar', UserSidebarComposer::class);

        // Admin dashboard stats cache invalidation
        $forget = fn () => Cache::forget('admin_dashboard_stats');

        Campaign::saved($forget);
        Campaign::deleted($forget);

        Donation::created($forget);
        Donation::deleted($forget);

        User::created($forget);

        Volunteer::saved($forget);
        Volunteer::deleted($forget);

        VolunteerApplication::saved($forget);
        VolunteerApplication::deleted($forget);

        // Health Checks
        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            DebugModeCheck::new(),
        ]);

        if (App::environment('local')) {
            \DB::listen(function ($query) {
                if ($query->time > 100) {
                    \Log::channel('slow_queries')->info('Slow Query', [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time_ms' => $query->time,
                    ]);
                }
            });
        }

        if (App::environment('production') || config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}
