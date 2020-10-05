<?php

namespace Artifacts\Services;

use Artifacts\Services\PopulationServiceInterface;
use Cache;

class PopulationService implements PopulationServiceInterface {

    /**
     * Retrieve US population statistics
     * @return array $statistics Statistics
     */
    public function getUSStatistics()
    {
        if (!Cache::has('us_population_statistics')):
            // Get population of country
            $url = 'https://api.census.gov/data/2019/pep/natmonthly?get=POP,MONTHLY_DESC&for=us:*';
            $country = json_decode(file_get_contents($url));
            // Get population of each state
            $url = 'https://api.census.gov/data/2019/pep/charagegroups?get=POP,NAME&for=state:*&DATE_CODE=12';
            // TODO update Laravel version
            //$response = Http::get($url);
            $states = json_decode(file_get_contents($url));
            $population = [];
            // First record is header
            unset($states[0]);
            foreach ($states as $state):
                $population[$state[1]] = ['population' => $state[0], 'percent' => round($state[0] / $country[1][0] * 100, 2)];
            endforeach;
            ksort($population);
            Cache::put('us_population_statistics', ['country_population' => $country[1][0], 'state_populations' => $population], 604800);
        endif;

        return Cache::get('us_population_statistics');
    }

}