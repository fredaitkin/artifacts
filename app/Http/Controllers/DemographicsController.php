<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Khill\Lavacharts\Lavacharts;
use Artifacts\Interfaces\PopulationServiceInterface;

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

        $request = Request::create('/api/player/state', 'GET');
        $response = Route::dispatch($request);
        $state_data = json_decode($response->getContent());

        $request = Request::create('/api/player/us', 'GET');
        $response = Route::dispatch($request);
        $total = json_decode($response->getContent());
        $total = $total[0]->total;

        $rows = array();
        $data = array_slice($state_data, 0, 5);

        $other = $total;
        foreach($data as $state) {
            $rows[] = array($state->state, round($state->total / ($total / 100),2));
            $other -= $state->total;
        }
        $rows[] = array('Other', round($other / ($total / 100),2));

        $popularity->addStringColumn('State')
                   ->addNumberColumn('Popularity')
                   ->addRows($rows);

        $lava->PieChart('Popularity', $popularity, ['title' => 'Players by State', 'height' => 350, 'width' => 400]);

        // Players by State by Population
        $comparative_popularity = $lava->DataTable();

        foreach($state_data as $state):
            $state->comparative = round($state->total / $us_population_statistics['state_populations'][$state->state]['population'] * 100, 6);
        endforeach;

        usort($state_data, array('Artifacts\Http\Controllers\DemographicsController', 'pop_compare'));

        $data = $state_data;
        $rows = array();
        foreach($data as $state) {
            $rows[] = array($state->state, $state->comparative);
        }

        $comparative_popularity->addStringColumn('State')
                   ->addNumberColumn('ComparativePopularity')
                   ->addRows($rows);

        $lava->PieChart(
            'ComparativePopularity',
            $comparative_popularity,
            [
                'title'         => 'Top State Producers',
                'height'        => 350,
                'width'         => 500,
                'pieSliceText'  => 'none'
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

        $rows = array();
        $other = $total;
        foreach($data as $country) {
            if ($country->country != 'US') {
                $rows[] = array($country->country, round($country->total / ($total / 100),2));
            } else {
                $us = array($country->country, round($country->total / ($total / 100),2));
            }
            $other -= $country->total;
        }
        $rows[] = array('Other', round($other / ($total / 100),2));
        $rows[] = $us;

        $population->addStringColumn('Country')
                   ->addNumberColumn('Population')
                   ->addRows($rows);

        $lava->PieChart('Population', $population, ['title' => 'Players by Country', 'height' => 350, 'width' => 400]);

        return view('demographics', compact('lava'));
    }

    public function pop_compare($a, $b)
    {
        if ($a->comparative == $b->comparative) {
            return 0;
        }
        return ($a->comparative > $b->comparative) ? -1 : 1;
    }

}
