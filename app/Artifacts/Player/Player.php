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
     * @param  string  $value
     * @return string
     */
    public function getBirthDateAttribute($value) {
        return Carbon::parse($value)->format('d-m-Y');
    }

	/**
     * Set formatted birth date.
     *
     * @param  string  $value
     * @return string
     */
    public function setBirthDateAttribute($value) {
        return Carbon::parse($value)->format('d-m-Y');
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
        // TODO handle multiple teams
        $teams = '';
        if (!empty($this->previous_teams)):
            $teams = config('teams')[$this->previous_teams];
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
}
