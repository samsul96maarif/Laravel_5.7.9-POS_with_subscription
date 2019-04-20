<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use App\Models\Contact;
use App\Models\Store;

use Auth;

use Closure;

class CheckContact
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
      $i = 0;
      $contacts = contact::all()->where('store_id', $store->id);
      foreach ($contacts as $contact) {
        $i++;
      }
      if ($i > 0) {
        return $next($request);
      }
      return redirect('/contact/create');
    }
}
