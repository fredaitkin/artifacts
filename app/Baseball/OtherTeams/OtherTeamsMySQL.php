<?php

namespace Artifacts\Baseball\OtherTeams;

use Artifacts\Baseball\OtherTeams\OtherTeamsInterface;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

/**
* The MySQL implementation of the other teams table class
*/

class OtherTeamsMySQL extends Model implements OtherTeamsInterface
{

    use Sortable;

    public $sortable = [
        'name',
        'league',
        'country',
        'founded',
        'player_count',
    ];

    protected $table = 'other_teams';

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
            return OtherTeamsMySQL::select('*')->sortable('name')->paginate();
        } else {
            $query = OtherTeamsMySQL::select($fields);
            if (isset($order_by)):
                foreach($order_by as $order):
                    $query->orderBY($order[0], $order[1]);
                endforeach;
            endif;
            return $query->get();
        }
    }

    /**
     * Get team by id
     *
     * @return array
     */
    public function getTeamByID(int $id)
    {
        return OtherTeamsMySQL::findOrFail($id);
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
        return OtherTeamsMySQL::updateOrCreate($keys, $fields);
    }

    /**
    * Sort null founded years to the bottom
    */
    public function playerCountSortable($query, $direction)
    {
        return $query->join('player_other_teams', 'other_teams.id', '=', 'player_other_teams.other_teams_id')
            ->groupBy('other_teams_id')
            ->orderBy('player_count', $direction)
            ->select('other_teams.*')
            ->selectRaw('COUNT(player_other_teams.player_id) AS player_count');
    }

    public function players()
    {
        return OtherTeamsMySQL::belongsToMany('Artifacts\Baseball\Player\PlayerMySQL', 'player_other_teams', 'other_teams_id', 'player_id');
    }
    /**
     * Search
     *
     * @param string $q
     * @return array
     */
    public function search(string $q)
    {
        return OtherTeamsMySQL::select('other_teams.*')
            ->where('name', 'LIKE', '%' . $q . '%')
            ->orWhere('city', 'LIKE', '%' . $q . '%')
            ->orWhere('country', 'LIKE', '%' . $q . '%')
            ->paginate()
            ->appends(['q' => $q])
            ->setPath('');
    }

}
