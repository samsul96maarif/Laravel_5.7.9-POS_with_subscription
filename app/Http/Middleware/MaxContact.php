<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Subscription;
use App\Models\Contact;
use App\Models\Store;

use Auth;

class MaxContact
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
      // mencari contacts melalui store id
      $contacts = contact::all()->where('store_id', $store->id);
      $i = 0;
      // dd($contacts);
      foreach ($contacts as $contact) {
        $i++;
      }
      // dd($i);
      $subscription_num_users = subscription::find($store->subscription_id)->num_users;
      // dd($subscription_num_users);
      if ($i < $subscription_num_users) {
        return $next($request);
      }
      // bila mau ditampilkan pesan error
      throw new \Exception("kuota contact telah melebihi kapasitas, silahkan upgrade paket");
    }
}
