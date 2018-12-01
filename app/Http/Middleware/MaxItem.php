<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Item;
use App\Models\Organization;
use App\Models\Subscription;
use Auth;

class MaxItem
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
        } else {
          $organization = organization::where('user_id', $user->id)->first();
        }

        $subscription = subscription::findOrFail($organization->subscription_id);

        $items = item::all()->where('organization_id', $organization->id);
        $i = 0;
        foreach ($items as $value) {
          $i++;
        }

        if ($i < $subscription->num_items || $subscription->num_items === null) {
          // code...
          return $next($request);
        }

        if ($user->role == 0) {
          return back()
          ->with('alert', 'Quota Items Has Exceeded Capacity, Please Upgrade Your Package');
        }

        return redirect()
        ->route('subscriptions')
        ->with('alert', 'Quota Items Has Exceeded Capacity, Please Upgrade Your Package');
    }
}
