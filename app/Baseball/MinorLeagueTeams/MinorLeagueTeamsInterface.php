<?php

namespace Artifacts\Baseball\MinorLeagueTeams;

/**
 * Works with minor league teams data source
 */

interface MinorLeagueTeamsInterface {

    public function getTeams();
    public function addTeam(string $team);
    public function getTeamByID(int $id);
    public function getPlayerTeams(array $ids);
    public function updateCreate(array $keys, array $fields);

}
