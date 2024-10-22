<?php

namespace App\Providers;

use App\Models\User;
use App\Providers\Auth\LegacyUserProvider;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register custom user providers.
     *
     * @return void
     */
    private function registerUserProviders()
    {
        Auth::provider('legacy', function ($app) {
            return new LegacyUserProvider($app->make(Hasher::class));
        });
    }

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerUserProviders();
        $this->registerPolicies();
    }

    public function register()
    {
        $this->app->singleton(User::class, function () {
            return Auth::user();
        });
    }
}
