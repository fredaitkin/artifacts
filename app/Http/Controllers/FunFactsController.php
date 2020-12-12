<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface as MinorLeagueTeams;
use Artifacts\Baseball\Player\PlayerInterface as Player;
use Artifacts\Baseball\Teams\TeamsInterface as Teams;

class FunFactsController extends Controller
{

    /**
     * The Player Interface
     *
     * @var Artifacts\Baseball\Player\PlayerInterface
     */
    private $player;

    /**
     * The Teams interface
     *
     * @var Artifacts\Baseball\Teams\TeamsInterface
     */
    private $team;

    /**
     * The Minor League Teams interface
     *
     * @var Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface
     */
    private $mlt;

    public function __construct(MinorLeagueTeams $mlt, Player $player, Teams $team)
    {
        $this->mlt = $mlt;
        $this->player = $player;
        $this->team = $team;
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
                'world_series_winners'  => $this->team->getWorldSeriesWinners(),
                'ml_teams'              => $this->mlt->getTeams(['id', 'team'], [['team', 'ASC']]),
                'player_cities'         => $this->player->getPlayerCityCount(),
            ]
        );
    }

}
