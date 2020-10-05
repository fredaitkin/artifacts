<?php

namespace Artifacts\Player;

use Artifacts\Player\PlayerInterface;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Player extends Model implements PlayerInterface
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
        'at_bats',
        'home_runs',
        'rbis',
        'era',
        'games',
        'wins',
        'losses',
        'saves',
    ];

    protected $guarded = [];

    /**
     * Get formatted birth date.
     *
     * @return string
     */
   public function getBirthDateDisplayAttribute() {
       return Carbon::parse($this->birthdate)->format('d/m/Y');
    }

   /**
    * Sort on first and last name
    */
    public function lastNameSortable($query, $direction) {
        return $query->orderBy('last_name', $direction)->orderBy('first_name', 'ASC');
    }

    /**
    * Sort null draft years to the bottom
    */
    public function draftYearSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(draft_year), draft_year ' . $direction);
    }

    public function draftRoundSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(draft_round), draft_round+0 ' . $direction);
    }

    public function draftPositionSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(draft_position), draft_position ' . $direction);
    }

    public function debutYearSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(debut_year), debut_year ' . $direction);
    }

    public function positionSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(position), position ' . $direction);
    }

    public function averageSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(average), average ' . $direction);
    }

    public function atBatsSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(at_bats), at_bats ' . $direction);
    }

    public function homeRunsSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(home_runs), home_runs ' . $direction);
    }

    public function rbisSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(rbis), rbis ' . $direction);
    }

    public function eraSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(era), era ' . $direction);
    }

    public function gamesSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(games), games ' . $direction);
    }

    public function winsSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(wins), wins ' . $direction);
    }

    public function lossesSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(losses), losses ' . $direction);
    }

    public function savesSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(saves), saves ' . $direction);
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
     * Get the team display name from abbreviation
     *
     * @return string
     */
    public function getTeamDisplayAttribute()
    {
        return config('teams')[$this->team];
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
            $data = explode(',', $this->previous_teams);
            foreach ($data as $key => $value):
              $data[$key] = config('teams')[$value];
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
        $query = Player::select('id', 'first_name', 'last_name', 'team', 'home_runs')
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
        $query = Player::select('id', 'first_name', 'last_name', 'team', 'rbis')
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
        $query = Player::select('id', 'first_name', 'last_name', 'team', 'average')
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
        $query = Player::selectRaw('id, first_name, last_name, team, round(at_bats/home_runs, 2) as strike_rate')
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
        $query = Player::selectRaw('id, first_name, last_name, team, round(at_bats/rbis, 2) as strike_rate')
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
        $query = Player::select('id', 'first_name', 'last_name', 'team', 'wins')
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
        $query = Player::select('id', 'first_name', 'last_name', 'team', 'era')
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
        $query = Player::selectRaw('id, first_name, last_name, team, round(games/wins, 2) as strike_rate')
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

}