<?php

namespace Artifacts\Baseball\MinorLeagueTeams;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
* The MySQL implementation of the minor league table class
*/

class MinorLeagueTeamsMySQL extends Model implements MinorLeagueTeamsInterface
{

    use Sortable;

    protected $table = 'minor_league_teams';

    protected $guarded = ['id'];

    public $sortable = [
        'team',
        'class',
        'league',
        'affiliate',
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

    public function getTeams($fields = null)
    {
        if (!$fields) {
            return MinorLeagueTeamsMySQL::select('*')->sortable('team')->paginate();
        } else {
            return MinorLeagueTeamsMySQL::select($fields)->get();
        }
    }

    public function addTeam(string $team)
    {
        MinorLeagueTeamsMySQL::create(['team' => $team]);
    }

    /**
     * Get team by id
     *
     * @return array
     */
    public function getTeamByID(int $id)
    {
        return MinorLeagueTeamsMySQL::findOrFail($id);
    }

    /**
     * Get minor league teams a player was in
     *
     * @param array $ids Ids of minor league teams
     * @return array
     */
    public function getPlayerTeams($ids)
    {
        $preserve_order_ids = implode(',', $ids);
        return MinorLeagueTeamsMySQL::select('team')->whereIn('id', $ids)->orderByRaw("FIELD(id, $preserve_order_ids)")->get()->toArray();
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
        return MinorLeagueTeamsMySQL::updateOrCreate($keys, $fields);
    }

    public function classSortable($query, $direction)
    {
        return $query->orderByRaw('FIELD(class, "Triple-A", "Double-A", "Class A - Advanced", "Class A", "Class A Short Season", "Rookie Advanced", "Rookie") ' . $direction);
    }

    /**
    * Sort null affiliates to the bottom
    */
    public function affiliateSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(affiliate), affiliate ' . $direction);
    }
}