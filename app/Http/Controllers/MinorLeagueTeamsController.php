<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;
use Artifacts\Baseball\Teams\TeamsInterface;
use Artifacts\Baseball\Player\PlayerInterface;
use Artifacts\Rules\IsTeam;
use Log;

class MinorLeagueTeamsController extends Controller
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

    public function __construct(MinorLeagueTeamsInterface $mlt, TeamsInterface $team, PlayerInterface $player)
    {
        $this->mlt = $mlt;
        $this->team = $team;
        $this->player = $player;
    }

    /**
     * Display fun facts
     *
     * @return Response
     */
    public function index()
    {Log::info('gday');
        return view('minor_league_teams', ['ml_teams' => $this->mlt->getTeams()]);
    }

    /**
     * Show the form for creating a new minor league team
     *
     * @return Response
     */
    public function create()
    {
        return view('minor_league_team', [
            'states'    => ['' => ''] + config('states'),
            'teams'     => ['' => ''] + $this->team->getCurrentTeams(),
            'classes'   => ['' => ''] + config('minor_league_teams.classes'),
            'leagues'   => ['' => ''] + config('minor_league_teams.leagues'),
            'divisions' => ['' => ''] + config('minor_league_teams.divisions'),
            'countries' => config('minor_league_teams.countries'),
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
        $team = $this->mlt->getTeamByID($id);
        return view('minor_league_team', [
            'team'      => $team,
            'states'    => ['' => ''] + config('states'),
            'teams'     => ['' => ''] + $this->team->getCurrentTeams(),
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
        if (isset($request->team)):
            $team['team']   = $request->team;
        endif;
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

        $this->mlt->updateCreate(['id' => $request->id ?? null], $team);

        return redirect('/minor-league-teams');
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