<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Artifacts\MinorLeagueTeams\MinorLeagueTeamsInterface;

class FunFactsController extends Controller
{

    /**
     * The Minor League Teams interface
     *
     * @var Artifacts\MinorLeagueTeams\MinorLeagueTeamsInterface
     */
    private $mlt;

    public function __construct(MinorLeagueTeamsInterface $mlt)
    {
        $this->mlt = $mlt;
    }

    /**
     * Display fun facts
     *
     * @return Response
     */
    public function index()
    {
        // Set fun facts to view
        return view(
            'fun_facts',
            [
                'ml_teams' => $this->mlt->getTeams(),
            ]
        );
    }

}