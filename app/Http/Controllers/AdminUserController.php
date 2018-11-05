<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('admin');
  }

  public function index()
  {
    $users = user::all()->where('role', false);
    $stores = store::all();
    // dd($users);
    return view('admin/user/index',
    [
      'users' => $users,
      'stores' => $stores
    ]);
  }

  public function show($id)
  {
    $user = user::findOrFail($id);
    $store = store::where('user_id', $id)->first();
    // $subscription = subscription::findOrFail($store->subscription_id);
    // $contacts = contact::all()->where('store_id', $store->id);
    // $salesOrders = salesOrder::all()->where('store_id', $store->id);
    // dd($store);
    return view('admin/user/detail',
    [
      'user' => $user,
      // 'Subscription' => $subscription,
      // 'contacts' => $contacts,
      // 'salesOrders'=> $salesOrders,
      'store' => $store
    ]);
  }

  public function search(Request $request)
  {

    $users = DB::table('users')
                    ->where('name', 'like', '%'.$request->q.'%')
                    ->where('role', false)
                    ->get();

    $stores = store::all();
                    // dd($users);
    return view('admin/user/index',
      [
        'users' => $users,
        'stores' => $stores
      ]);
  }
}
