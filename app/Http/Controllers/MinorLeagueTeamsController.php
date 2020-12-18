<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface as MinorLeagueTeam;
use Artifacts\Baseball\Player\PlayerInterface as Player;
use Artifacts\Baseball\Teams\TeamsInterface as Team;
use Artifacts\Rules\IsTeam;
use Illuminate\Http\Request;

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

    public function __construct(MinorLeagueTeam $mlt, Team $team, Player $player)
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
    public function index(Request $request)
    {
        if (empty($request->q)):
            return view('minor_league_teams', ['ml_teams' => $this->mlt->getTeams()]);
        else:
            return $this->search($request->q);
        endif;
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
            'defunct' => 'nullable|integer',
            'previous_teams'    => new IsTeam,
        ]);

        $team = [];
        if (isset($request->team)):
            $team['team'] = $request->team;
        endif;
        $team['city']           = $request->city;
        $team['state']          = $request->state;
        $team['country']        = $request->country;
        $team['affiliate']      = $request->affiliate;
        $team['class']          = $request->class;
        $team['league']         = $request->league;
        $team['division']       = $request->division;
        $team['founded']        = $request->founded;
        $team['defunct']        = $request->defunct;
        $team['other_names']    = $request->other_names;

        if (! empty($request->previous_teams)):
            $previous_teams = explode(',', $request->previous_teams);
            $team['previous_teams'] = serialize($previous_teams);
        endif;

        $this->mlt->updateCreate(['id' => $request->id ?? null], $team);

        return redirect('/minor-league-teams');
    }

    /**
     * Search for team/s.
     *
     * @param  string  $q
     * @return Response
     */
    private function search(string $q)
    {
        $teams = [];
        if ($q != ""):
          $teams = $this->mlt->search($q);
        endif;
        if (count($teams) > 0):
            return view('minor_league_teams', ['ml_teams' => $teams, 'q' => $q]);
        else:
            return view('minor_league_teams', ['ml_teams' => $teams, 'q' => $q])->withMessage('No teams found. Try to search again!');
        endif;
    }

}
