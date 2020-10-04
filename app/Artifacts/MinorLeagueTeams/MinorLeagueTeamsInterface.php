<?php

/**
 * Works with minor league teams data source
 */

namespace Artifacts\MinorLeagueTeams;

interface MinorLeagueTeamsInterface {

    public function getTeams();
    public function addTeam(string $team);

}