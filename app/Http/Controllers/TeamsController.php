<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\Teams\TeamsInterface as Team;
use Artifacts\Rules\IsTeam;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class TeamsController extends Controller
{

    /**
     * The Teams interface
     *
     * @var Artifacts\Baseball\Teams\TeamsInterface
     */
    private $team;

    public function __construct(Team $team)
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
        if (! empty($filter) && $filter === 'all'):
            $teams = $this->team->getTeams(null, false);
        else:
            $filter = 'current';
            $teams = $this->team->getTeams(null, true);
        endif;

        return view('teams', ['teams' => $teams, 'filter' => $filter]);
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
        $team['other_names']    = $request->other_names;
        // TODO make these fields foreign keys to a team table
        $team['relocated_to']   = $request->relocated_to;
        $team['relocated_from'] = $request->relocated_from;

        if ($request->hasFile('logo')):
            $image          = $request->file('logo');
            $file_name      = $request->team . '.' . $image->extension();
            $team['logo']   = $file_name;
        endif;

        if (! empty($request->titles)):
            $titles = explode(',', $request->titles);
            $team['titles'] = serialize($titles);
        endif;

        $updatedTeam = $this->team->updateCreate(['team' => $request->team ?? null], $team);

        // Only save logo if save if successful
        if (isset($file_name)):
            $this->createTeamImages($image, $file_name);
        endif;

        return redirect('/teams');
    }

    /**
     * Create team photos
     *
     * @param string $file_name
     */
    private function createTeamImages(\Illuminate\Http\UploadedFile $image, string $file_name)
    {
        // Thumb size photo
        $img = Image::make($image->getRealPath());
        $img->resize(20, 20, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        Storage::disk('public')->put('teams/thumbnails/' . $file_name, $img);
        // Reduced size photo
        $img = Image::make($image->getRealPath());
        $img->resize(40, 40, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        Storage::disk('public')->put('teams/smalls/' . $file_name, $img);
        // Regular photo
        $img = Image::make($image->getRealPath());
        $img->stream();
        Storage::disk('public')->put('teams/regular/' . $file_name, $img);
    }

}
