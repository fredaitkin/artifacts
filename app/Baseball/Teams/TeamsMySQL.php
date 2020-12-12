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
        'state',
        'league',
        'division',
        'founded',
        'titles_count'
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
        $relocated = null;
        if (!empty($this->relocatedToTeam->name)):
            $relocated = [$this->relocatedToTeam->team, $this->relocatedToTeam->name];
        endif;
        return $relocated;
    }

    public function relocatedFromTeam()
    {
        return TeamsMySQL::belongsTo('Artifacts\Baseball\Teams\TeamsMySQL', 'relocated_from');
    }

    public function getRelocatedFromDisplayAttribute()
    {
        $relocated = null;
        if (!empty($this->relocatedFromTeam->name)):
            $relocated = [$this->relocatedFromTeam->team, $this->relocatedFromTeam->name];
        endif;
        return $relocated;
    }

    public function getTitlesDisplayAttribute()
    {
        return implode(',', unserialize($this->titles));
    }

    public function getTitleCountAttribute()
    {
        $count = 0;
        if (!empty($this->titles)):
            $count = count(unserialize($this->titles));
        endif;
        return $count;
    }

    public function titleCountSortable($query, $direction)
    {
        return $query->orderByRaw('CHAR_LENGTH(titles) ' . $direction);
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

    /**
     * Get world series winners
     * @return array Year/team collection
     **/
    public function getWorldSeriesWinners()
    {
        $winners = [];
        $teams = TeamsMySQL::select('name', 'titles')->get();
        foreach($teams as $team):
            if (!empty($team->titles)):
                $titles = unserialize($team->titles);
                foreach($titles as $year):
                    $winners[$year] = $team->name;
                endforeach;
            endif;
        endforeach;
        ksort($winners);
        return $winners;
    }
}