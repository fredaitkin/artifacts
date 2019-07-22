<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
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

        $data = json_decode(file_get_contents(url('') . '/api/player/state'));
        // TODO update application server and try guzzle client
        // $data = Helper::GetAPI($url);

        //https://github.com/kevinkhill/lavacharts/issues/123
        $total = json_decode(file_get_contents(url('') . '/api/player/us'));
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

        $data = json_decode(file_get_contents(url('') . '/api/player/country'));
        $data = array_slice($data, 0, 5);

        $total = json_decode(file_get_contents(url('') . '/api/player'));
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
