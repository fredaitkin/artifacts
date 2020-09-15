<?php

namespace Artifacts\Console\Commands;

use Illuminate\Console\Command;

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
        // TODO CREATE BACKUP FIRST
        $players_html = file_get_contents('https://www.mlb.com/players');

        $player = 'href="/player/';

        $offset = 0;
        while (($pos = strpos($players_html, $player, $offset)) !== FALSE) {
            $endpos = strpos($players_html, ' ', $pos);
            $player_url = substr($players_html, $pos + 6, $endpos - $pos - 7);
            $this->updatePlayer($player_url);
            exit;
            $offset = $pos + 1;
        }

    }

    /**
     * Update the player
     *
     * @param string $url
     * The player url
     */
    public function updatePlayer($url)
    {
        $player_html = file_get_contents('https://www.mlb.com' . $url);

        $mlb_career_stats = '{"header":"MLB Career Stats"';

        $pos = strpos($player_html, $mlb_career_stats);
        $endpos = strpos($player_html, '}', $pos);
        $stats = substr($player_html, $pos, $endpos - $pos + 1);
        $stats = json_decode($stats);
        echo $stats->whip;
    }

}