<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;

use Auth;

class MaxUser
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
        if ($user->role == 0) {
          $organization = organization::findOrFail($user->organization_id);
          $extend = 'employeMaster';
        } else {
          // code...
          $organization = organization::where('user_id', $user->id)->first();
          $extend = 'userMaster';
        }

        $subscription = subscription::findOrFail($organization->subscription_id);

        $users = user::all()->where('organization_id', $organization->id);

        $i = 0;
        foreach ($users as $value) {
          $i++;
        }

        if ($i < $subscription->num_users || $subscription->num_users === null) {
          return $next($request);
        }

        if ($user->role == 0) {
          if ($i == $subscription->num_users) {
            return $next($request);
          }
          return redirect('/')
          ->with('alert', 'Quota Users Has Exceeded Capacity, Please Upgrade Your Package');
        }

        // bila mau ditampilkan pesan error
        return redirect()
        ->route('subscriptions')
        ->with('alert', 'Quota Users Has Exceeded Capacity, Please Upgrade Your Package');
        throw new \Exception("kuota contact telah melebihi kapasitas, silahkan upgrade paket");

    }
}
