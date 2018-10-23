<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

use App\Models\Contact;
use App\Models\Constore;
use App\Models\Usestore;

class GetContact
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

        // user_stores
        $user_store = usestore::where('user_id', $user_id)->first();
        $store_id = $user_store->store_id;
        $contact_stores = constore::where('store_id', $store_id);
        foreach ($contact_stores as $contact_store) {
          $contact = contact::where('id', $contact_store->id);
        }
        dd($contact);
        return $next($request);
    }
}
