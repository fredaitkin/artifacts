<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\OtherTeams\OtherTeamsInterface as OtherTeam;
use Artifacts\Traits\StoreImageTrait;
use Illuminate\Http\Request;

class OtherTeamsController extends Controller
{

    use StoreImageTrait;

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
        if (empty($request->q)):
            return view('other_teams', ['teams' => $this->team->getTeams()]);
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
        $team['other_names']    = $request->other_names;

        $team = $this->team->updateCreate(['id' => $request->id ?? null], $team);

        // Only save logo if save if successful
        if ($request->hasFile('logo')):
            $image      = $request->file('logo');
            $file_name  = $team->id . '.' . $image->extension();
            $this->storeImage($image, $file_name, 'other_teams/regular/');
            $this->storeImage($image, $file_name, 'other_teams/smalls/', [40, 40]);
            $this->storeImage($image, $file_name, 'other_teams/thumbnails/', [20, 20]);
            $team->logo = $file_name;
            $team->save();
        endif;

        return redirect('/other-teams');
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
          $teams = $this->team->search($q);
        endif;
        if (count($teams) > 0):
            return view('other_teams', ['teams' => $teams, 'q' => $q]);
        else:
            return view('other_teams', ['teams' => $teams, 'q' => $q])->withMessage('No teams found. Try to search again!');
        endif;
    }

}
