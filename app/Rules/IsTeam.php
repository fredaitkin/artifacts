<?php

namespace Artifacts\Rules;

use Illuminate\Contracts\Validation\Rule;
use Artifacts\Baseball\Teams\TeamsMySQL as Teams;

class IsTeam implements Rule
{

    /**
     * The Teams as abbreviations
     *
     * @var array
     */
    private $team_abbreviations;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $teams = new Teams();
        $this->team_abbreviations = array_column($teams->getTeams(['team']), 'team');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!empty($value)):
            $previous_teams = explode(', ', trim($value));
            foreach($previous_teams as $team):
                if (!in_array($team, $this->team_abbreviations)):
                    return false;
                endif;
            endforeach;
        endif;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The previous teams must be in ' . implode(', ', $this->team_abbreviations);
    }
}
