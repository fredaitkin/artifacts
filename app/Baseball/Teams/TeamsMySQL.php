<?php

namespace Artifacts\Baseball\Teams;

use Artifacts\Baseball\Teams\TeamsInterface;

use Illuminate\Database\Eloquent\Model;

/**
* The MySQL implementation of the team table class
*/

class TeamsMySQL extends Model implements TeamsInterface
{

    protected $table = 'teams';

    protected $primaryKey = 'team';

    public $incrementing = false;

}