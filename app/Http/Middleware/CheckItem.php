<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\Item;
use App\Models\Organization;

use Auth;

use Closure;

class CheckItem
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
        $i = 0;
        $items = item::all()->where('organization_id', $organization->id);
        foreach ($items as $item) {
          $i++;
        }
        if ($i > 0) {
          return $next($request);
        }
        return redirect('/item/create')->withSuccess('You Dont Have Inventory to Sell, Please Add Item First.');
    }
}
