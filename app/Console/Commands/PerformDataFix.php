<?php

namespace Artifacts\Console\Commands;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface as MinorLeagueTeam;
use Artifacts\Baseball\OtherTeams\OtherTeamsInterface as OtherTeam;
use Artifacts\Baseball\Player\PlayerInterface as Player;
use Artifacts\Baseball\Teams\TeamsInterface as Team;
use DomDocument;
use DOMXpath;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Log;
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
                            {--player-injuries : Write list of player injuries to file}
                            {--player-location : Set player location coordinates}
                            {--player-other-teams : Get player other teams}';

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
     * The Other Teams interface
     *
     * @var Artifacts\Baseball\OtherTeams\OtherTeamsInterface
     */
    private $other_team;

    /**
     * Teams
     *
     * @var array
     */
    private $teams;

    /**
     * Minor League Teams
     *
     * @var array
     */
    private $minor_league_teams;

    /**
     * Other Teams
     *
     * @var array
     */
    private $other_teams;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Player $player, Team $team, MinorLeagueTeam $mlt, OtherTeam $other_team)
    {
        $this->player       = $player;
        $this->team         = $team;
        $this->mlt          = $mlt;
        $this->other_team   = $other_team;
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

        if ($options['player-location']):
            $this->setPlayerLocationCoordinates();
        endif;

        if ($options['player-other-teams']):
            $this->playerOtherTeams();
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
        Storage::delete('public/injuries.txt');
        $lines = '';
        $players = $this->player->getAllPlayers();
        foreach ($players as $player):
            if ($player->mlb_link):
                $injuries = [];
                $player_html = @file_get_contents('https://www.mlb.com' . $player->mlb_link);
                $DOM = new DomDocument;
                // Ignore errors due to Html5
                @$DOM->loadHTML($player_html);
                $tables = $DOM->getElementsByTagName('table');
                $xp = new DOMXpath($DOM);
                $rows = $xp->query("//table[@class='transactions-table collapsed']//tr");

                foreach($rows as $row):
                    $cols = $xp->query( 'td', $row);
                    foreach($cols as $col):
                        if (strpos($col->textContent, 'injured') !== false || strpos($col->textContent, 'disabled') !== false):
                            $idx = $this->getIndex($col->textContent);
                            $text = explode('.', $col->textContent);
                            if (isset($text[$idx])):
                                $text = $this->cleanUpText($text[$idx]);
                                if (! empty($text) && strlen($text) > 10):
                                    if (! in_array($text, $injuries)):
                                        $injuries[] = $text;
                                    endif;
                                endif;
                            endif;
                        endif;
                    endforeach;
                endforeach;

                if (! empty($injuries)):
                    $lines .= 'Player:' . $player->first_name . ' ' . $player->last_name . "\n";
                    foreach($injuries as $injury):
                        $lines .= $injury . "\n";
                    endforeach;
                endif;
            endif;

        endforeach;
        Storage::put('public/injuries.txt', $lines);
    }

    /**
     * The actual injury will normally be the second sentence, indicated by a
     * period, except when a team or a name has a period.
     **/
    private function getIndex($text) {
        // St. Louis Cardinals, St. Lucie Mets, Jackie Bradley Jr., Lourdes Gurriel Jr.
        $special_cases = ['St. L', 'Jr.'];
        foreach($special_cases as $special_case):
            if (strpos($text, $special_case) !== false):
                return 2;
            endif;
        endforeach;
        return 1;
    }

    /**
     * The format of the transactions are not always standard, try to standardize where possible
     **/
    private function cleanUpText($text) {
        $convertedMonths = [' january ', ' february ', ' march ', ' april ', ' may ', ' june ', ' july ', ' august ', ' september ', ' october ', '  november ', ' december '];
        $correctMonths = [' January ', ' February ', ' March ', ' April ', ' May ', ' June ', ' July ', ' August ', ' September ', ' October ', '  November ', ' December '];
        $text = ucfirst(strtolower(trim($text)));
        return str_replace($convertedMonths, $correctMonths, $text);
    }

    /**
     * Set player location coordinates
     *
     * @return mixed
     */
    private function setPlayerLocationCoordinates()
    {
        $players = $this->player->getAllPlayers();
        foreach ($players as $player):
            if (empty($player->location_coordinates)):
                $address = $player->city . '+';
                if (!empty($player->state)):
                    $address .= $player->state . '+';
                endif;
                $address .= $player->country;
                $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . config('app')['google_maps_api_key'];
                $client = new Client();
                $res = $client->get($url);
                if ($res->getStatusCode() == 200):
                    $body = json_decode($res->getBody());
                    if (isset($body->results[0]->geometry)):
                        $player['location_coordinates'] = serialize([
                            'lat'   => $body->results[0]->geometry->location->lat,
                            'long'  => $body->results[0]->geometry->location->lng,
                        ]);
                        $player->save();
                    endif;
                endif;
            endif;
        endforeach;
    }

    /**
     * Get player other teams.
     *
     * @return mixed
     */
    private function playerOtherTeams()
    {
        Log::info("Updating player minor league and other teams");
        $this->teams = array_column($this->team->getTeams(['name']), 'name');
        $rows = $this->mlt->getTeams(['id', 'team', 'other_names']);
        $this->minor_league_teams = [];
        foreach($rows as $row):
            $this->minor_league_teams[$row['team']] = $row['id'];
            $other = explode(',', $row['other_names']);
            foreach($other as $team):
                $this->minor_league_teams[trim(str_replace('etc', '', $team))] = $row['id'];
            endforeach;
        endforeach;
        // var_dump($this->minor_league_teams);exit;
        $rows = $this->other_team->getTeams(['id', 'name']);
        $this->other_teams = [];
        foreach($rows as $row):
            $this->other_teams[$row['name']] = $row['id'];
        endforeach;

        // $players = $this->player->getAllPlayers();
        $players = $this->player->getPlayersByIDs(['876']);
        foreach ($players as $player):
            if ($player->mlb_link):
                $injuries = [];
                $player_html = @file_get_contents('https://www.mlb.com' . $player->mlb_link);
                $DOM = new DomDocument;
                // Ignore errors due to Html5
                @$DOM->loadHTML($player_html);
                $tables = $DOM->getElementsByTagName('table');
                $xp = new DOMXpath($DOM);
                $rows = $xp->query("//table[@class='transactions-table collapsed']//tr");

                $teams = [];
                foreach($rows as $row):
                    $cols = $xp->query( 'td', $row);

                    foreach($cols as $col):
                        $idx_assigned = strpos($col->textContent, 'assigned to');
                        if ($idx_assigned !== false):
                            echo "\n *LINE " . $col->textContent . "\n";
                            $idx_from = strpos($col->textContent, 'from');
                            if ($idx_from !== false):
                                // Two teams in row - assigned to X from Y.
                                $team = substr($col->textContent, $idx_assigned + 12, $idx_from - $idx_assigned - 13);
                                $teams[] = $team;
                                // echo ' *TEAM ' . $team . "\n";
                                $idx_period = strpos($col->textContent, '.');
                                $team = substr($col->textContent, $idx_from + 5, $idx_period - $idx_from - 5);
                                $teams[] = $team;
                                // echo ' *NEXT TEAM' . $team . "end\n";
                            else:
                                // One team in row - assigned to X.
                                $idx_period = strpos($col->textContent, '.');
                                $team = substr($col->textContent, $idx_assigned + 12, $idx_period - $idx_assigned - 12);
                                $teams[] = $team;
                                // echo ' *SINGLE TEAM ' . $team . "\n";
                            endif;
                        endif;
                    endforeach;
                endforeach;
                $teams = array_unique($teams);
                // var_dump($teams);
                foreach($teams as $team):
                    if (in_array($team, $this->teams)):
                        echo "\nIGNORE: " . $team. "\n";
                    elseif (array_key_exists($team, $this->minor_league_teams)):
                        echo "\nMINOR LEAGUE: " . $team . "\n";
                    elseif (array_key_exists($team, $this->other_teams)):
                        echo "\nOTHER: " . $team . "\n";
                    else:
                        echo "\nNOT THERE OR APPLICABLE: " . $team . "\n";
                        Log::info($player->id . ' ' . $team);
                    endif;
                endforeach;
            endif;

        endforeach;

    }

}
