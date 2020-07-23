<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Artifacts\Player\Player;
use Kyslik\ColumnSortable\Sortable;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;

class PlayerController extends Controller
{
     /**
     * Constructor
     */
    public function __construct()
    {
    }

  /**
     * Display players
     *
     * @return Response
     */
    public function index()
    {
        $players = Player::sortable()->paginate(15);
        return view('players', ['players' => $players]);
    }

    /**
     * Show the form for creating a new player
     *
     * @return Response
     */
    public function create()
    {
        return view('player', ['title' => 'Add Player']);
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
            'first_name'   => 'required|max:255',
            'last_name'    => 'required|max:255',
            'team'         => 'required|string'
        ]);

        if ($request->hasFile('photo')):

            $image      = $request->file('photo');
            $file_name  = time() . '.' . $request->first_name . '_' . $request->last_name . '.' . $image->extension();

            // Reduced size photo
            $img = Image::make($image->getRealPath());
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->stream();

            Storage::disk('public')->put('images/smalls' . '/' . $file_name, $img);

            // Regular phot
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('public')->put('images/regular' . '/' . $file_name, $img);

        endif;

        if (isset($request->id)):
            $player = Player::findOrFail($request->id);
        else:
            $player = new Player();
        endif;

        $player->first_name     = $request->first_name;
        $player->last_name      = $request->last_name;
        $player->team           = $request->team;
        $player->city           = $request->city;
        $player->state          = $request->state;
        $player->country        = $request->country;
        $player->birthdate      = $request->birthdate;        
        $player->draft_year     = $request->draft_year;
        $player->draft_round    = $request->draft_round;
        $player->draft_position = $request->draft_position;
        $player->debut_year     = $request->debut_year;
        $player->previous_teams = $request->previous_teams;

        if (isset($file_name)):
            // Duplication caused by legacy photo processing
            $player->photo = serialize(['regular' => $file_name, 'small' => $file_name]);
        endif;

        $player->save();

        return redirect('/players');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $player = Player::find($id);
        $teams = config('teams');
        $teams[''] = 'Please Select';
        ksort($teams);
        if(isset($request->view)):
            return view('player_view', ['player' => $player]);
        else:
            return view('player', ['title' => 'Edit Player', 'player' => $player, 'teams' => $teams]);
        endif;
    }


    /**
     * Search for player.
     *
     * @param  string  $q
     * @return Response
     */
    public function search(Request $request)
    {
        $q = $request->q;
        $players = [];
        if ($q != "") {
          $players = Player::select('players.*')
                ->where('team', 'LIKE', '%' . $q . '%')
                ->orWhere('city', 'LIKE', '%' . $q . '%')
                ->orWhere('first_name', 'LIKE', '%' . $q . '%')
                ->orWhere('last_name', 'LIKE', '%' . $q . '%')
                ->orWhere('state', 'LIKE', '%' . $q . '%')
                ->orWhere('country', 'LIKE', '%' . $q . '%')
                ->orWhere('draft_year', 'LIKE', '%' . $q . '%')
                ->orWhere('draft_round', 'LIKE', '%' . $q . '%')
                ->orWhere('debut_year', 'LIKE', '%' . $q . '%')
                ->paginate(15)
                ->appends(['q' => $q])
                ->setPath('');
            }
        if (count ( $players ) > 0) {
            return view('players', ['players' => $players]);
        } else {
            return view('players', ['players' => $players])->withMessage('No Details found. Try to search again !');
        }
    }

    /**
     * Remove the player from the database
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        Player::findOrFail($id)->delete();
        return redirect('/players');
    }

    public function getStateCount()
    {
        return DB::table('players')
            ->select('state', DB::raw('count(*) as total'))
            ->whereNotNull('state')
            ->where('country', '=', 'US')
            ->groupBy('state')
            ->orderBy('total', 'DESC')
            ->get();
    }

    public function getCountryCount()
    {
        return DB::table('players')
            ->select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderBy('total', 'DESC')
            ->get();
    }

    public function getPlayerCount()
    {
        return DB::table('players')
            ->select(DB::raw('count(*) as total'))
            ->get();
    }

    public function getUSPlayerCount()
    {
        return DB::table('players')
            ->select(DB::raw('count(*) as total'))
            ->where('country', '=', 'US')
            ->get();
    }

    public function getNonUSPlayerCount()
    {
        return DB::table('players')
            ->select(DB::raw('count(*) as total'))
            ->where('country', '<>', 'US')
            ->get();
    }

}