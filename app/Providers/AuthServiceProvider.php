<?php

namespace App\Providers;

use App\Models\Donation;
use App\Policies\DonationReceiptPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Donation::class => DonationReceiptPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
