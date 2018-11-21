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
        $store = store::where('user_id', $user_id)->first();
        // memeriksa apakah sudah memiliki subsription Package
        if ($store->subscription_id == null) {
          return redirect('/subscription')->withSuccess('please subscribe first.');
        }
        else {
          // memeriksa apakah statusnya 'true'
          // bila false maka akan diarahkan ke compleate a payment
            if ($store->status == false) {
              return redirect('/subscription/cart')->withSuccess('please compleate a payment.');
              throw new \Exception("masih menunggu konfirmasi pembayaran");
            } else {
              // memeriksa apakah package belum expied
              $now = carbon::now();
              if ($store->expire_date > $now) {
                return $next($request);
              }
              return redirect('/subscription')->withSuccess('your package was expired, please subscrib again.');
              throw new \Exception("subscription expired");

            }
        }
    }
}
