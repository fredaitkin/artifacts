<?php

namespace Artifacts\Http\Controllers;

use Artifacts\Baseball\Player\PlayerInterface;
use Illuminate\Support\Facades\DB;

// TODO add these methods to the player interface
class PlayerAPIController extends Controller
{

    /**
     * The Player Interface
     *
     * @var Artifacts\Baseball\Player\PlayerInterface
     */
    private $player;

    /**
     * Constructor
     */
    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
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
