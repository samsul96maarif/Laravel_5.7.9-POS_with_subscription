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
      // dd($user_id);
      // mencari store yang milik user
      $store = store::where('user_id', $user_id)->first();
      // dd($store);

      if ($store == null) {
        // dd($store);
        return redirect('/create');
        // return view('auth/login');
      }

      // dd($store);
      return $next($request);
    }
}
