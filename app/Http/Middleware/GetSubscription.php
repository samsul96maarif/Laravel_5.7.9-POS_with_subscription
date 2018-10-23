<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Stosub;
use App\Models\Usestore;
use App\Models\Subscription;
use App\Models\Contact;
use App\Models\Constore;

use Auth;

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
        // $tes = constore::all();
        // dd($tes);
        $user_id = Auth::id();
        // dd($user_id);

        // user_stores
        $user_store = usestore::where('user_id', $user_id)->first();
        $store_id = $user_store->store_id;
        // dd($store_id);

        // store_subscription
        $store_subscription = stosub::where('store_id', $store_id)->first();
        // dd($stosub);

        if ($store_subscription == null) {
          return redirect('/subscription');
        }
        else {
          // mencari subscription id dari store
          $subscription_id = $store_subscription->subscription_id;
          // dd($subs_id);

          if ($subscription_id > 0) {
            // mencari contacts melalui store id
            $contacts = contact::all()->where('store_id', $store_id);
            $i = 0;
            // dd($contact_stores);
            foreach ($contacts as $contact) {
              $i++;
            }
            // dd($i);
            $subscription_num_users = subscription::find($subscription_id)->num_users;
            // dd($subscription_num_users);
            if ($i < $subscription_num_users) {
              return $next($request);
            }
            // bila mau ditampilkan pesan error
            throw new \Exception("kuota tambah contact telah melebihi kapasitas");
          }
        }
    }
}
