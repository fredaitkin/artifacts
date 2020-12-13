<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Services\PopulationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Khill\Lavacharts\Lavacharts;

class DemographicsController extends Controller
{
    private $populationService;

    public function __construct(PopulationServiceInterface $populationService)
    {
        $this->populationService = $populationService;
    }

    /**
     * Display demographics page.
     */
    public function index()
    {
        $us_population_statistics = $this->populationService->getUSStatistics();

        $lava = new Lavacharts;

        // Players by State
        $popularity = $lava->DataTable();

        // TODO You should not mix restful and non-restful public methods in a controller
        $request = Request::create('/api/player/state', 'GET');
        $response = Route::dispatch($request);
        $state_data = json_decode($response->getContent());

        $request = Request::create('/api/player/us', 'GET');
        $response = Route::dispatch($request);
        $total = json_decode($response->getContent());
        $total = $total[0]->total;

        $rows = [];
        $data = array_slice($state_data, 0, 5);

        $other = $total;
        foreach ($data as $state):
            $rows[] = [$state->state, round($state->total / ($total / 100), 2)];
            $other -= $state->total;
        endforeach;
        $rows[] = ['Other', round($other / ($total / 100), 2)];

        $popularity->addStringColumn('State')
            ->addNumberColumn('Popularity')
            ->addRows($rows);

        $lava->PieChart('Popularity', $popularity, ['title' => 'Players by State', 'height' => 350, 'width' => 400]);

        // Players by State by Population
        $comparative_popularity = $lava->DataTable();
        $states = config('states');
        foreach ($state_data as $state):
            $state->comparative = round($state->total / $us_population_statistics['state_populations'][$states[$state->state]]['population'] * 100, 6);
        endforeach;

        usort($state_data, ['Artifacts\Http\Controllers\DemographicsController', 'popCompare']);

        $data = array_slice($state_data, 0, 10);
        $rows = [];
        foreach ($data as $state):
            $rows[] = [$state->state, $state->comparative];
        endforeach;

        $comparative_popularity->addStringColumn('State')
            ->addNumberColumn('ComparativePopularity')
            ->addRows($rows);

        $lava->BarChart(
            'ComparativePopularity',
            $comparative_popularity,
            [
                'title'                 => 'Top Ten State Producers',
                'height'                => 350,
                'width'                 => 500,
                'legend'                => 'none',
                'enableInteractivity'   => false,
            ]
        );

        // Players by nonUS Country
        $population = $lava->DataTable();

        $request = Request::create('/api/player/country', 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent());

        $request = Request::create('/api/player', 'GET');
        $response = Route::dispatch($request);
        $total = json_decode($response->getContent());
        $total = $total[0]->total;

        $rows = [];
        $other = $total;
        foreach ($data as $country):
            if ($country->country != 'US'):
                $rows[] = [$country->country, round($country->total / ($total / 100), 2)];
            else:
                $us = [$country->country, round($country->total / ($total / 100), 2)];
            endif;
            $other -= $country->total;
        endforeach;
        $rows[] = ['Other', round($other / ($total / 100), 2)];
        $rows[] = $us;

        $population->addStringColumn('Country')
            ->addNumberColumn('Population')
            ->addRows($rows);

        $lava->PieChart('Population', $population, ['title' => 'Players by Country', 'height' => 350, 'width' => 400]);

        // TODO There should be no calls to compact() in controllers
        return view('demographics', compact('lava'));
    }

    private function popCompare($a, $b)
    {
        if ($a->comparative == $b->comparative):
            return 0;
        endif;
        return ($a->comparative > $b->comparative) ? -1 : 1;
    }

}
