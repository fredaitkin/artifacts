<?php

namespace Artifacts\Player;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

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

	public function draftYearSortable($query, $direction) {
		return $query->orderByRaw('ISNULL(draft_year), draft_year ' . $direction);
    }

	public function draftRoundSortable($query, $direction) {
		return $query->orderByRaw('ISNULL(draft_round), draft_round ' . $direction);
    }

	public function draftPositionSortable($query, $direction) {
		return $query->orderByRaw('ISNULL(draft_position), draft_position ' . $direction);
    }

	public function debutYearSortable($query, $direction) {
		return $query->orderByRaw('ISNULL(debut_year), debut_year ' . $direction);
    }

}
