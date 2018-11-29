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
      $store = store::where('user_id', $user_id)->first();
      // mencari contacts melalui store id
      $contacts = contact::all()->where('store_id', $store->id);
      $i = 0;

      foreach ($contacts as $contact) {
        $i++;
      }

      $subscription_num_users = subscription::find($store->subscription_id)->num_users;

      if ($i <= $subscription_num_users || $subscription_num_users == 0) {
        return $next($request);
      }
      // bila mau ditampilkan pesan error
      return redirect()
      ->route('subscription')
      ->with('alert', 'Quota Contact Has Exceeded Capacity, Please Upgrade Your Package');
      throw new \Exception("kuota contact telah melebihi kapasitas, silahkan upgrade paket");
    }
}
