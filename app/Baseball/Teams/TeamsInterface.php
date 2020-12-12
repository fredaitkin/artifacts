<?php

namespace Artifacts\Baseball\Teams;

/**
 * Works with teams data source
 */

interface TeamsInterface {

    public function getTeams(array $fields);
    public function getCurrentTeams();
    public function getTeamByCode(string $code);
    public function updateCreate(array $keys, array $fields);
    public function getWorldSeriesWinners();

}