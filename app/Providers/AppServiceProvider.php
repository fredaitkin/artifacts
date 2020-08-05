<?php

namespace Artifacts\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Population service
        $this->app->bind(
            'Artifacts\Interfaces\PopulationServiceInterface',
            'Artifacts\Services\PopulationService'
        );
    }
}
