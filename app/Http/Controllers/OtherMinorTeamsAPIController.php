<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\OtherTeams\OtherTeamsInterface as OtherTeam;
use Illuminate\Http\Request;

class OtherTeamsAPIController extends Controller
{

    /**
     * The Other Teams interface
     *
     * @var Artifacts\Baseball\OtherTeams\OtherTeamsInterface
     */
    private $team;

    public function __construct(OtherTeam $team)
    {
        $this->team = $team;
    }

    /**
     * Retrieve other teams
     *
     * @param Request $request
     * @return Response
     */
    public function other_teams(Request $request)
    {
       return $this->team->getTeams(['id', 'name as value']);
    }
}
