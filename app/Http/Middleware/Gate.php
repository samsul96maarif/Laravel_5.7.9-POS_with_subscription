<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Usestore;
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
      // $user_id = Auth::user()->id;
      $user_id = Auth::id();
    // dd($user_id);
    $usestore = usestore::where('user_id', $user_id)->first();
    // dd($usestore);

      if ($usestore == null) {
        // dd($tes);
        return redirect('/create');
        // return view('auth/login');
      }

      $store_id = $usestore->store_id;
      $store = store::where('id', $store_id)->first();
      // dd($store);
      // return view('index', ['store' => $store]);
      return $next($request);
    }
}
