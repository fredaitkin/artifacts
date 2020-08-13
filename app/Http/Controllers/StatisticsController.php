<?php

namespace Artifacts\Http\Controllers;

use Illuminate\Http\Request;
use Artifacts\Interfaces\PlayerInterface;

class StatisticsController extends Controller
{

    /**
     * The Player Interfact
     *
     * @var Artifacts\Interfaces\PlayerInterface
     */
    private $player;

    /**
     * The player positions array
     *
     * @var array
     */
    private $positions;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
        $this->positions = config('positions');
    }

    /**
     * Display statistics
     *
     * @return Response
     */
    public function index()
    {
        $most_home_runs = $this->player->getMostHomeRuns();
        $most_home_runs_by_position = []; 
        foreach($this->positions as $k => $v):
            if($k !== 'P'):
                $most_home_runs_by_position[$v] = $this->player->getMostHomeRuns(['position' => $k]);
            endif;
        endforeach;
        $most_wins = $this->player->getMostWins();
        $best_era = $this->player->getBestERA();
        return view(
            'statistics',
            [
                'most_home_runs'                => $most_home_runs,
                'most_home_runs_by_position'    => $most_home_runs_by_position,
                'most_wins'                     => $most_wins,
                'best_era'                      => $best_era,
            ]
        );
    }

}