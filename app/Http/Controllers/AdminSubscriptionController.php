<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Store;
use App\Models\Payment;

class AdminSubscriptionController extends Controller
{

    public function __construct()
    {
      // unutk mengecek auth
        $this->middleware('auth');
        // untuk mengecek role user 1 / 0
        $this->middleware('admin');
    }

    public function index()
    {
      $subscriptions = subscription::all();

      return view('admin/subscription/index', [
        'subscriptions' => $subscriptions
      ]);
    }

    public function show($id)
    {
      $subscription = subscription::findOrFail($id);
      $users = user::all();
      $stores = store::all()->where('subscription_id', $id);
      $filter = 'All';

      return view('admin/subscription/detail',
      [
        'subscription' => $subscription,
        'filter' => $filter,
        'users' => $users,
        'stores' => $stores
      ]);
    }

    public function filter(Request $request, $id)
    {
      $subscription = subscription::findOrFail($id);

      if ($request->filter == 'active') {
        $stores = Store::all()
        ->where('subscription_id', $id)
        ->where('status', 1);
      } elseif ($request->filter == 'awaiting') {
        $stores = Store::all()
        ->where('subscription_id', $id)
        ->where('status', 0)
        ->where('subscription_id', '!=', null);
      } else {
        $stores = store::all()->where('subscription_id', $id);
      }

      if ($request->filter == 'awaiting') {
        $filter = 'Awaiting Paymnet';
      } elseif ($request->filter == 'not') {
        $filter = 'Not Subscribe';
      }else {
        $filter = 'Active';
      }

      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/subscription/detail',
      [
        'stores' => $stores,
        'filter' => $filter,
        'subscription' => $subscription,
        'users' => $users
      ]);
    }

    // create
// 1. mengarahkan ke form
    public function create(){
      return view('admin/subscription/create');
    }
// 2. store data create
    public function store(Request $request){
      // contoh penggunaan validate dimana :
      // 1. value name required
      $this->validate($request, [
        'name' => 'required',
        'price' => 'required|integer',
        'num_invoices' => 'required|integer',
        'num_users' => 'required|integer',
      ]);

      $subscription = new subscription;
      $subscription->name = $request->name;
      $subscription->price = $request->price;
      $subscription->num_invoices = $request->num_invoices;
      $subscription->num_users = $request->num_users;
      $subscription->save();

      return redirect()
      ->route('admin.subscription')
      ->withSuccess('Succeed Add Package '.$subscription->name);
    }

    // update
    // 1. get data melalui id-nya
        public function edit($id){
          $subscription = subscription::find($id);
          return view('admin/subscription/edit', ['subscription' => $subscription]);
        }
    // 2. store data update
        public function update(Request $request, $id){

          $this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer',
            'num_invoices' => 'required|integer',
            'num_users' => 'required|integer',
          ]);

          $subscription = subscription::find($id);
          $subscription->name = $request->name;
          $subscription->price = $request->price;
          $subscription->num_invoices = $request->num_invoices;
          $subscription->num_users = $request->num_users;
          $subscription->save();

          return redirect()
          ->route('admin.subscription.edit', ['id' => $subscription->id])
          ->withSuccess('Succeed Edit Package '.$subscription->name);
        }

        // delete
      public function delete($id)
      {
        $subscription = subscription::find($id);
        $store = store::where('subscription_id', $subscription->id)
        ->where('status', 1)->first();

        $payment = payment::where('subscription_id', $subscription->id)
        ->where('proof', '!=', null)
        ->where('paid', 0)->first();

        $alert = 'alert-success';

        if ($store != null || $payment != null) {
          $alert = 'alert-danger';

            return redirect()
            ->route('admin.subscription.detail', ['id' => $subscription->id])
            ->withSuccess('Delete package '.$subscription->name.' Failed, Package Has been Used');
        }

        $payments = payment::all()
        ->where('subscription_id', $subscription->id)
        ->where('paid', 0);

        $stores = store::all()
        ->where('subscription_id', $subscription->id);

        foreach ($payments as $payment) {
          $payment->delete();
        }

        foreach ($stores as $store) {
          $store->subscription_id = null;
          $store->save();
        }

        $subscription->delete();

        return redirect()
        ->route('admin.subscription')
        ->withSuccess('Succeed Deleted Package '.$subscription->name);
      }
}
