<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;

use Carbon\Carbon;

class AdminStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
      $stores = store::all();
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/store/index',
      [
        'stores' => $stores,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function show($id)
    {
      $store = store::findOrFail($id);
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/store/detail',
      [
        'store' => $store,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function active(Request $request, $id)
    {
      $now = carbon::now();
      $store = store::find($id);
      $store->status = $request->status;
      if ($store->status == 1) {
        $store->expire_date = Carbon::now()->addDays(30);
        // dd($store->expire_date);
      } else {
        $store->expire_date = null;
        $store->subscription_id = null;
      }

      $store->save();
      return redirect('/admin/store');
    }

    public function extend(Request $request, $id)
    {
      $store = store::find($id);
      $now = $store->expire_date;
      // dd($now);
      if ($store->status == 1) {
        $store->expire_date = $now->addDays(30);
        // dd($store->expire_date);
      }
      $store->save();
      return redirect('/admin/store');
    }

    public function filter(Request $request)
    {
      if ($request->filter == 'active') {
        $stores = Store::all()->where('status', 1);
      } elseif ($request->filter == 'awaiting') {
        $stores = Store::all()
        ->where('status', 0)
        ->where('subscription_id', '!=', null);
      } elseif ($request->filter == 'not') {
        $stores = Store::all()->where('subscription_id', null);
      } else {
        $stores = store::all();
      }

      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/store/index',
      [
        'stores' => $stores,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

}
