<?php

namespace App\Providers;

use App\Models\Blog;
use App\Policies\BlogPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Blog::class => BlogPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
