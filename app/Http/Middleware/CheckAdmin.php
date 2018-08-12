<?php

namespace App\Http\Middleware;

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
        if(Auth::check() && Auth::user()->admin == true)
        {
            return $next($request);
        }
        if(Auth::user()->admin == null)
        {
            Auth::user()->admin = false;
            Auth::user()->save();
        }
        return abort(403, __('You do not have the permission to view this page.'));
    }
}
