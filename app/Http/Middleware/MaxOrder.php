<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\SalesOrder;
use App\Models\Store;

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
      $store = store::where('user_id', $user_id)->first();
      // mencari contacts melalui store id
      $salesOrders = salesOrder::all()->where('store_id', $store->id);
      $i = 0;
      // dd($contacts);
      foreach ($salesOrders as $salesOrder) {
        $i++;
      }
      // dd($i);
      $subscription_num_invoices = subscription::find($store->subscription_id)->num_invoices;
      // dd($subscription_num_invoices);
      if ($i <= $subscription_num_invoices) {
        return $next($request);
      }
      // bila mau ditampilkan pesan error
      throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
    }
}
