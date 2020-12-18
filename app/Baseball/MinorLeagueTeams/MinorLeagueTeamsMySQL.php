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

    public $sortable = [
        'team',
        'class',
        'league',
        'affiliate',
        'state',
        'country',
        'founded',
        'player_count',
    ];

    protected $table = 'minor_league_teams';

    protected $guarded = ['id'];

    /**
     * The number of records to return for pagination.
     *
     * @var int
     */
    protected $perPage = 10;

    public function getPlayerCountAttribute()
    {
        return count($this->players);
    }

    public function getTeams($fields = null, $order_by = null)
    {
        if (! $fields) {
            return MinorLeagueTeamsMySQL::select('*')->sortable('team')->paginate();
        } else {
            $query = MinorLeagueTeamsMySQL::select($fields);
            if (isset($order_by)):
                foreach($order_by as $order):
                    $query->orderBY($order[0], $order[1]);
                endforeach;
            endif;
            return $query->get();
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
        return MinorLeagueTeamsMySQL::select('team')->whereIn('id', $ids)->orderByRaw("FIELD(id, {$preserve_order_ids})")->get()->toArray();
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

    /**
    * Sort null founded years to the bottom
    */
    public function foundedSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(founded), founded ' . $direction);
    }

    /**
    * Sort null founded years to the bottom
    */
    public function playerCountSortable($query, $direction)
    {
        // TODO use relationship?
        return $query->join('player_minor_league_teams', 'minor_league_teams.id', '=', 'player_minor_league_teams.mlt_id')
            ->groupBy('mlt_id')
            ->orderBy('player_count', $direction)
            ->select('minor_league_teams.*')
            ->selectRaw('COUNT(player_minor_league_teams.player_id) AS player_count');
    }

    public function players()
    {
        return MinorLeagueTeamsMySQL::belongsToMany('Artifacts\Baseball\Player\PlayerMySQL', 'player_minor_league_teams', 'mlt_id', 'player_id');
    }

    /**
     * Search
     *
     * @param string $q
     * @return array
     */
    public function search(string $q)
    {
        return MinorLeagueTeamsMySQL::select('minor_league_teams.*')
            ->where('team', 'LIKE', '%' . $q . '%')
            ->orWhere('city', 'LIKE', '%' . $q . '%')
            ->orWhere('other_names', 'LIKE', '%' . $q . '%')
            ->paginate()
            ->appends(['q' => $q])
            ->setPath('');
    }
}
