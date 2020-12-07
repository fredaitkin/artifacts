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
    public function index()
    {
        return view('teams', ['teams' => $this->team->getTeams()]);
    }

    /**
     * Show the form for creating a new minor league team
     *
     * @return Response
     */
    public function create()
    {
        return view('team', [
            'states'    => ['' => ''] + config('states'),
        ]);
    }

    /**
     * Show the form for editing the minor league team.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $code)
    {
        $team = $this->team->getTeamByCode($code);
        return view('team', ['team' => $team, 'states' => ['' => ''] + config('states')]);
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
            'founded'           => 'nullable|integer',
            'closed'            => 'nullable|integer',
            'relocated_to'      => new IsTeam,
            'relocated_from'    => new IsTeam,
        ]);

        $team = [];
        $team['city']           = $request->city;
        $team['state']          = $request->state;
        $team['country']        = $request->country;
        $team['ground']         = $request->ground;
        $team['founded']        = $request->founded;
        $team['closed']         = $request->closed;
        $team['logo']           = $request->logo;
        $team['other_names']    = $request->other_names;
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