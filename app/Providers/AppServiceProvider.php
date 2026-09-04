<?php

namespace App\Providers;

use App\Services\ClickHouseService;
use App\Services\DomainSearchService;
use App\Services\DomainValuationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ClickHouseService::class, function ($app) {
            return new ClickHouseService();
        });

        $this->app->singleton(DomainSearchService::class, function ($app) {
            return new DomainSearchService($app->make(ClickHouseService::class));
        });

        $this->app->singleton(DomainValuationService::class, function ($app) {
            return new DomainValuationService($app->make(ClickHouseService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
