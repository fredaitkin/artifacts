<?php

namespace Artifacts\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsTeam implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
            $teams = array_merge(config('teams.current'), config('teams.defunct'));
            $previous_teams = explode(',', $value);
            foreach($previous_teams as $team):
                if (!array_key_exists($team, $teams)):
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
        return 'The previous teams must be in ' . implode(', ', array_keys(config('teams')));
    }
}
