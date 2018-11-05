<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\Store;

use Auth;

class SubscriptionController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware(['auth', 'gate']);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function home()
  {
      return view('user/index');
  }

  public function index()
  {
    $user_id = Auth::id();
    $store = store::where('user_id', $user_id)->first();
    $subscriptions = subscription::all();
    return view('user/subscription/index',
    [
      'subscriptions' => $subscriptions,
      'store' => $store
    ]);
  }

  public function show($id)
  {
    $subscription = subscription::findOrFail($id);
    return view('user/subscription/detail', ['subscription' => $subscription]);
  }

  public function beli(Request $request)
  {
    $subscription = subscription::find($request->id);
    $user_id = Auth::id();
    // dd($user_id);
    $store = store::where('user_id', $user_id)->first();
    $store->subscription_id = $subscription->id;
    $store->status = 0;
    $store->expire_date = null;
    // dd($store);
    $store->save();

    return redirect('/subscription');
  }
}
