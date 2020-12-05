<?php

namespace Artifacts\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Log;

use Illuminate\Support\Facades\DB;
use Artifacts\Baseball\Player\PlayerInterface;

class PerformDataFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:perform-data-fix
                            {--serialize-prev-teams : Convert previous teams to serialized data}
                            {--pivot-prev-teams : Store previous teams in pivot table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix db data issues';

    /**
     * The Player Interface
     *
     * @var Artifacts\Baseball\Player\PlayerInterface
     */
    private $player;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $options = $this->options();

        if ($options['serialize-prev-teams']):
            echo "This has been run.\n";
        endif;

        if ($options['pivot-prev-teams']):
            echo "This has been run.\n";
        endif;
    }

    /**
     * Serialize previous teams
     *
     * @return mixed
     */
    private function serializePreviousTeams()
    {
        $players = $this->player->getAllPlayers();
        foreach ($players as $player):
            if ($player->previous_teams):
                $player->previous_teams = serialize(explode(',', $player->previous_teams));;
                $player->save();
            endif;
        endforeach;
    }

    /**
     * Serialize previous teams
     *
     * @return mixed
     */
    private function playerPreviousTeamsPivot()
    {
        $players = $this->player->getAllPlayers();
        foreach ($players as $player):
            if ($player->previous_teams):
                $teams = unserialize($player->previous_teams);
                foreach($teams as $team):
                    DB::table('player_previous_teams')->insert(array('player_id' => $player->id, 'team' => $team));
                endforeach;
            endif;
        endforeach;
    }
}