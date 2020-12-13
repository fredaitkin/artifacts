<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface as MinorLeagueTeam;
use Artifacts\Baseball\Player\PlayerInterface as Player;
use Artifacts\Baseball\Teams\TeamsInterface as Team;
use Artifacts\Rules\IsTeam;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class PlayerController extends Controller
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

     /**
     * Constructor
     */
    public function __construct(Player $player, Team $team, MinorLeagueTeam $mlt)
    {
        $this->player = $player;
        $this->team = $team;
        $this->mlt = $mlt;
    }

    /**
     * Display players
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (empty($request->q)):
            return view('players', ['players' => $this->player->getTabulatedPlayers()]);
        else:
            return $this->search($request->q);
        endif;
    }

    /**
     * Show the form for creating a new player
     *
     * @return Response
     */
    public function create()
    {
        $teams = ['' => 'Please Select'] + $this->team->getCurrentTeams();
        $states = ['' => 'Please Select'] + config('states');
        $positions = ['' => 'Please Select'] + config('positions');
        return view('player', [
            'title'     => 'Add Player',
            'teams'     => $teams,
            'states'    => $states,
            'countries' => config('countries'),
            'positions' => $positions,
        ]);
    }

    /**
     * Store a newly created player in the database
     *
     * @param Request request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'first_name'        => 'required|max:255',
            'last_name'         => 'required|max:255',
            'team'              => 'required|string',
            'draft_position'    => 'nullable|integer',
            'draft_year'        => 'nullable|integer',
            'debut_year'        => 'nullable|integer',
            'average'           => 'nullable|numeric',
            'at_bats'           => 'nullable|integer',
            'home_runs'         => 'nullable|integer',
            'rbis'              => 'nullable|integer',
            'hits'              => 'nullable|integer',
            'runs'              => 'nullable|integer',
            'stolen_bases'      => 'nullable|integer',
            'obp'               => 'nullable|numeric',
            'ops'               => 'nullable|numeric',
            'era'               => 'nullable|numeric',
            'games'             => 'nullable|numeric',
            'wins'              => 'nullable|integer',
            'losses'            => 'nullable|integer',
            'saves'             => 'nullable|integer',
            'games_started'     => 'nullable|integer',
            'innings_pitched'   => 'nullable|numeric',
            'strike_outs'       => 'nullable|integer',
            'whip'              => 'nullable|numeric',
            'previous_teams'    => new IsTeam,
        ]);

        if ($request->hasFile('photo')):
            $image      = $request->file('photo');
            $file_name  = time() . '.' . $request->first_name . '_' . $request->last_name . '.' . $image->extension();
        endif;

        // Player as array
        $player = [];
        $player['first_name']       = $request->first_name;
        $player['last_name']        = $request->last_name;
        $player['team']             = $request->team;
        $player['city']             = $request->city;
        $player['state']            = $request->state;
        $player['country']          = $request->country;
        $player['birthdate']        = $request->birthdate;
        $player['draft_year']       = $request->draft_year;
        $player['draft_round']      = $request->draft_round;
        $player['draft_position']   = $request->draft_position;
        $player['debut_year']       = $request->debut_year;
        $player['position']         = $request->position;
        $player['average']          = $request->average;
        $player['at_bats']          = $request->at_bats;
        $player['rbis']             = $request->rbis;
        $player['home_runs']        = $request->home_runs;
        $player['hits']             = $request->hits;
        $player['runs']             = $request->runs;
        $player['stolen_bases']     = $request->stolen_bases;
        $player['obp']              = $request->obp;
        $player['ops']              = $request->ops;
        $player['era']              = $request->era;
        $player['games']            = $request->games;
        $player['wins']             = $request->wins;
        $player['losses']           = $request->losses;
        $player['saves']            = $request->saves;
        $player['games_started']    = $request->games_started;
        $player['innings_pitched']  = $request->innings_pitched;
        $player['strike_outs']      = $request->strike_outs;
        $player['whip']             = $request->whip;
        $player['status']           = $request->status;

        if (! empty($request->minor_league_teams)):
            $player['minor_league_teams'] = serialize(explode(',', $request->minor_league_teams));
        endif;

        if (isset($file_name)):
            // Duplication caused by legacy photo processing
            $player['photo'] = serialize(['regular' => $file_name, 'small' => $file_name]);
        endif;

        // Player as DB object
        $player = $this->player->updateCreate(['id' => $request->id ?? null], $player);

        // Only save photo if save if successful
        if (isset($file_name)):
            // Reduced size photo
            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->stream();

            Storage::disk('public')->put('images/smalls' . '/' . $file_name, $img);

            // Regular photo
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('public')->put('images/regular' . '/' . $file_name, $img);
        endif;

        // Make any updates to previous teams
        if (! empty($request->previous_teams)):
            $request->previous_teams = explode(', ', trim($request->previous_teams));
            $inserts = array_diff($request->previous_teams, $player->previous_teams_array);
            foreach($inserts as $team):
                $player->teams()->attach(['team' => $team]);
            endforeach;
            $deletes = array_diff($player->previous_teams_array, $request->previous_teams);
            foreach($deletes as $team):
                $player->teams()->detach(['team' => $team]);
            endforeach;
        endif;

        // Make any updates to minor league teams
        if (! empty($request->minor_league_teams)):
            $request->minor_league_teams = explode(',', trim($request->minor_league_teams));
            $inserts = array_diff($request->minor_league_teams, $player->minor_league_teams_array);
            foreach($inserts as $id):
                $player->minor_teams()->attach(['mlt_id' => $id]);
            endforeach;
            $deletes = array_diff($player->minor_league_teams_array, $request->minor_league_teams);
            foreach($deletes as $id):
                $player->minor_teams()->detach(['mlt_id' => $id]);
            endforeach;
        endif;

        return redirect('/players');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $player = $this->player->getPlayerByID($id);
        $teams = ['' => 'Please Select'] + $this->team->getCurrentTeams();
        $states = ['' => 'Please Select'] + config('states');
        $positions = ['' => 'Please Select'] + config('positions');
        if (isset($player->mlb_link[2])):
            $player->mlb_link = explode('/', $player->mlb_link)[2];
        endif;
        if (isset($request->view)):
            return view('player_view', ['player' => $player, 'team' => $teams[$player->team]]);
        else:
            return view('player', [
                'title'                     => '',
                'player'                    => $player,
                'teams'                     => $teams,
                'states'                    => $states,
                'countries'                 => config('countries'),
                'positions'                 => $positions,
            ]);
        endif;
    }

    /**
     * Search for player/s.
     *
     * @param  string  $q
     * @return Response
     */
    private function search(string $q)
    {
        $players = [];
        if ($q != ""):
          $players = $this->player->search($q);
        endif;
        if (count($players) > 0):
            return view('players', ['players' => $players, 'q' => $q]);
        else:
            return view('players', ['players' => $players, 'q' => $q])->withMessage('No players found. Try to search again!');
        endif;
    }

    /**
     * Remove the player from the database
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->player->deleteByID($id);
        return redirect('/players');
    }

}
