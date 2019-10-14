<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Khill\Lavacharts\Lavacharts;
use Artifacts\Helper\Helper;

class DemographicsController extends Controller
{
    /**
     * Display demographics page.
     */
    public function index()
    {

        $lava = new Lavacharts;
        $popularity = $lava->DataTable();

        $request = Request::create('/api/player/state', 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent());

        //https://github.com/kevinkhill/lavacharts/issues/123
        $request = Request::create('/api/player/us', 'GET');
        $response = Route::dispatch($request);
        $total = json_decode($response->getContent());
        $total = $total[0]->total;

        $rows = array();
        $data = array_slice($data, 0, 5);

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

    /**
     * Show the form for creating a new resource.
     *, compact('lava')
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
