<?php

namespace Artifacts\Console\Commands;

use Illuminate\Console\Command;
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
     * @param string $url
     * The player url
     */
    public function updatePlayer($url)
    {
        Log::info("");
        Log::info($url);

        $player_name = explode('/', $url);
        $player_name = explode('-', $player_name[2]);
        $count = count($player_name);

        if($count > 2):

            // The following will not currently work with some nicknamed players or non standard name 
            // eg. /player/ji-man-choi-596847  /player/travis-d-arnaud-51859 /player/brett-de-geus-676969 /player/chad-de-la-guerra-664750 
            // It works by default with /player/jackie-bradley-jr-598265

            Log::info(ucwords($player_name[0]));
            Log::info(ucwords($player_name[1]));

            // Catch players who are known by initialed nicknames such as JD Martinez or TJ McFarland
            if($count === 4):
                if(strlen($player_name[0]) === 1 && strlen($player_name[1]) === 1):
                    $player_name[0] = $player_name[0] .  $player_name[1];
                    $player_name[1] = $player_name[2];
                endif;
            endif;

            $player = Player::select('*')->where('first_name', $player_name[0])->where('last_name', $player_name[1])->get();
            $count = count($player);

            if($count === 0):
                Log::info('Player does not exist');
            endif;

            if($count > 1):
                Log::info('Multiple players with same name');
            endif;

            if($count === 1):
                $player = $player[0];

                $player_html = @file_get_contents('https://www.mlb.com' . $url);

                if($player_html):

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
                else:
                    Log::info('Error retrieving player page');
                endif;
            endif;
        endif;

    }

}