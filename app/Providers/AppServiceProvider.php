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
            'Artifacts\Services\PopulationServiceInterface',
            'Artifacts\Services\PopulationService'
        );
        // Player service
        $this->app->bind(
            'Artifacts\Player\PlayerInterface',
            'Artifacts\Player\Player'
        );
        // Minor League Teams service
        $this->app->bind(
            'Artifacts\MinorLeagueTeams\MinorLeagueTeamsInterface',
            'Artifacts\MinorLeagueTeams\MinorLeagueTeams'
        );
    }
}
