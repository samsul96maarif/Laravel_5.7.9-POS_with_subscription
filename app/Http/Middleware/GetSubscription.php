<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Subscription;
use App\Models\Organization;
use App\Models\User;

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
        $user = Auth::user();

        if ($user->role == 1) {
          $organization = organization::where('user_id', $user->id)->first();

          // memeriksa apakah sudah memiliki subsription Package
          if ($organization->subscription_id == null) {
            return redirect('/subscriptions')->withSuccess('Please Subscribe First.');
          } else {
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
            // if ($organization->status == false) { else
            }
          // if ($organization->subscription_id == null) { else
          }
          // if role == 1
        } else {
          $organization = organization::findOrFail($user->organization_id);

          // memeriksa apakah sudah memiliki subsription Package
          if ($organization->subscription_id == null) {

            return redirect('/')
            // ->route('employe.sales.orders', ['id' => $user->id])
            ->with('alert', 'Your Organization Does Not Have Subscription Package');

          } else {
            // memeriksa apakah statusnya 'true'
            // bila false maka akan diarahkan ke compleate a payment
            if ($organization->status == false) {
              return redirect('/')
              // ->route('employe.sales.orders', ['id' => $user->id])
              ->with('alert', 'Your Organization Not Yet Compleate a Payment');

              throw new \Exception("masih menunggu konfirmasi pembayaran");
            } else {
              // memeriksa apakah package belum expied
              $now = carbon::now();
              if ($organization->expire_date > $now) {

                $users = user::all()->where('organization_id', $organization->id);
                $i = 0;
                foreach ($users as $value) {
                  $i++;
                }

                $subscription = subscription::findOrFail($organization->subscription_id);

                if ($i <= $subscription->num_users || $subscription->num_users === null) {
                  return $next($request);
                }
                return redirect('/')
                // ->route('employe.sales.orders', ['id' => $user->id])
                ->with('alert', 'Quota Users Has Exceeded Capacity, Please Upgrade Your Package');

              }
              return redirect('/')
              // ->route('employe.sales.orders', ['id' => $user->id])
              ->with('alert', 'Package Subscription Your Organozation Was Expired');

              throw new \Exception("subscription expired");
            // if ($organization->status == false) { else
            }
          // if ($organization->subscription_id == null) { else
          }
        // if role==1 else
        }
    }
}
