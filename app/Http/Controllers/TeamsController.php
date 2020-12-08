<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;
use Artifacts\Baseball\Teams\TeamsInterface;
use Artifacts\Baseball\Player\PlayerInterface;
use Artifacts\Rules\IsTeam;
use Log;

class TeamsController extends Controller
{

    /**
     * The Teams interface
     *
     * @var Artifacts\Baseball\Teams\TeamsInterface
     */
    private $team;

    public function __construct(TeamsInterface $team)
    {
        $this->team = $team;
    }

    /**
     * Display fun facts
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        if (!empty($filter) && $filter === 'all'):
            $teams = $this->team->getTeams(null, false);
        else:
            $filter = 'current';
            $teams = $this->team->getTeams(null, true);
        endif;

        return view('teams')->with('teams', $teams)->with('filter', $filter);
    }

    /**
     * Show the form for adding a team.
     *
     * @param  int  $id
     * @return Response
     */
    public function create()
    {
        return view('team', [
            'states'    => ['' => ''] + config('states'),
            'leagues'   => ['' => ''] + config('teams.leagues'),
            'divisions' => ['' => ''] + config('teams.divisions'),
        ]);
    }

    /**
     * Show the form for editing the team.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $code)
    {
        $team = $this->team->getTeamByCode($code);
        return view('team', [
            'team'      => $team,
            'states'    => ['' => ''] + config('states'),
            'leagues'   => ['' => ''] + config('teams.leagues'),
            'divisions' => ['' => ''] + config('teams.divisions'),
        ]);
    }

    /**
     * Store a newly update team in the database
     *
     * @param Request request
     * @return Response
     */
    public function store(Request $request)
    {

        $validator = $request->validate([
            'founded'           => 'nullable|integer',
            'closed'            => 'nullable|integer',
            'relocated_to'      => new IsTeam,
            'relocated_from'    => new IsTeam,
        ]);

        $team = [];
        $team['team']           = $request->team;
        $team['name']           = $request->name;
        $team['league']         = $request->league;
        $team['division']       = $request->division;
        $team['city']           = $request->city;
        $team['state']          = $request->state;
        $team['country']        = $request->country;
        $team['ground']         = $request->ground;
        $team['founded']        = $request->founded;
        $team['closed']         = $request->closed;
        // TODO Change to file field
        $team['logo']           = $request->logo;
        $team['other_names']    = $request->other_names;
        // TODO make these fields foreign keys to a team table
        $team['relocated_to']   = $request->relocated_to;
        $team['relocated_from'] = $request->relocated_from;

        if (!empty($request->titles)):
            $titles = explode(',', $request->titles);
            $team['titles'] = serialize($titles);
        endif;

        $this->team->updateCreate(['team' => $request->team ?? null], $team);

        return redirect('/teams');
    }

}