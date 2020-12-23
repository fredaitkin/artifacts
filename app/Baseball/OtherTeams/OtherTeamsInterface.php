<?php

namespace Artifacts\Baseball\OtherTeams;

/**
 * Works with other teams data source
 */

interface OtherTeamsInterface {

    public function getTeams();
    public function getTeamByID(int $id);
    public function updateCreate(array $keys, array $fields);

}
