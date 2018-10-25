<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Subscription;
use App\Models\Store;

use Auth;
use Carbon\Carbon;

class GetSubscription
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
        $store = store::where('user_id', $user_id)->first();

        // dd($store->subscription_id);
        if ($store->subscription_id == null) {
          return redirect('/subscription');
        }
        else {
          // dd($store->id);
          // dd($store->status);
            if ($store->status == false) {
              throw new \Exception("masih menunggu konfirmasi pembayaran");
            } else {
              $now = carbon::now();
              if ($store->expire_date > $now) {
                // dd($store->expire_date);
                return $next($request);
              }
              throw new \Exception("subscription expired");
            }
        }
    }
}
