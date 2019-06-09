<?php

namespace Artifacts\Player;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Player extends Model
{

	use Sortable;

    protected $table = 'players';

	public $sortable = [
		'team',
		'city',
		'state',
		'country',
		'draft_year',
		'draft_round',
		'draft_position'
	];
}
