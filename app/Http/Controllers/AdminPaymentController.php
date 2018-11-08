<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
// unutk menggunakan db builder
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{

    public function __construct()
    {
        // mengecek sudah auth belum
        $this->middleware('auth');
        // mengecek apakah admin atau bukan
        $this->middleware('admin');
    }

    public function index()
    {
      $payments = payment::all();
      $stores = store::all();
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/payment/index',
      [
        'payments' => $payments,
        'stores' => $stores,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function show($id)
    {
      $payment = payment::findOrFail($id);
      $store = store::where('id', $payment->store_id)->first();
      $subscription = subscription::where('id', $store->subscription_id)->first();
      // dd($payment);
      return view('admin/payment/detail',
      [
        'payment' => $payment,
        'store' => $store,
        'subscription' => $subscription
      ]);
    }

    public function search(Request $request)
    {
      $payments = DB::table('payments')
                      ->where('uniq_code', 'like', '%'.$request->q.'%')
                      ->get();

      $stores = store::all();
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/payment/index',
      [
        'payments' => $payments,
        'stores' => $stores,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }
}
