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
}
