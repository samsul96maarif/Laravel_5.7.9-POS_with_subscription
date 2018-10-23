<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\Stosub;
use App\Models\Usestore;

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
      $this->middleware('auth');
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
    $subscriptions = subscription::all();
    return view('user/subscription/index', ['subscriptions' => $subscriptions]);
  }

  public function show($id)
  {
    $subscription = subscription::find($id);
    return view('user/subscription/detail', ['subscription' => $subscription]);
  }

  public function beli(Request $request)
  {
    $subscription = subscription::find($request->id);

    $user_id = Auth::user()->id;

    $usestore = usestore::where('user_id', $user_id)->first();

    $stosub = new stosub;
    $stosub->store_id = $usestore->store_id;
    $stosub->subscription_id = $subscription->id;
    // dd($stosub->subscription_id);
    $stosub->save();

    return redirect('/home');
    // return view('user/subscription/detail', ['subscription' => $subscription]);
  }
}
