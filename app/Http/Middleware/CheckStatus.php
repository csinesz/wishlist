<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
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
        $response = $next($request);

        if(Auth::check() && !Auth::user()->active){
            Auth::logout();
            return redirect(route('login'))->withErrors(['inactive'=>__('gui.inactive_user')]);
        }
        return $response;
    }
}
