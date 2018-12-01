<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Organization;

class Gate
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
      $user_id = Auth::id();

      // mencari organization yang milik user
      $organization = organization::where('user_id', $user_id)->first();

      // bila belum memiliki organization diarahkan ke halaman create
      if ($organization == null) {

        return redirect('/create')->withSuccess('Please Fill Company Profile First.');
      }

      return $next($request);
    }
}
