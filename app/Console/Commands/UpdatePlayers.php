<?php

namespace Artifacts\Console\Commands;

use Illuminate\Console\Command;
use Intervention\Image\ImageManagerStatic as Image;
use Storage;
use Log;

use Artifacts\Player\Player;

class UpdatePlayers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:update-players';

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
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Backup DB in case there is an issue with the update players process
        $this->call('db:backup');

        // Get mlb links for all players
        $players_html = file_get_contents('https://www.mlb.com/players');

        $player = 'href="/player/';

        $offset = 0;
        while (($pos = strpos($players_html, $player, $offset)) !== FALSE):
            $pos = strpos($players_html, $player, $offset);
            $endpos = strpos($players_html, ' ', $pos);
            // Strip off href tag from string, and therefore tweak end position, to be left with /player/fernando-abad-472551
            $player_url = substr($players_html, $pos + 6, $endpos - $pos - 7);
            $this->updatePlayer($player_url);
            $offset = $pos + 1;
        endwhile;

    }

    /**
     * Update the player
     *
     * @param string $link
     * The player link
     */
    public function updatePlayer($link)
    {
        Log::info("");
        Log::info($link);

        $name = $this->getPlayerName($link);

        if($name):

            $player = Player::select('*')->where('first_name', $name[0])->where('last_name', $name[1])->get();
            $count = count($player);

            $player_html = @file_get_contents('https://www.mlb.com' . $link);

            if($player_html):

                if($count === 0):
                    Log::info('Player does not exist, adding');
                    $id = $this->getPlayerId($link);
                    $this->addPlayer($name, $id, $player_html);
                endif;

                if($count > 1):
                    Log::info('Multiple players with same name');
                endif;

                if($count === 1):
                    $player = $player[0];

                    // Most NL pitchers will have batting stats, and some batter have pitching stats, but they are no really of interest.
                    // This will get the important stats in all cases except for players like Shohei Ohtani
                    if('P' === $player->position):
                        $mlb_career_stats = '{"header":"MLB Career Stats","wins"';
                    else:
                        $mlb_career_stats = '{"header":"MLB Career Stats","atBats"';
                    endif;

                    // {"header":"MLB Career Stats","wins":8,"losses":29,"era":"3.67","gamesPlayed":384,"gamesStarted":6,"saves":2,"inningsPitched":"330.2","strikeOuts":280,"whip":"1.29"}
                    // {"header":"MLB Career Stats","atBats":1181,"runs":238,"hits":333,"homeRuns":78,"rbi":187,"stolenBases":59,"avg":".282","obp":".370","ops":".907"}
                    $pos = strpos($player_html, $mlb_career_stats);
                    $endpos = strpos($player_html, '}', $pos);
                    $stats = substr($player_html, $pos, $endpos - $pos + 1);
                    $stats = json_decode($stats);

                    if(isset($stats->atBats)):
                        Log::info('Batter');
                        Log::info('ABs ' . $stats->atBats . ' ' . $player->at_bats . ' ' . ($stats->atBats - $player->at_bats));
                        Log::info('HRs ' . $stats->homeRuns . ' ' . $player->home_runs . ' ' . ($stats->homeRuns - $player->home_runs));
                        Log::info('RBIs ' . $stats->rbi . ' ' . $player->rbis . ' ' . ($stats->rbi - $player->rbis));
                        Log::info('AVG ' . $stats->avg . ' ' . $player->average . ' ' . ($stats->avg - $player->average));
                        $player->at_bats    = $stats->atBats;
                        $player->home_runs  = $stats->homeRuns;
                        $player->rbis       = $stats->rbi;
                        $player->average    = $stats->avg;
                    endif;

                    if(isset($stats->wins)):
                        Log::info('Pitcher');
                        Log::info('Wins ' . $stats->wins . ' ' . $player->wins . ' ' . ($stats->wins - $player->wins));
                        Log::info('Losses ' . $stats->losses . ' ' . $player->losses . ' ' . ($stats->losses - $player->losses));
                        Log::info('ERA ' . $stats->era . ' ' . $player->era . ' ' . ($stats->era - $player->era));
                        Log::info('Games ' . $stats->gamesPlayed . ' ' . $player->games . ' ' . ($stats->gamesPlayed - $player->games));
                        Log::info('Saves ' . $stats->saves . ' ' . $player->saves . ' ' . ($stats->saves - $player->saves));
                        $player->wins   = $stats->wins;
                        $player->losses = $stats->losses;
                        $player->era    = $stats->era;
                        $player->games  = $stats->gamesPlayed;
                        $player->saves  = $stats->saves;
                    endif;

                    // Update player stats
                    $player->save();
                endif;

            else:
                Log::info('Error retrieving player page');
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
    private function getPlayerName(string $link) {
        $name = null;

        $player_name = explode('/', $link);

        if(count($player_name) > 2 && strpos($player_name[2], '-') !== false):
            // Extract last portion of link
            $name = explode('-', $player_name[2]);
            $count = count($name);

            if($count > 3):
                // Non standard name
                $non_standard_name = $this->non_standard_names[$link] ?? null;

                if($non_standard_name):
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
    private function getPlayerId(string $link) {
        $id = null;

        $player_name = explode('/', $link);

        if(count($player_name) > 2 && strpos($player_name[2], '-') !== false):
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
     * @param array $name Player first and last name
     * @param mixed $id MLB player id
     * @param string $player_html Player page
     * @return mixed Id
     */
    private function addPlayer(array $name, $id, string $player_html) {
        // Get team
        $pos = strpos($player_html, 'playerTeamName:');
        $endpos = strpos($player_html, "',", $pos);
        $team = substr($player_html, $pos + 17, $endpos - $pos - 17);
        $team = array_search(trim($team), config('teams'));
        Log::info('Team ' . $team);

        // Only add if they are in a major league team
        if($team):
            // Get birthdate
            $pos = strpos($player_html, 'Born:');
            $endpos = strpos($player_html, "in", $pos);
            $born = substr($player_html, $pos + 12, $endpos - $pos - 12);
            $born = trim($born);
            $born = explode('/', $born);
            $birthdate = null;
            // Translate 3/27/1990 to 1990-3-27
            if(count($born) === 3):
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
            $image_src = 'https://securea.mlb.com/mlb/images/players/head_shot/' . $id . '.jpg';
            if (@file_get_contents($image_src)):

                $file_name  = time() . '.' . $name[0] . '_' . $name[1] . '.' . 'jpeg';

                $_img = Image::make($image_src);

                $img = $_img;
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $img->stream();

                Storage::disk('public')->put('images/smalls' . '/' . $file_name, $img);

                $img = $_img;
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
            ]);
        endif;
    }
}