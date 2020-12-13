<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface as MinorLeagueTeam;
use Illuminate\Http\Request;

class MinorLeagueTeamsAPIController extends Controller
{

    /**
     * The Minor League Teams interface
     *
     * @var Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface
     */
    private $mlt;

    public function __construct(MinorLeagueTeam $mlt)
    {
        $this->mlt = $mlt;
    }

    /**
     * Retrieve minor league teams
     *
     * @param Request $request
     * @return Response
     */
    public function minor_league_teams(Request $request)
    {
       return $this->mlt->getTeams(['id', 'team as value']);
    }
}
