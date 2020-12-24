<?php

namespace Artifacts\Console\Commands;

use Artifacts\Baseball\Player\PlayerInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

use Storage;

class PerformDataFix extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:perform-data-fix
                            {--serialize-prev-teams : Convert previous teams to serialized data}
                            {--pivot-prev-teams : Store previous teams in pivot table}
                            {--player-injuries : Write list of player injuries to file}';

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

        if ($options['player-injuries']):
            $this->playerInjuries();
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

    /**
     * Get player injuries
     *
     * @return mixed
     */
    private function playerInjuries()
    {
        $lines = '';
        $players = $this->player->getAllPlayers();
        // $players = $this->player->getPlayersByIDs([1,2,3,4,5,6,7,8,9]);
        foreach ($players as $player):
            if ($player->mlb_link):
                $player_html = @file_get_contents('https://www.mlb.com' . $player->mlb_link);
                $DOM = new \DomDocument();
                // Ignore errors due to Html5
                @$DOM->loadHTML($player_html);
                $tables = $DOM->getElementsByTagName('table');
                $xp = new \DOMXpath($DOM);
                $rows = $xp->query("//table[@class='transactions-table collapsed']//tr");

                foreach($rows as $row):
                    $cols = $xp->query( 'td', $row);
                    foreach($cols as $col):
                        if (strpos($col->textContent, 'injured') !== false || strpos($col->textContent, 'disabled') !== false):
                            $text = explode('.', $col->textContent);
                            if (isset($text[1]) && !empty(trim($text[1])) && strlen($text[1]) > 10):
                                $lines .= $text[1] . "\n";
                            endif;
                        endif;
                    endforeach;
                endforeach;
            endif;

        endforeach;
        Storage::put('injuries.txt', $lines);
    }
}
