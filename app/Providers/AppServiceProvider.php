<?php

namespace App\Providers;

use App\Services\FundraiserLevelService;
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
            return \App\Models\Campaign::with('category')
                ->where(is_numeric($value) ? 'id' : 'slug', $value)
                ->firstOrFail();
        });

        // Health Checks
        Health::checks([
            DatabaseCheck::new(),
            CacheCheck::new(),
            DebugModeCheck::new(),
        ]);
    }
}