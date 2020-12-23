<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\OtherTeams\OtherTeamsInterface as OtherTeam;
use Illuminate\Http\Request;

class OtherTeamsController extends Controller
{

    /**
     * The Minor League Teams interface
     *
     * @var Artifacts\Baseball\OtherTeams\OtherTeamsInterface
     */
    private $team;

    public function __construct(OtherTeam $team)
    {
        $this->team = $team;
    }

    /**
     * Display other teams
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('other_teams', ['teams' => $this->team->getTeams()]);
    }

    /**
     * Show the form for creating a new minor league team
     *
     * @return Response
     */
    public function create()
    {
        return view('other_team', [
            'countries' => ['' => ''] + config('countries'),
        ]);
    }

    /**
     * Show the form for editing the minor league team.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $team = $this->team->getTeamByID($id);
        return view('other_team', [
            'team'      => $team,
            'countries' => ['' => ''] + config('countries'),
        ]);
    }

    /**
     * Store a newly update minor league team in the database
     *
     * @param Request request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name'      => 'required',
            'founded'   => 'nullable|integer',
            'defunct'   => 'nullable|integer',
        ]);

        $team = [];
        $team['name']           = $request->name;
        $team['city']           = $request->city;
        $team['country']        = $request->country;
        $team['league']         = $request->league;
        $team['founded']        = $request->founded;
        $team['defunct']        = $request->defunct;

        $this->team->updateCreate(['id' => $request->id ?? null], $team);

        return redirect('/other-teams');
    }

}
