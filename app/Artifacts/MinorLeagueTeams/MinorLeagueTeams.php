<?php

namespace Artifacts\MinorLeagueTeams;

use Artifacts\MinorLeagueTeams\MinorLeagueTeamsInterface;

use Illuminate\Database\Eloquent\Model;

class MinorLeagueTeams extends Model implements MinorLeagueTeamsInterface
{

    protected $table = 'minor_league_teams';

    protected $fillable = ['team'];

    public function getTeams()
    {
        return MinorLeagueTeams::select('team')->get()->toArray();
    }

    public function addTeam(string $team)
    {
        MinorLeagueTeams::create(['team' => $team]);
    }

}