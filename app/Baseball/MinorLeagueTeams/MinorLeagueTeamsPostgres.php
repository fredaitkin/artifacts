<?php

/**
* The PostgreSQL implementation of the minor league table class
*
*/

namespace Artifacts\Baseball\MinorLeagueTeams;

use Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsInterface;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class MinorLeagueTeamsPostgres extends Model implements MinorLeagueTeamsInterface
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

    public function getTeams()
    {
        return MinorLeagueTeamsPostgres::select('*')->sortable('team')->paginate();
    }

    public function addTeam(string $team)
    {
        MinorLeagueTeamsPostgres::create(['team' => $team]);
    }

    /**
     * Get team by id
     *
     * @return array
     */
    public function getTeamByID(int $id)
    {
        return MinorLeagueTeamsPostgres::findOrFail($id);
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
        return MinorLeagueTeamsPostgres::updateOrCreate($keys, $fields);
    }

    public function classSortable($query, $direction)
    {
        return $query->orderByRaw("CASE
            WHEN class='Triple-A' THEN 1
            WHEN class='Double-A' THEN 2
            WHEN class='Class A - Advanced' THEN 3
            WHEN class='Class A' THEN 4
            WHEN class='Class A Short Season' THEN 5
            WHEN class='Rookie Advanced' THEN 6
            WHEN class='Rookie' THEN 7
            ELSE 8
          END " . $direction);
    }

    /**
    * Sort null affiliates to the bottom
    */
    public function affiliateSortable($query, $direction)
    {
        return $query->orderByRaw('COALESCE(affiliate), affiliate ' . $direction);
    }
}