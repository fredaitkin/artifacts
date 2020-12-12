<?php

namespace Artifacts\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->validateAccess($request)):
           abort(403);
        endif;

        return $next($request);
    }

    /**
    * Some routes are only available to the administrator
    * @return bool
    */
    private function validateAccess($request)
    {
        if ('player.read' === $request->route()->getName() && 'true' === $request->get('view')):
            return true;
        endif;

        return (Auth::user()->id === 1);
    }

}
