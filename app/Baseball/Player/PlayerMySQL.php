<?php

namespace Artifacts\Baseball\Player;

use Artifacts\Baseball\Player\PlayerInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class PlayerMySQL extends Model implements PlayerInterface
{

    use Sortable;

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

    protected $table = 'players';

    protected $primaryKey = 'id';

    protected $guarded = ['id'];

    /**
     * The number of records to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;


    public function teams()
    {
        // TODO refactor into one function?
        return PlayerMySQL::belongsToMany('Artifacts\Baseball\Teams\TeamsMySQL', 'player_previous_teams', 'player_id', 'team');
    }

    public function minor_teams()
    {
        return PlayerMySQL::belongsToMany('Artifacts\Baseball\MinorLeagueTeams\MinorLeagueTeamsMySQL', 'player_minor_league_teams', 'player_id', 'mlt_id');
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
     * Get the state from abbreviations
     *
     * @return string
     */
    public function getStateDisplayAttribute()
    {
        $state = '';
        if (! empty($this->state)):
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
     * Get the previous teams from pivot table
     * @return string
     */
    public function getPreviousTeamsAttribute()
    {
        $teams = '';
        foreach($this->teams as $t):
            $teams .= $t->team . ', ';
        endforeach;
        return rtrim(trim($teams), ',');
    }

    /**
     * Get the previous teams from pivot table
     * @return string
     */
    public function getPreviousTeamsDisplayAttribute()
    {
        $teams = '';
        foreach($this->teams as $t):
            $teams .= $t->name . ', ';
        endforeach;
        return rtrim(trim($teams), ',');
    }

    /**
     * Get the previous teams as an array from pivot table
     * @return string
     */
    public function getPreviousTeamsArrayAttribute()
    {
        $teams = [];
        foreach($this->teams as $t):
            $teams[] = $t->team;
        endforeach;
        return $teams;
    }

   /**
     * Get the minor league teams
     *
     * @return string
     */
    public function getMinorLeagueTeamsAttribute()
    {
        $teams = '';
        foreach($this->minor_teams as $t):
            $teams .= $t->id . ',';
        endforeach;
        return rtrim(trim($teams), ',');
        return $teams;
    }

   /**
     * Get the minor league team names
     *
     * @return string
     */
    public function getMinorLeagueTeamsDisplayAttribute()
    {
        $teams = '';
        foreach($this->minor_teams as $t):
            $teams .= $t->team . ', ';
        endforeach;
        return rtrim(trim($teams), ',');
        return $teams;
    }

    /**
     * Get the minor league teams as an array from pivot table
     * @return string
     */
    public function getMinorLeagueTeamsArrayAttribute()
    {
        $teams = [];
        foreach($this->minor_teams as $t):
            $teams[] = $t->id;
        endforeach;
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

    /**
    * Sort on first and last name
    */
    public function lastNameSortable($query, $direction)
    {
        return $query->orderBy('last_name', $direction)->orderBy('first_name', 'ASC');
    }

    /**
    * Sort null draft years to the bottom
    */
    public function draftYearSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(draft_year), draft_year ' . $direction);
    }

    public function draftRoundSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(draft_round), draft_round+0 ' . $direction);
    }

    public function draftPositionSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(draft_position), draft_position ' . $direction);
    }

    public function debutYearSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(debut_year), debut_year ' . $direction);
    }

    public function positionSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(position), position ' . $direction);
    }

    public function averageSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(average), average ' . $direction);
    }

    public function atBatsSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(at_bats), at_bats ' . $direction);
    }

    public function homeRunsSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(home_runs), home_runs ' . $direction);
    }

    public function rbisSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(rbis), rbis ' . $direction);
    }

    public function eraSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(era), era ' . $direction);
    }

    public function gamesSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(games), games ' . $direction);
    }

    public function winsSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(wins), wins ' . $direction);
    }

    public function lossesSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(losses), losses ' . $direction);
    }

    public function savesSortable($query, $direction)
    {
        return $query->orderByRaw('ISNULL(saves), saves ' . $direction);
    }

    public function getMostHomeRuns(array $where = null)
    {
        $query = PlayerMySQL::select('id', 'first_name', 'last_name', 'team', 'home_runs')
            ->whereNotNull('home_runs')
            ->orderBy('home_runs', 'DESC');
        if (isset($where)):
            foreach ($where as $field => $value):
                $query->where([$field => $value]);
            endforeach;
        endif;

        return $query->first();
    }

    /**
     * Get all players
     *
     * @return array
     */
    public function getAllPlayers()
    {
        return PlayerMySQL::all();
    }

    /**
     * Get tabulated players
     *
     * @return array
     */
    public function getTabulatedPlayers()
    {
        return PlayerMySQL::sortable()->paginate();
    }

    /**
     * Get player by id
     *
     * @return array
     */
    public function getPlayerByID(int $id)
    {
        return PlayerMySQL::findOrFail($id);
    }

    /**
     * Get players by ids
     *
     * @return array
     */
    public function getPlayersByIDs(array $ids)
    {
        return PlayerMySQL::whereIn('id', $ids)->get();
    }

    /**
     * Get player by mlb link
     *
     * @return array
     */
    public function getPlayerByLink(string $link)
    {
        return PlayerMySQL::select('*')->where('mlb_link', $link)->get();
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
            return PlayerMySQL::create($fields);
        else:
            return new PlayerMySQL;
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
        return PlayerMySQL::updateOrCreate($keys, $fields);
    }

    /**
     * Delete a player
     *
     * @param string $id
     */
    public function deleteByID(int $id)
    {
        PlayerMySQL::findOrFail($id)->delete();
    }

    /**
     * Search
     *
     * @param string $q
     * @return array
     */
    public function search(string $q)
    {
        return PlayerMySQL::select('players.*')
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

    public function getMostRBIs(array $where = null)
    {
        $query = PlayerMySQL::select('id', 'first_name', 'last_name', 'team', 'rbis')
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
        $query = PlayerMySQL::select('id', 'first_name', 'last_name', 'team', 'average')
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
        $query = PlayerMySQL::selectRaw('id, first_name, last_name, team, round(at_bats/home_runs, 2) as strike_rate')
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
        $query = PlayerMySQL::selectRaw('id, first_name, last_name, team, round(at_bats/rbis, 2) as strike_rate')
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
        $query = PlayerMySQL::select('id', 'first_name', 'last_name', 'team', 'wins')
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
        $query = PlayerMySQL::select('id', 'first_name', 'last_name', 'team', 'era')
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
        $query = PlayerMySQL::selectRaw('id, first_name, last_name, team, round(games/wins, 2) as strike_rate')
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
        return PlayerMySQL::selectRaw('city, country, state, count(*) as count')
            ->groupBy('city')
            ->groupBy('country')
            ->groupBy('state')
            ->orderBy('count', 'DESC')
            ->orderBy('city', 'ASC')
            ->get();
    }

}
