<?php

namespace Artifacts\Http\Middleware;

use Closure;

class ArtifactsAuth
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
        // TODO Add authentication check
        return $next($request);
    }
}
