<?php

namespace App\Http\Middleware;

use Closure;

class AccesViaAjax
{
    /**
     * Enable ajax request only.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->ajax()) {
	        return response('Forbidden.', 403);
        }
        return $next($request);
    }
}
