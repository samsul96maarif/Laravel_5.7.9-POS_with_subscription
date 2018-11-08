<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminPaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
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
