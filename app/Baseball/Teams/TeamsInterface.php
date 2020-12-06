<?php

namespace Artifacts\Baseball\Teams;

/**
 * Works with teams data source
 */

interface TeamsInterface {

    public function getTeams(array $fields);
    public function getCurrentTeams();

}