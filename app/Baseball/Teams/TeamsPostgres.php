<?php

namespace Artifacts\Baseball\Teams;

use Artifacts\Baseball\MinorLeagueTeams\TeamsInterface;


/**
* The PostgreSQL implementation of the teams table class
*
*/

class TeamsPostgres extends Model implements TeamsInterface
{

    public $incrementing = false;

    protected $table = 'teams';

    protected $primaryKey = 'team';

}
