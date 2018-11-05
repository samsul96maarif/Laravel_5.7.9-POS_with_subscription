<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SalesOrder;
use App\Models\Contact;
use App\Models\Item;

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
      $filter = 'all';

      return view('admin/store/index',
      [
        'stores' => $stores,
        'filter' => $filter,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function show($id)
    {
      $store = store::findOrFail($id);
      $subscription = subscription::where('id', $store->subscription_id)->first();
      $user = user::where('id', $store->user_id)->first();
      // $users = user::all();
      $contacts = contact::all()->where('store_id', $id);
      $salesOrders = salesOrder::all()->where('store_id', $id);
      $items = item::all()->where('store_id', $id);
      $numSalesOrders = count($salesOrders);
      $numContacts = count($contacts);
      $numItems = count($items);
      // dd($user);

      return view('admin/store/detail',
      [
        'store' => $store,
        'subscription' => $subscription,
        'user' => $user,
        'numSalesOrders' => $numSalesOrders,
        'numContacts' => $numContacts,
        'numItems' => $numItems
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

      $filter = $request->filter;
      if ($request->filter == 'awaiting') {
        $filter = 'awaiting paymnet';
      } elseif ($request->filter == 'not') {
        $filter = 'not subscribe';
      }
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/store/index',
      [
        'stores' => $stores,
        'filter' => $filter,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

}
