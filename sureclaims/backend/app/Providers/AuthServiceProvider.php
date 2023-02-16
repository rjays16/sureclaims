<?php

namespace App\Providers;

use App\Lib\Auth\GrantGuard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
        $this->registerTokenScopes();

        /**
         * Grant guard for testing APIs without bearer token
         */
        \Auth::extend('grant', function ($app, $name, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\Guard...
            return \App::make(GrantGuard::class);
        });
    }

    /**
     * Registers the OAuth scopes used by the Passport library
     */
    private function registerTokenScopes()
    {
    }
}
