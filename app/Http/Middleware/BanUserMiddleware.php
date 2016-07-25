<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BanUserMiddleware
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
        // baned user
        if(Auth::user() && Auth::user()->authGroup_id == 9) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Ban User.', 401);
            } else {
                Auth::logout();
                return redirect('/login')
                    ->with('ban_user', 'User is baned by admin, please contact our support team : ticket@nusding.info');
            }
        }

        return $next($request);
    }
}
