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

        $url = url('') . '/api/player/state';
        $data = json_decode(file_get_contents($url));

        // TODO update application server and try guzzle client
        // $data = Helper::GetAPI($url);

        //https://github.com/kevinkhill/lavacharts/issues/123

        $rows = array();
        foreach($data as $state) {
            $rows[] = array($state->state, $state->total);
        }

        $popularity->addStringColumn('State')
                   ->addNumberColumn('Popularity')
                   ->addRows($rows);

        $lava->PieChart('Popularity', $popularity);

        $population = $lava->DataTable();

        $url = url('') . '/api/player/country';
        $data = json_decode(file_get_contents($url));

        $rows = array();
        foreach($data as $country) {
            $rows[] = array($country->country, $country->total);
        }

        $population->addStringColumn('Country')
                   ->addNumberColumn('Population')
                   ->addRows($rows);

        $lava->PieChart('Population', $population);

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
