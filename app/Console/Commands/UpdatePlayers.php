<?php

namespace Artifacts\Console\Commands;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;
use Artifacts\Baseball\Player\PlayerInterface;
use Artifacts\Baseball\Teams\TeamsInterface;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use Exception;
use Log;
use Storage;

class UpdatePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:update-players
                            {--all : Retrieve all players from mlb site}
                            {--ids= : Comma separated list of player ids}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes MLB website to get latest player stats and updates the DB';

    /**
    * MLB batting stats mappings
    *
    * @var array
    */
    protected $batting_stats_mappings = [
        'atBats'        => ['at_bats', 'intval'],
        'homeRuns'      => ['home_runs', 'intval'],
        'rbi'           => ['rbis', 'intval'],
        'avg'           => ['average', 'floatval'],
        'hits'          => ['hits', 'intval'],
        'runs'          => ['runs', 'intval'],
        'stolenBases'   => ['stolen_bases', 'intval'],
        'obp'           => ['obp', 'floatval'],
        'ops'           => ['ops', 'floatval'],
    ];

    /**
    * MLB pitching stats mappings
    *
    * @var array
    */
    protected $pitching_stats_mappings = [
        'wins'              => ['wins', 'intval'],
        'losses'            => ['losses', 'intval'],
        'era'               => ['era', 'floatval'],
        'gamesPlayed'       => ['games', 'intval'],
        'saves'             => ['saves', 'intval'],
        'gamesStarted'      => ['games_started', 'intval'],
        'inningsPitched'    => ['innings_pitched', 'floatval'],
        'strikeOuts'        => ['strike_outs', 'intval'],
        'whip'              => ['whip', 'floatval'],
    ];

    /**
     * Specific player ids to update.
     *
     * @var string
     */
    protected $player_ids;

    /**
     * List of minor league teams.
     *
     * @var array
     */
    protected $minor_league_teams;

    /**
     * The Player Interface
     *
     * @var Artifacts\Baseball\Player\PlayerInterface
     */
    private $player;

    /**
     * The Teams Interface
     *
     * @var Artifacts\Baseball\Teams\TeamsInterface
     */
    private $team;

    /**
     * The Minor League Teams Interface
     *
     * @var Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface
     */
    private $mlt;

    /**
     * The Guzzle HTTP Client
     *
     * @var Artifacts\Baseball\Player\MinorLeagueTeams\MinorLeagueTeamsInterface
     */
    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PlayerInterface $player, TeamsInterface $team, MinorLeagueTeamsInterface $mlt)
    {
        $this->player = $player;
        $this->team = $team;
        $this->mlt = $mlt;
        $this->client = new Client;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->minor_league_teams = $this->mlt->getTeams('*');
        $this->teams = $this->team->getCurrentTeams();

        $options = $this->options();

        if (isset($options['ids'])):
            $this->player_ids = explode(',', $options['ids']);
        endif;

        if ($options['all']):
            $this->updatePlayersInSite();
        else:
             $this->updatePlayersInDB();
        endif;

    }

    /**
     * Update players in DB
     *
     * @return mixed
     */
    private function updatePlayersInDB()
    {

        if (isset($this->player_ids)):
            $players = $this->player->getPlayersByIDs($this->player_ids);
        else:
            $players = $this->player->getAllPlayers();
        endif;

        foreach ($players as $player):
            $this->updatePlayer($player->mlb_link, $player);
        endforeach;
    }

    /**
     * Update players including any new players on the mlb site
     *
     * @return mixed
     */
    private function updatePlayersInSite()
    {
        // Get mlb links for all players
        $players_html = file_get_contents('https://www.mlb.com/players');

        $player_href = 'href="/player/';

        $offset = 0;
        while (($pos = strpos($players_html, $player_href, $offset)) !== FALSE):
            $pos = strpos($players_html, $player_href, $offset);
            $endpos = strpos($players_html, ' ', $pos);
            // Strip off href tag from string, and therefore tweak end position, to be left with /player/fernando-abad-472551
            $player_link = substr($players_html, $pos + 6, $endpos - $pos - 7);
            $player = $this->player->getPlayerByLink($player_link);
            $player = $player[0] ?? null;

            if ($player):
                $this->updatePlayer($player_link, $player);
            else:
                $this->addPlayer($player_link);
            endif;

            $offset = $pos + 1;
        endwhile;
    }

    /**
     * Update the player
     *
     * @param string $link
     * The player link
     * @param object $player
     * The player DB object
     */
    public function updatePlayer($link, $player)
    {
        Log::info("");
        Log::info($link);

        if ($link):
            Log::info($player->status);

            if ($player->status !== 'retired'):

                try {
                    // Get player page
                    $response = $this->client->get('https://www.mlb.com/player/' . $link);

                    $player_html = $response->getBody()->getContents();

                    if ($player_html):
                        echo $player->id . ' ';
                        // Update details if player has changed teams.
                        $team = $this->getTeam($player_html);
                        if ($player->team != $team):
                            if ($team):
                                Log::info("Player " . $player->id . " " .  $player->first_name . " " . $player->last_name . " has changed teams");
                                $player->teams()->attach(['team' => $player->team]);
                                $player->team = $team;
                                $photo = $this->getPlayerPhoto($player->mlb_link, $player->first_name, $player->last_name);
                                if ($photo):
                                    $player->photo = $photo;
                                else:
                                    Log::info('Unable to retrieve player photo');
                                endif;
                            else:
                                Log::info('Currently not playing major league ball');
                            endif;
                        endif;

                        // Update player stats
                        $this->setStats($player_html, $player);
                        $player->save();

                    else:
                        Log::info('Error retrieving player page');
                    endif;

                } catch(Exception $e) {
                    Log::info('Error retrieving player page: ' . $e->getMessage());
                }
            endif;
        endif;

    }

    /**
     * Extract first and last name from player link
     * Typical player link format /player/firstname-lastname-id
     *
     * @param string $url Player link
     * @return array Name
     */
    private function getPlayerName(string $link)
    {
        $name = null;

        $player_name = explode('/', $link);

        if (count($player_name) > 2 && strpos($player_name[2], '-') !== false):
            // Extract last portion of link
            $name = explode('-', $player_name[2]);
            $count = count($name);

            if ($count === 4):
                // Catch players who are known by initialed nicknames such as JD Martinez or TJ McFarland
                if (strlen($name[0]) === 1 && strlen($name[1]) === 1):
                    $name[0] = $name[0] . $name[1];
                    $name[1] = $name[2];
                // Catch Irish names such as Ryan O'Hearn
                elseif (strlen($name[1]) === 1 && $name[1] === 'o'):
                    $name[1] = $name[1] . "'" . $name[2];
                endif;
            endif;

            Log::info(ucwords($name[0]));
            Log::info(ucwords($name[1]));
        else:
            Log::info('Unexcepted link format');
        endif;

        return $name;
    }

    /**
     * Set stats and returns a flag to indicate if the player has stats
     *
     * @param string $url Player html
     * @param object $player Player
     *
     * @return bool
     */
    private function setStats(string $player_html, object &$player)
    {
        // Most NL pitchers will have batting stats, and some batter have pitching stats, but they are no really of interest.
        // This will get the important stats in all cases except for players like Shohei Ohtani
        if ('P' === $player->position):
            $mlb_career_stats = '{"header":"MLB Career Stats","wins"';
            $mappings = $this->pitching_stats_mappings;
            $indicator = 'wins';
        else:
            $mlb_career_stats = '{"header":"MLB Career Stats","atBats"';
            $mappings = $this->batting_stats_mappings;
            $indicator = 'atBats';
        endif;

        $pos = strpos($player_html, $mlb_career_stats);
        $endpos = strpos($player_html, '}', $pos);
        $stats = substr($player_html, $pos, $endpos - $pos + 1);
        $stats = json_decode($stats);

        $stats_found = false;
        if (isset($stats->{$indicator})):
            foreach ($mappings as $key => $stat):
                Log::info($key . ' ' . $stats->{$key} . ' ' . $player->{$stat[0]} . ' ' . ($stat[1]($stats->{$key}) - $stat[1]($player->{$stat[0]})));
                $player->{$stat[0]} = $stat[1]($stats->{$key});
            endforeach;
            $stats_found = true;
        endif;

        return $stats_found;
    }

    /**
     * Extract player id from player link
     *
     * @param string $url Player link
     * @return mixed Id
     */
    private function getPlayerMLBId(string $link)
    {
        // Extract last portion of link
        $name = explode('-', $link);
        $count = count($name);
        return $name[$count - 1];
    }

    /**
     * Add player
     *
     * @param string $link MLB player link
     */
    private function addPlayer(string $link)
    {
        Log::info("");
        Log::info($link);
        Log::info('Player does not exist, adding');

        // Get player name from link
        $name = $this->getPlayerName($link);

        // Get player page
        $player_html = @file_get_contents('https://www.mlb.com' . $link);

        // Get team
        $team = $this->getTeam($player_html);

        // Only add if they are in a major league team
        if ($team):

            // Create player
            $player = $this->player->create();
            $player->first_name = ucfirst($name[0]);
            $player->last_name  = ucfirst($name[1]);
            $player->team       = $team;
            $player->mlb_link   = $link;

            // Set birthdate
            $pos = strpos($player_html, 'Born:');
            $endpos = strpos($player_html, "in", $pos);
            $born = substr($player_html, $pos + 12, $endpos - $pos - 12);
            $born = trim($born);
            $born = explode('/', $born);
            $birthdate = null;
            // Translate 3/27/1990 to 1990-3-27
            if (count($born) === 3):
                $birthdate = $born[2] . '-' . $born[0] . '-' . $born[1];
            endif;
            $player->birthdate = $birthdate;
            Log::info('Birthdate ' . $birthdate);

            // Set position
            $pos = strpos($player_html, 'player-header--vitals');
            $pos = strpos($player_html, "<li>", $pos);
            $endpos = strpos($player_html, "</li>", $pos);
            $player->position = substr($player_html, $pos + 4, $endpos - $pos - 4);
            Log::info('Position ' . $player->position);

            // Set stats
            $status = 'rookie';
            $stats_found = $this->setStats($player_html, $player);
            if ($stats_found):
                $status = 'active';
            endif;
            $player->status = $status;

            // Get player photo
            $photo = $this->getPlayerPhoto($link, $player->first_name, $player->last_name);
            if ($photo):
                $player->photo = $photo;
            else:
                Log::info('Unable to retrieve player photo');
            endif;

            $player->save();
        endif;
    }

    /**
     * Get player team
     *
     * @param string $player_html MLB player page
     */
    private function getTeam(string $player_html)
    {
        $team = null;
        $pos = strpos($player_html, 'playerTeamName:');
        $endpos = strpos($player_html, "',", $pos);
        $team_str = trim(substr($player_html, $pos + 17, $endpos - $pos - 17));
        Log::info('Team ' . $team_str);
        if (strpos($team_str, 'html') === false):
            $team = array_search($team_str, $this->teams);
            // If it is not a major league team, add to minor league team data source
            if (! $team && ! empty($team_str)):
                if (! in_array($team_str, $this->minor_league_teams)):
                    $this->mlt->addTeam($team_str);
                    $this->minor_league_teams[] = $team_str;
                endif;
            endif;
        endif;
        return $team;
    }

    /**
     * Get player photo
     *
     * @param string $link MLB player link
     * @param string $first_name Player first name
     * @param string $llast_name Player last name
     */
    private function getPlayerPhoto($link, $first_name, $last_name)
    {
        // Get player photo
        $photo = null;
        $id = $this->getPlayerMLBId($link);
        try {
            $image_src = 'https://securea.mlb.com/mlb/images/players/head_shot/' . $id . '.jpg';
            if (@file_get_contents($image_src)):
                $file_name  = time() . '.' . $first_name . '_' . $last_name . '.' . 'jpeg';

                // Reduced size photo
                $img = Image::make($image_src);
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream();
                Storage::disk('public')->put('images/smalls' . '/' . $file_name, $img);

                // Regular photo
                $img = Image::make($image_src);
                $img->stream();
                Storage::disk('public')->put('images/regular' . '/' . $file_name, $img);

                $photo = serialize(['regular' => $file_name, 'small' => $file_name]);
            endif;
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
        return $photo;
    }
}
