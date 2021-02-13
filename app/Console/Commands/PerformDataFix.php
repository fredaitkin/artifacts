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
                            {--player-other-teams : Get player other teams}
                            {--starts-at= : Player ids starts at}
                            {--ids= : Comma separated list of player ids}
                            {--debug : Dump output}';

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
     * Command options
     *
     * @var array
     */
    private $options;

    /**
     * Debug flag
     *
     * @var bool
     */
    private $debug = false;

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
        $this->options = $this->options();

        if ($this->options['debug']) {
            $this->debug = true;
        }

        if ($this->options['serialize-prev-teams']):
            echo "This has been run.\n";
        endif;

        if ($this->options['pivot-prev-teams']):
            echo "This has been run.\n";
        endif;

        if ($this->options['player-injuries']):
            $this->playerInjuries();
        endif;

        if ($this->options['player-location']):
            $this->setPlayerLocationCoordinates();
        endif;

        if ($this->options['player-other-teams']):
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
        if (isset($this->options['ids'])):
            $players = $this->player->getPlayersByIDs(explode(',', $this->options['ids']));
        elseif (isset($this->options['starts-at'])):
            $players = $this->player->getAllPlayers(['id' => ['operator' => '>=', 'value' => $this->options['starts-at']]]);
        else:
            $players = $this->player->getAllPlayers();
        endif;

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

        $rows = $this->other_team->getTeams(['id', 'name']);
        $this->other_teams = [];
        foreach($rows as $row):
            $this->other_teams[$row['name']] = $row['id'];
        endforeach;

        foreach ($players as $player):
            if ($player->mlb_link):
                $player_html = @file_get_contents('https://www.mlb.com' . $player->mlb_link);
                $DOM = new DomDocument;
                // Ignore errors due to Html5
                @$DOM->loadHTML($player_html);
                $tables = $DOM->getElementsByTagName('table');
                $xp = new DOMXpath($DOM);
                $rows = $xp->query("//table[@class='transactions-table collapsed']//tr");

                if ($this->debug):
                    echo $player->id . "\n";
                endif;

                $teams = [];
                $mlt_added = [];
                foreach($rows as $row):
                    $cols = $xp->query( 'td', $row);

                    foreach($cols as $col):
                        $idx_assigned = strpos($col->textContent, 'assigned to');
                        if ($idx_assigned !== false):
                            $idx_from = strpos($col->textContent, ' from');
                            if ($idx_from !== false):
                                // Two teams in row - assigned to X from Y.
                                $teams[] = $this->extractString($col->textContent, $idx_assigned, $idx_from, 12);
                                // One team - assigned from.
                                $teams[] = $this->extractString($col->textContent, $idx_from, strrpos($col->textContent, '.'), 5);
                            else:
                                // One team - assigned to X.
                                $teams[] = $this->extractString($col->textContent, $idx_assigned, strrpos($col->textContent, '.'), 12);
                            endif;
                        else:
                            if (strpos($col->textContent, 'optioned') !== false):
                                $teams[] = $this->extractString($col->textContent, strpos($col->textContent, ' to '), strrpos($col->textContent, '.'), 4);
                            endif;
                            $idx_outright = strpos($col->textContent, 'outright to');
                            if ($idx_outright !== false):
                                $teams[] = $this->extractString($col->textContent, $idx_outright, strrpos($col->textContent, '.'), 12);
                            endif;
                            $idx_rehab = strpos($col->textContent, 'rehab assignment to');
                            if ($idx_rehab !== false):
                                $teams[] = $this->extractString($col->textContent, $idx_rehab, strrpos($col->textContent, '.'), 20);
                            endif;
                            if (strpos($col->textContent, 'contract of') !== false):
                                $teams[] = $this->extractString($col->textContent, strpos($col->textContent, 'from'), strrpos($col->textContent, '.'), 5);
                            endif;
                            $idx_loaned = strpos($col->textContent, 'loaned to');
                            if ($idx_loaned !== false):
                                $teams[] = $this->extractString($col->textContent, $idx_loaned, strrpos($col->textContent, 'from'), 9);
                            endif;
                            $idx_loaned = strpos($col->textContent, 'transferred to');
                            if ($idx_loaned !== false):
                                $team = $this->extractString($col->textContent, $idx_loaned, strrpos($col->textContent, 'from'), 14);
                                Log::info("Check me " . $team);
                                // $teams[] = $this->extractString($col->textContent, $idx_loaned, strrpos($col->textContent, 'from'), 14);
                            endif;
                        endif;
                    endforeach;
                endforeach;

                // Reverse to chronological order
                $teams = array_reverse($teams);
                // Remove duplicates.
                $teams = array_unique($teams);

                if ($this->debug):
                    var_dump($teams);
                    exit;
                endif;

                // Teams already set against the player.
                $current_teams = $player->getAllOtherTeams();
                // New teams to add.
                $remaining_teams = array_diff($teams, $current_teams);

                foreach($remaining_teams as $team):
                    if ($team):
                        if (in_array($team, $this->teams)):
                        elseif (array_key_exists($team, $this->minor_league_teams)):
                            // Teams can come through with different names
                            if (!in_array($this->minor_league_teams[$team], $mlt_added)):
                                $player->minor_teams()->attach(['mlt_id' => $this->minor_league_teams[$team]]);
                                $mlt_added[] = $this->minor_league_teams[$team];
                            endif;
                        elseif (array_key_exists($team, $this->other_teams)):
                            $player->non_mlb_affiliated_teams()->attach(['other_teams_id' => $this->other_teams[$team]]);
                        else:
                            Log::info($player->id . ' ' . $team);
                        endif;
                    endif;
                endforeach;

            endif;

        endforeach;

    }

    /**
     * Helper function to extract substring from a string
     *
     * @return mixed
     */
    private function extractString($text, $left_boundary, $right_boundary, $offset) {
        $data = substr($text, $left_boundary + $offset, $right_boundary - $left_boundary - $offset);
        $data = trim($data);
        if ($this->debug):
            echo "*" . $data . "*\n";
        endif;
        return $data;
    }

}
