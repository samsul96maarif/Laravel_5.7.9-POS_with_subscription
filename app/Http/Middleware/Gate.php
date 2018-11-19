<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Store;

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

      // mencari store yang milik user
      $store = store::where('user_id', $user_id)->first();

      // bila belum memiliki store diarahkan ke halaman create
      if ($store == null) {

        return redirect('/create');
      }

      return $next($request);
    }
}
