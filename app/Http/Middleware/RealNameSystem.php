<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RealNameSystem
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
        // require real name
        if (Auth::user() && Auth::user()->authGroup_id == 8) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Real-Name System Required.', 405);
            } else {
                return redirect('/settings/account');
            }
        }

        return $next($request);
    }
}
