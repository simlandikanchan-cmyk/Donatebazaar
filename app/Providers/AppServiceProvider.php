<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerApplication;
use App\Services\FundraiserLevelService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FundraiserLevelService::class);
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

        // ───────────────────────────────────────────────────────────────
        // Admin dashboard stats cache invalidation
        // ───────────────────────────────────────────────────────────────

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
    }
}