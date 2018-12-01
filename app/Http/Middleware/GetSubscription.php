<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Subscription;
use App\Models\Organization;

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
        $organization = organization::where('user_id', $user_id)->first();
        // memeriksa apakah sudah memiliki subsription Package
        if ($organization->subscription_id == null) {
          return redirect('/subscriptions')->withSuccess('Please Subscribe First.');
        }
        else {
          // memeriksa apakah statusnya 'true'
          // bila false maka akan diarahkan ke compleate a payment
            if ($organization->status == false) {
              return redirect('/subscription/cart')->withSuccess('Please Compleate a Payment.');
              throw new \Exception("masih menunggu konfirmasi pembayaran");
            } else {
              // memeriksa apakah package belum expied
              $now = carbon::now();
              if ($organization->expire_date > $now) {
                return $next($request);
              }
              return redirect('/subscriptions')->with('alert', 'Your Package Was Expired, Please Subscribe Again.');
              throw new \Exception("subscription expired");

            }
        }
    }
}
