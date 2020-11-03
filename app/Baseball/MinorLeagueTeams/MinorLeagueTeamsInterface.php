<?php

/**
 * Works with minor league teams data source
 */

namespace Artifacts\Baseball\MinorLeagueTeams;

interface MinorLeagueTeamsInterface {

    public function getTeams();
    public function addTeam(string $team);
    public function getTeamByID(int $id);
    public function updateCreate(array $keys, array $fields);

}