<?php

namespace App\Providers;

use App\Models\Colocation;
use App\Policies\ColocationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as BaseAuthServiceProvider;

class AuthServiceProvider extends BaseAuthServiceProvider
{
    protected $policies = [
        Colocation::class => ColocationPolicy::class
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
