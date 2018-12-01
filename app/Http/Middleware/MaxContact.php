<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Subscription;
use App\Models\Contact;
use App\Models\Organization;

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
      $organization = organization::where('user_id', $user_id)->first();
      // mencari contacts melalui organization id
      $contacts = contact::all()->where('organization_id', $organization->id);
      $i = 0;

      foreach ($contacts as $contact) {
        $i++;
      }

      $subscription_num_users = subscription::find($organization->subscription_id)->num_users;

      if ($i <= $subscription_num_users || $subscription_num_users == 0) {
        return $next($request);
      }
      // bila mau ditampilkan pesan error
      return redirect()
      ->route('subscriptions')
      ->with('alert', 'Quota Contact Has Exceeded Capacity, Please Upgrade Your Package');
      throw new \Exception("kuota contact telah melebihi kapasitas, silahkan upgrade paket");
    }
}
