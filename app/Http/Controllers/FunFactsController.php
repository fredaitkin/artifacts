<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;
use Artifacts\Baseball\Player\PlayerInterface;
use Artifacts\Rules\IsTeam;

class FunFactsController extends Controller
{

    /**
     * The Minor League Teams interface
     *
     * @var Artifacts\MinorLeagueTeams\MinorLeagueTeamsInterface
     */
    private $mlt;

    public function __construct(MinorLeagueTeamsInterface $mlt, PlayerInterface $player)
    {
        $this->mlt = $mlt;
        $this->player = $player;
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
                'ml_teams'      => $this->mlt->getTeams(),
                'player_cities' => $this->player->getPlayerCityCount(),
            ]
        );
    }

    /**
     * Show the form for editing the minor league team.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $team = $this->mlt->getTeamByID($id);
        return view('minor_league_team', [
            'team'      => $team,
            'states'    => ['' => ''] + config('states'),
            'teams'     => ['' => ''] + config('teams.current'),
            'classes'   => ['' => ''] + config('minor_league_teams.classes'),
            'leagues'   => ['' => ''] + config('minor_league_teams.leagues'),
            'divisions' => ['' => ''] + config('minor_league_teams.divisions'),
            'countries' => config('minor_league_teams.countries'),
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
            'founded' => 'nullable|integer',
            'previous_teams'    => new IsTeam,
        ]);

        $team = [];
        $team['city']       = $request->city;
        $team['state']      = $request->state;
        $team['country']    = $request->country;
        $team['affiliate']  = $request->affiliate;
        $team['class']      = $request->class;
        $team['league']     = $request->league;
        $team['division']   = $request->division;
        $team['founded']    = $request->founded;

        if (!empty($request->previous_teams)):
            $previous_teams = explode(',', $request->previous_teams);
            $team['previous_teams'] = serialize($previous_teams);
        endif;

        $this->mlt->updateCreate(['id' => $request->id], $team);

        return redirect('/funfacts');
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