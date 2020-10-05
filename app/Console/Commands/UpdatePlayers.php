<?php

namespace Artifacts\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Log;

use Artifacts\Player\Player;
use Artifacts\MinorLeagueTeams\MinorLeagueTeamsInterface;

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
    * Map MLB player linked name to player in DB
    *
    * @var array
    */
    protected $non_standard_names = [
        '/player/wei-yin-chen-612672'           => ['Wei-Yin', 'Chen'],
        '/player/shao-ching-chiang-623992'      => ['Shao-Ching', 'Chang'],
        '/player/ji-man-choi-596847'            => ['Ji-Man', 'Choi'],
        '/player/shin-soo-choo-425783'          => ['Shin-Soo', 'Choo'],
        '/player/travis-d-arnaud-51859'         => ['Travis', "d'Arnaud"],
        '/player/brett-de-geus-676969'          => ['Brett', 'De Geus'],
        '/player/alex-de-goti-621008'           => ['Alex', 'De Goti'],
        '/player/adrian-de-horta-641506'        => ['Adrian', 'De Horta'],
        '/player/jasseel-de-la-cruz-665600'     => ['Jasseel', 'De La Cruz'],
        '/player/oscar-de-la-cruz-642601'       => ['Oscar', 'De La Cruz'],
        '/player/chad-de-la-guerra-664750'      => ['Chad', 'De La Guerra'],
        '/player/jose-de-leon-592254'           => ['Jose', 'De Leon'],
        '/player/enyel-de-los-santos-660853'    => ['Enyel', 'De Los Santos'],
        '/player/miguel-del-pozo-600887'        => ['Miguel', 'Del Pozo'],
        '/player/chi-chi-gonzalez-592346'       => ['Chi chi', 'Gonzalez'],
        '/player/chih-wei-hu-629496'            => ['Chih-Wei', 'Hu'],
        '/player/wei-chieh-huang-658791'        => ['Wei-Chieh', 'Huang'],
        '/player/jung-ho-kang-628356'           => ['Jung Ho', 'Kang'],
        '/player/kwang-hyun-kim-547942'         => ['Kwang-Hyun', 'Kim'],
        '/player/tommy-la-stella-600303'        => ['Tommy', 'La Stella'],
        '/player/tzu-wei-lin-624407'            => ['Tzu-Wei', 'Lin'],
        '/player/jean-carlos-mejia-650496'      => ['Jean Carlos', 'Mejia'],
        '/player/seth-mejias-brean-623180'      => ['Seth', 'Mejias-Brean'],
        '/player/john-ryan-murphy-571974'       => ['John Ryan', 'Murphy'],
        '/player/daniel-ponce-de-leon-594965'   => ['Daniel ', 'Ponce De Leon'],
        '/player/sean-reid-foley-656887'        => ['Sean', 'Reid-Foley'],
        '/player/hyun-jin-ryu-547943'           => ['Hyun Jin', 'Ryu'],
        '/player/ka-ai-tom-664789'              => ["Ka'ai", 'Tom'],
        '/player/wei-chung-wang-623913'         => ['Wei-Chung', 'Wang'],
        '/player/luis-alexander-basabe-642772'  => ['Luis Alexander', 'Basabe'],
        '/player/ke-bryan-hayes-663647'         => ["Ke'Bryan", "Hayes"],
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
    protected $ml_teams;

    /**
     * The Minor League Teams Interface
     *
     * @var Artifacts\Interfaces\MinorLeagueTeamsInterface
     */
    private $minor_league_teams;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MinorLeagueTeamsInterface $minor_league_teams)
    {
        // TODO use PlayerInterface
        $this->minor_league_teams = $minor_league_teams;
        $this->ml_teams = array_column($this->minor_league_teams->getTeams(), 'team');
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
            $players = Player::whereIn('id', $this->player_ids)->get();
        else:
            $players = Player::all();
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
            $player = Player::select('*')->where('mlb_link', $player_link)->get();
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

                // Get player page
                $player_html = @file_get_contents('https://www.mlb.com' . $link);

                if ($player_html):

                    $this->getTeam($player_html);
                    // Most NL pitchers will have batting stats, and some batter have pitching stats, but they are no really of interest.
                    // This will get the important stats in all cases except for players like Shohei Ohtani
                    if ('P' === $player->position):
                        $mlb_career_stats = '{"header":"MLB Career Stats","wins"';
                    else:
                        $mlb_career_stats = '{"header":"MLB Career Stats","atBats"';
                    endif;

                    $pos = strpos($player_html, $mlb_career_stats);
                    $endpos = strpos($player_html, '}', $pos);
                    $stats = substr($player_html, $pos, $endpos - $pos + 1);
                    $stats = json_decode($stats);

                    $status = 'rookie';

                    if (isset($stats->atBats)):
                        Log::info('Batter');
                        Log::info('ABs ' . $stats->atBats . ' ' . $player->at_bats . ' ' . (intval($stats->atBats) - intval($player->at_bats)));
                        Log::info('HRs ' . $stats->homeRuns . ' ' . $player->home_runs . ' ' . (intval($stats->homeRuns) - intval($player->home_runs)));
                        Log::info('RBIs ' . $stats->rbi . ' ' . $player->rbis . ' ' . (intval($stats->rbi) - intval($player->rbis)));
                        Log::info('AVG ' . $stats->avg . ' ' . $player->average . ' ' . (floatval($stats->avg) - floatval($player->average)));
                        Log::info('Hits ' . $stats->hits . ' ' . $player->hits . ' ' . (intval($stats->hits) - intval($player->hits)));
                        Log::info('Runs ' . $stats->runs . ' ' . $player->runs . ' ' . (intval($stats->runs) - intval($player->runs)));
                        Log::info('Stolen Bases ' . $stats->stolenBases . ' ' . $player->stolen_bases . ' ' . (intval($stats->stolenBases) - intval($player->stolen_bases)));
                        Log::info('OBP ' . $stats->obp . ' ' . $player->obp . ' ' . (floatval($stats->obp) - floatval($player->obp)));
                        Log::info('OPS ' . $stats->ops . ' ' . $player->ops . ' ' . (floatval($stats->ops) - floatval($player->ops)));

                        $status                 = 'active';

                        $player->at_bats        = intval($stats->atBats);
                        $player->home_runs      = intval($stats->homeRuns);
                        $player->rbis           = intval($stats->rbi);
                        $player->average        = floatval($stats->avg);
                        $player->hits           = intval($stats->hits);
                        $player->runs           = intval($stats->runs);
                        $player->stolen_bases   = intval($stats->stolenBases);
                        $player->obp            = floatval($stats->obp);
                        $player->ops            = floatval($stats->ops);
                    endif;

                    if (isset($stats->wins)):
                        Log::info('Pitcher');
                        Log::info('Wins ' . $stats->wins . ' ' . $player->wins . ' ' . (intval($stats->wins) - intval($player->wins)));
                        Log::info('Losses ' . $stats->losses . ' ' . $player->losses . ' ' . (intval($stats->losses) - intval($player->losses)));
                        Log::info('ERA ' . $stats->era . ' ' . $player->era . ' ' . (floatval($stats->era) - floatval($player->era)));
                        Log::info('Games ' . $stats->gamesPlayed . ' ' . $player->games . ' ' . (intval($stats->gamesPlayed) - intval($player->games)));
                        Log::info('Saves ' . $stats->saves . ' ' . $player->saves . ' ' . (intval($stats->saves) - intval($player->saves)));
                        Log::info('Games Started ' . $stats->gamesStarted . ' ' . $player->games_started . ' ' . (intval($stats->gamesStarted) - intval($player->games_started)));
                        Log::info('Innings Pitched ' . $stats->inningsPitched . ' ' . $player->innings_pitched . ' ' . (floatval($stats->inningsPitched) - floatval($player->innings_pitched)));
                        Log::info('Strike Outs ' . $stats->strikeOuts . ' ' . $player->strike_outs . ' ' . (intval($stats->strikeOuts) - intval($player->strike_outs)));
                        Log::info('WHIP ' . $stats->whip . ' ' . $player->whip . ' ' . (floatval($stats->whip) - floatval($player->whip)));

                        $status = 'active';

                        $player->wins               = intval($stats->wins);
                        $player->losses             = intval($stats->losses);
                        $player->era                = floatval($stats->era);
                        $player->games              = intval($stats->gamesPlayed);
                        $player->saves              = intval($stats->saves);
                        $player->games_started      = intval($stats->gamesStarted);
                        $player->innings_pitched    = floatval($stats->inningsPitched);
                        $player->strike_outs        = intval($stats->strikeOuts);
                        $player->whip               = floatval($stats->whip);
                    endif;

                    // Update status
                    $player->status = $status;
                    // Update player stats
                    $player->save();

                else:
                    Log::info('Error retrieving player page');
                endif;
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

            if ($count > 3):
                // Non standard name
                $non_standard_name = $this->non_standard_names[$link] ?? null;

                if ($non_standard_name):
                    $name = $non_standard_name;
                elseif ($count === 4):
                    // Catch players who are known by initialed nicknames such as JD Martinez or TJ McFarland
                    if(strlen($name[0]) === 1 && strlen($name[1]) === 1):
                        $name[0] = $name[0] .  $name[1];
                        $name[1] = $name[2];
                    // Catch Irish names such as Ryan O'Hearn
                    elseif (strlen($name[1]) === 1 && $name[1] === 'o'):
                        $name[1] = $name[1] . "'" . $name[2];
                    endif;
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
     * Extract player id from player link
     *
     * @param string $url Player link
     * @return mixed Id
     */
    private function getPlayerId(string $link)
    {
        $id = null;

        $player_name = explode('/', $link);

        if (count($player_name) > 2 && strpos($player_name[2], '-') !== false):
            // Extract last portion of link
            $name = explode('-', $player_name[2]);
            $count = count($name);
            $id = $name[$count - 1];
        else:
            Log::info('Unexcepted link format');
        endif;

        return $id;
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
            // Get birthdate
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
            Log::info('Birthdate ' . $birthdate);

            // Get position
            $pos = strpos($player_html, 'player-header--vitals');
            $pos = strpos($player_html, "<li>", $pos);
            $endpos = strpos($player_html, "</li>", $pos);
            $position = substr($player_html, $pos + 4, $endpos - $pos - 4);
            Log::info('Position ' . $position);

            // Get player photo
            $photo = null;
            $id = $this->getPlayerId($link);
            $image_src = 'https://securea.mlb.com/mlb/images/players/head_shot/' . $id . '.jpg';
            if (@file_get_contents($image_src)):

                $file_name  = time() . '.' . $name[0] . '_' . $name[1] . '.' . 'jpeg';

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
            else:
                Log::info('Unable to retrieve player photo');
            endif;

            Player::create([
                'first_name'    => ucfirst($name[0]),
                'last_name'     => ucfirst($name[1]),
                'team'          => $team,
                'birthdate'     => $birthdate,
                'position'      => $position,
                'photo'         => $photo,
                'mlb_link'      => $link,
            ]);
        endif;
    }

    private function getTeam(string $player_html)
    {
        $team = null;
        $pos = strpos($player_html, 'playerTeamName:');
        $endpos = strpos($player_html, "',", $pos);
        $team_str = trim(substr($player_html, $pos + 17, $endpos - $pos - 17));
        Log::info('Team ' . $team_str);
        if (strpos($team_str, 'html') === false):
            $team = array_search($team_str, config('teams'));
            // If it is not a major league team, add to minor league team data source
            if (!$team && !empty($team_str)):
                if (!in_array($team_str, $this->ml_teams)):
                    $this->minor_league_teams->addTeam($team_str);
                    $this->ml_teams[] = $team_str;
                endif;
            endif;
        endif;
        return $team;
    }
}