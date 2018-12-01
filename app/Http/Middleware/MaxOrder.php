<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\SalesOrder;
use App\Models\Organization;

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
      $user_id = Auth::id();
      // dd($user_id);
      $organization = organization::where('user_id', $user_id)->first();
      // mencari contacts melalui organization id
      $salesOrders = salesOrder::all()->where('organization_id', $organization->id);
      $i = 0;
      // dd($contacts);
      foreach ($salesOrders as $salesOrder) {
        $i++;
      }
      // dd($i);
      $subscription_num_invoices = subscription::find($organization->subscription_id)->num_invoices;
      // dd($subscription_num_invoices);
      if ($i <= $subscription_num_invoices || $subscription_num_invoices == 0) {
        return $next($request);
      }
      // bila mau ditampilkan pesan error
      return redirect()
      ->route('subscriptions')
      ->with('alert', 'Quota Sales Order Has Exceeded Capacity, Please Upgrade Your Package');
      throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
    }
}
