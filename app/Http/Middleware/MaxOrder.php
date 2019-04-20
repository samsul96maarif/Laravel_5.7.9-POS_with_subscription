<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\SalesOrder;
use App\Models\Organization;
use App\Models\Item;

use Auth;

use Closure;

class MaxOrder
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
      // mencari sales orders melalui organization id
      $salesOrders = salesOrder::all()->where('organization_id', $organization->id);
      $i = 0;
      // dd($contacts);
      foreach ($salesOrders as $salesOrder) {
        $i++;
      }

      $subscription = subscription::find($organization->subscription_id);

      if ($i < $subscription->num_invoices || $subscription->num_invoices === null) {
        $items = item::all()->where('organization_id', $organization->id);
        $i = 0;
        foreach ($items as $value) {
          $i++;
        }
        if ($i > $subscription->num_items) {
          return redirect()
          ->route('subscriptions')
          ->with('alert', 'Quota Items Has Exceeded Capacity, Please Upgrade Your Package');
          throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
        }
        return $next($request);
      }
      // bila mau ditampilkan pesan error
      if ($user->role == 0) {
        return redirect()
        ->route('sales.orders')
        ->with('alert', 'Quota Sales Order Has Exceeded Capacity, Please Upgrade Your Package');
      }
      return redirect()
      ->route('subscriptions')
      ->with('alert', 'Quota Sales Order Has Exceeded Capacity, Please Upgrade Your Package');
      throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
    }
}
