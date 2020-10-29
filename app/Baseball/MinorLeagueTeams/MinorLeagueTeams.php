<?php

namespace Artifacts\Baseball\MinorLeagueTeams;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;

use Illuminate\Database\Eloquent\Model;

class MinorLeagueTeams extends Model implements MinorLeagueTeamsInterface
{

    protected $table = 'minor_league_teams';

    protected $fillable = ['team'];

    public function getTeams()
    {
        $teams = MinorLeagueTeams::select('team')->orderBy('team', 'asc')->get()->toArray();
        return array_column($teams, 'team');
    }

    public function addTeam(string $team)
    {
        MinorLeagueTeams::create(['team' => $team]);
    }

}