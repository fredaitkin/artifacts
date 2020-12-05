<?php

namespace Artifacts\Providers;

use Illuminate\Support\ServiceProvider;
use Log;

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

        if (config('database')['default'] === 'mysql'):
            // Player service
            $this->app->bind(
                'Artifacts\Baseball\Player\PlayerInterface',
                'Artifacts\Baseball\Player\PlayerMySQL'
            );
            // Minor League Teams service
            $this->app->bind(
                'Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface',
                'Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsMySQl'
            );
            // Teams service
            $this->app->bind(
                'Artifacts\Baseball\Teams\TeamsInterface',
                'Artifacts\Baseball\Teams\TeamsMySQl'
            );
        else:
            $this->app->bind(
                'Artifacts\Baseball\Player\PlayerInterface',
                'Artifacts\Baseball\Player\PlayerPostgres'
            );
            // Minor League Teams service
            $this->app->bind(
                'Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface',
                'Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsPostgres'
            );
            $this->app->bind(
                'Artifacts\Baseball\Teams\TeamsInterface',
                'Artifacts\Baseball\Teams\TeamsPostgres'
            );
        endif;
    }
}
