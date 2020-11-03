<?php

namespace Artifacts\Baseball\MinorLeagueTeams;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;

use Illuminate\Database\Eloquent\Model;

class MinorLeagueTeams extends Model implements MinorLeagueTeamsInterface
{

    protected $table = 'minor_league_teams';

    protected $guarded = ['id'];

    public function getTeams()
    {
        return MinorLeagueTeams::select('*')->orderBy('team', 'asc')->get();
    }

    public function addTeam(string $team)
    {
        MinorLeagueTeams::create(['team' => $team]);
    }

    /**
     * Get team by id
     *
     * @return array
     */
    public function getTeamByID(int $id)
    {
        return MinorLeagueTeams::findOrFail($id);
    }

    /**
     * Update or create team
     *
     * @param array $keys
     * @param array $fields
     * @return object
     */
    public function updateCreate(array $keys, array $fields)
    {
        return MinorLeagueTeams::updateOrCreate($keys, $fields);
    }

}