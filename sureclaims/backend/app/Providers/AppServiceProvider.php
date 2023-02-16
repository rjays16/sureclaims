<?php

namespace App\Providers;

use App\Lib\Contracts\LookupsInterface;
use App\Lib\Lookups;
use App\Lib\Tenancy\HostnameIdentification;
use Auth0\Login\LaravelCacheWrapper;
use Carbon\Carbon;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Traits\DispatchesJobs;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
use Event;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    use DispatchesJobs;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(150);
        /** @var Environment $environment */
        if (!\App::runningInConsole()) {
            $environment = $this->app->make(Environment::class);
            $hostname = $this->dispatch(new HostnameIdentification());
            if (!$hostname) {
                $hostname = new Hostname();
            }
            $environment->hostname($hostname);
        }

        if (config('app.log_query')) {
            Event::listen(QueryExecuted::class, function ($query) {
                Log::info($query->sql);
                Log::info($query->bindings);
                Log::info($query->time);
            });
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->registerAuth0();
        $this->registerLibraries();
    }

    /**
     *
     */
    private function registerLibraries()
    {
        // Register E-claims Web Service
        $this->app->bind(\App\Lib\Soap\EclaimsServiceInterface::class, function () {
            /** @var Container $app */
            return new \App\Lib\Soap\EClaimsServiceHIE();
        });

        // Lookups factory
        $this->app->singleton(LookupsInterface::class, Lookups::class);
    }

    /**
     *
     */
    private function registerAuth0()
    {
        // Register Auth0 bindings
        $this->app->bind(
            \Auth0\Login\Contract\Auth0UserRepository::class,
            \Auth0\Login\Repository\Auth0UserRepository::class
        );
        $this->app->bind(
            \App\Lib\Auth\Contracts\Authentication::class,
            \App\Lib\Auth\Authentication::class
        );
        $this->app->bind(
            \Hyn\Tenancy\Database\Connection::class,
            \App\Lib\Tenancy\Connection::class
        );

        $this->app->bind(
            '\Auth0\SDK\Helpers\Cache\CacheHandler',
            function() {
                static $cacheWrapper = null;
                if ($cacheWrapper === null) {
                    $cache = Cache::store();
                    $cacheWrapper = new LaravelCacheWrapper($cache);
                }
                return $cacheWrapper;
            });
    }
}
