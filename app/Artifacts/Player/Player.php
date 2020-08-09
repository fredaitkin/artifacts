<?php

namespace Artifacts\Player;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Carbon\Carbon;

class Player extends Model
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
        'debut_year'
    ];

    /**
     * Get formatted birth date.
     *
     * @return string
     */
   public function getBirthDateDisplayAttribute() {
       return Carbon::parse($this->birthdate)->format('d/m/Y');
    }

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

    public function homeRunsSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(home_runs), home_runs ' . $direction);
    }

    public function eraSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(era), era ' . $direction);
    }

    public function winsSortable($query, $direction) {
        return $query->orderByRaw('ISNULL(wins), wins ' . $direction);
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
            foreach($data as $key => $value):
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
}
