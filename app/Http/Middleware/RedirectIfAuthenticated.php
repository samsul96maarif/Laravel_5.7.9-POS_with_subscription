<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\user;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
          // periksa apakah admin
            if (Auth::user()->isAdmin()) {
              return redirect('/admin');
            }
            // return redirect('/user');
            return redirect('/home');
        }

        return $next($request);
    }
}
