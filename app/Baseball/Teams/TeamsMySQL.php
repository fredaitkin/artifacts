<?php

namespace Artifacts\Baseball\Teams;

use Artifacts\Baseball\Teams\TeamsInterface;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Log;

/**
* The MySQL implementation of the team table class
*/

class TeamsMySQL extends Model implements TeamsInterface
{

    use Sortable;

    protected $table = 'teams';

    protected $primaryKey = 'team';

    protected $guarded = [];

    public $incrementing = false;

    public $sortable = [
        'name',
        'city',
        'state',
        'country',
        'founded',
    ];

    /**
     * The number of records to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    /**
     * Get teams
     * @param array $fields specific subset of team fields
     * @return mixed
     **/
    public function getTeams($fields = null, $active = true)
    {
        if (!$fields):
            $query = TeamsMySQL::select('*');
            if ($active):
                $query = $query->active();
            endif;
            return $query->sortable('name')->paginate();
        else:
            return TeamsMySQL::select($fields)->get()->toArray();
        endif;
    }

    public function scopeActive($query)
    {
        return $query->whereNull('closed');
    }

    public function relocatedToTeam()
    {
        return TeamsMySQL::belongsTo('Artifacts\Baseball\Teams\TeamsMySQL', 'relocated_to');
    }

    public function getRelocatedToDisplayAttribute()
    {
        return $this->relocatedToTeam->name ?? '';
    }

    public function relocatedFromTeam()
    {
        return TeamsMySQL::belongsTo('Artifacts\Baseball\Teams\TeamsMySQL', 'relocated_from');
    }

    public function getRelocatedFromDisplayAttribute()
    {
        return $this->relocatedFromTeam->name ?? '';
    }

    /**
     * Get current teams
     * @return array Team abbreviation/name collection
     **/
    public function getCurrentTeams()
    {
        $current_teams = [];
        $data = TeamsMySQL::select('team', 'name')->whereNull('closed')->get();
        foreach($data as $d):
            $current_teams[$d->team] = $d->name;
        endforeach;
        return $current_teams;
    }

    /**
     * Get team by code
     *
     * @return array
     */
    public function getTeamByCode(string $code)
    {
        return TeamsMySQL::findOrFail($code);
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
        return TeamsMySQL::updateOrCreate($keys, $fields);
    }

}