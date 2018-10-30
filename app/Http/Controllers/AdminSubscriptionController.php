<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Store;

class AdminSubscriptionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
      $subscriptions = subscription::all();
      return view('admin/subscription/index', ['subscriptions' => $subscriptions]);
    }

    public function show($id)
    {
      $subscription = subscription::findOrFail($id);
      $users = user::all();
      $stores = store::all()->where('subscription_id', $id);

      return view('admin/subscription/detail',
      [
        'subscription' => $subscription,
        'users' => $users,
        'stores' => $stores
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
      return redirect('/admin/subscription');
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
          return redirect('/admin/subscription');
        }

        // delete
      public function delete($id)
      {
        $subscription = subscription::find($id);
        $subscription->delete();
        return redirect('/admin/subscription');
      }
}
