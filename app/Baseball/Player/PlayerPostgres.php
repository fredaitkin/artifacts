<?php

namespace Artifacts\Baseball\Player;

use Artifacts\Baseball\Player\PlayerInterface;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class PlayerPostgres extends Model implements PlayerInterface
{

    use Sortable;

    protected $table = 'players';

    public $sortable = [
        'first_name',
        'last_name',
        'team',
        'city',
        'state',
        'country',
        'birthdate',
        'draft_year',
        'draft_round',
        'draft_position',
        'debut_year',
        'position',
        'average',
        'hits',
        'at_bats',
        'home_runs',
        'runs',
        'rbis',
        'stolen_bases',
        'obp',
        'ops',
        'era',
        'games',
        'wins',
        'losses',
        'saves',
        'innings_pitched',
        'strike_outs',
        'whip',
    ];

    protected $guarded = ['id'];

    /**
     * The number of records to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;

    /**
     * Get all players
     *
     * @return array
     */
    public function getAllPlayers()
    {
        return PlayerPostgres::all();
    }

    /**
     * Get tabulated players
     *
     * @return array
     */
    public function getTabulatedPlayers()
    {
        return PlayerPostgres::sortable()->paginate();
    }

    /**
     * Get player by id
     *
     * @return array
     */
    public function getPlayerByID(int $id)
    {
        return PlayerPostgres::findOrFail($id);
    }

    /**
     * Get players by ids
     *
     * @return array
     */
    public function getPlayersByIDs(array $ids)
    {
        return PlayerPostgres::whereIn('id', $ids)->get();
    }

    /**
     * Get player by mlb link
     *
     * @return array
     */
    public function getPlayerByLink(string $link)
    {
        return PlayerPostgres::select('*')->where('mlb_link', $link)->get();
    }

    /**
     * Create player
     *
     * @param array $fields
     * @return object
     */
    public function create(array $fields = null)
    {
        if ($fields):
            return PlayerPostgres::create($fields);
        else:
            return new PlayerPostgres;
        endif;
    }

    /**
     * Update or create player
     *
     * @param array $keys
     * @param array $fields
     * @return object
     */
    public function updateCreate(array $keys, array $fields)
    {
        return PlayerPostgres::updateOrCreate($keys, $fields);
    }

    /**
     * Delete a player
     *
     * @param string $id
     */
    public function deleteByID(int $id)
    {
        PlayerPostgres::findOrFail($id)->delete();
    }

    /**
     * Search
     *
     * @param string $q
     * @return array
     */
    public function search(string $q)
    {
        return PlayerPostgres::select('players.*')
            ->where('team', 'LIKE', '%' . $q . '%')
            ->orWhere('city', 'LIKE', '%' . $q . '%')
            ->orWhere('first_name', 'LIKE', '%' . $q . '%')
            ->orWhere('last_name', 'LIKE', '%' . $q . '%')
            ->orWhere('state', 'LIKE', '%' . $q . '%')
            ->orWhere('country', 'LIKE', '%' . $q . '%')
            ->orWhere('draft_year', 'LIKE', '%' . $q . '%')
            ->orWhere('draft_round', 'LIKE', '%' . $q . '%')
            ->orWhere('debut_year', 'LIKE', '%' . $q . '%')
            ->paginate()
            ->appends(['q' => $q])
            ->setPath('');
    }

    /**
     * Get formatted birth date.
     *
     * @return string
     */
    public function getBirthDateDisplayAttribute()
    {
       return Carbon::parse($this->birthdate)->format('d/m/Y');
    }

    /**
    * Sort on first and last name
    */
    public function lastNameSortable($query, $direction)
    {
        return $query->orderBy('last_name', $direction)->orderBy('first_name', 'ASC');
    }

    public function draftRoundSortable($query, $direction)
    {
        return $query->orderByRaw('draft_round '. $direction . ' NULLS LAST');
    }

    public function draftPositionSortable($query, $direction)
    {
        return $query->orderByRaw('draft_position '. $direction . ' NULLS LAST');
    }

    public function debutYearSortable($query, $direction)
    {
        return $query->orderByRaw('debut_year '. $direction . ' NULLS LAST');
    }

    public function positionSortable($query, $direction)
    {
        return $query->orderByRaw('position '. $direction . ' NULLS LAST');
    }

    public function averageSortable($query, $direction)
    {
        return $query->orderByRaw('average '. $direction . ' NULLS LAST');
    }

    public function atBatsSortable($query, $direction)
    {
        return $query->orderByRaw('at_bats '. $direction . ' NULLS LAST');
    }

    public function homeRunsSortable($query, $direction)
    {
        return $query->orderByRaw('home_runs '. $direction . ' NULLS LAST');
    }

    public function rbisSortable($query, $direction)
    {
        return $query->orderByRaw('rbis '. $direction . ' NULLS LAST');
    }

    public function eraSortable($query, $direction)
    {
        return $query->orderByRaw('era '. $direction . ' NULLS LAST');
    }

    public function gamesSortable($query, $direction)
    {
        return $query->orderByRaw('games '. $direction . ' NULLS LAST');
    }

    public function winsSortable($query, $direction)
    {
        return $query->orderByRaw('wins '. $direction . ' NULLS LAST');
    }

    public function lossesSortable($query, $direction)
    {
        return $query->orderByRaw('losses '. $direction . ' NULLS LAST');
    }

    public function savesSortable($query, $direction)
    {
        return $query->orderByRaw('saves '. $direction . ' NULLS LAST');
    }

    public function strikeOutsSortable($query, $direction)
    {
        return $query->orderByRaw('strike_outs '. $direction . ' NULLS LAST');
    }

    public function inningsPitchedSortable($query, $direction)
    {
        return $query->orderByRaw('innings_pitched '. $direction . ' NULLS LAST');
    }

    public function whipSortable($query, $direction)
    {
        return $query->orderByRaw('whip '. $direction . ' NULLS LAST');
    }

    /**
     * Get the state from abbreviations
     *
     * @return string
     */
    public function getStateDisplayAttribute()
    {
        $state = '';
        if (!empty($this->state)):
              $state = config('states')[$this->state];
        endif;
        return $state;
    }

    /**
     * Get the position from abbreviations
     *
     * @return string
     */
    public function getPositionDisplayAttribute()
    {
        return config('positions')[$this->position];
    }

    /**
     * Get the previous team/s display name/s from abbreviations
     *
     * @return string
     */
    public function getPreviousTeamsDisplayAttribute()
    {
        $teams = '';
        if (!empty($this->previous_teams)):
            $data = unserialize($this->previous_teams);
            foreach ($data as $key => $value):
                if (isset(config('teams.current')[$value])):
                    $data[$key] = config('teams.current')[$value];
                elseif (isset(config('teams.defunct')[$value])):
                    $data[$key] = config('teams.defunct')[$value];
                endif;
            endforeach;
            $teams = implode(', ', $data);
        endif;
        return $teams;
    }

    /**
     * Get player age from birthdate
     *
     * @return string
     */
    public function getAgeAttribute()
    {
        return Carbon::parse($this->birthdate)->age;
    }

    /**
     * Get regular photo
     *
     * @return string
     */
    public function getRegularPhotoAttribute()
    {
        $photos = @unserialize($this->photo);
        return $photos['regular'] ?? '';
    }

    /**
     * Get small photo
     *
     * @return string
     */
    public function getSmallPhotoAttribute()
    {
        $photos = @unserialize($this->photo);
        return $photos['small'] ?? '';
    }

    public function getMostHomeRuns(array $where = null)
    {
        $query = PlayerPostgres::select('id', 'first_name', 'last_name', 'team', 'home_runs')
            ->whereNotNull('home_runs')
            ->orderBy('home_runs', 'DESC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getMostRBIs(array $where = null)
    {
        $query = PlayerPostgres::select('id', 'first_name', 'last_name', 'team', 'rbis')
            ->whereNotNull('rbis')
            ->orderBy('rbis', 'DESC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getBestAverage(array $where = null)
    {
        $query = PlayerPostgres::select('id', 'first_name', 'last_name', 'team', 'average')
            ->whereNotNull('average')
            ->where('at_bats', '>', 500)
            ->orderBy('average', 'DESC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getBestHomeRunStrikeRate(array $where = null)
    {
        $query = PlayerPostgres::selectRaw('id, first_name, last_name, team, round(at_bats/home_runs, 2) as strike_rate')
            ->whereNotNull('home_runs')
            ->whereNotNull('at_bats')
            ->where('home_runs', '>', 0)
            ->where('at_bats', '>', 500)
            ->orderBy('strike_rate', 'ASC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getBestRBIStrikeRate(array $where = null)
    {
        $query = PlayerPostgres::selectRaw('id, first_name, last_name, team, round(at_bats/rbis, 2) as strike_rate')
            ->whereNotNull('at_bats')
            ->where('at_bats', '>', 500)
            ->orderBy('strike_rate', 'ASC');
        if (isset($where)):
            foreach($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getMostWins(array $where = null)
    {
        $query = PlayerPostgres::select('id', 'first_name', 'last_name', 'team', 'wins')
            ->whereNotNull('wins')
            ->orderBy('wins', 'DESC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getBestERA(array $where = null)
    {
        $query = PlayerPostgres::select('id', 'first_name', 'last_name', 'team', 'era')
            ->whereNotNull('era')
            ->where('games', '>', 100)
            ->where('wins', '>', 50)
            ->orderBy('era', 'ASC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getBestWinStrikeRate(array $where = null)
    {
        $query = PlayerPostgres::selectRaw('id, first_name, last_name, team, round(games/wins, 2) as strike_rate')
            ->where('games', '>', 100)
            ->where('wins', '>', 0)
            ->orderBy('strike_rate', 'ASC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    public function getPlayerCityCount()
    {
        return PlayerPostgres::selectRaw('city, country, state, count(*) as count')
            ->groupBy('city')
            ->groupBy('country')
            ->groupBy('state')
            ->orderBy('count', 'DESC')
            ->orderBy('city', 'ASC')
            ->get();
    }

}