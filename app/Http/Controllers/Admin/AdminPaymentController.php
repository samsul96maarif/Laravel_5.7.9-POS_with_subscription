<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\Organization;
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
      $payments = payment::all()->where('paid', 0);
      $organizations = organization::all();
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/payment/index',
      [
        'payments' => $payments,
        'organizations' => $organizations,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function show($id)
    {
      $payment = payment::findOrFail($id);
      $organization = organization::where('id', $payment->organization_id)->first();
      $subscription = subscription::where('id', $organization->subscription_id)->first();

      return view('admin/payment/detail',
      [
        'payment' => $payment,
        'organization' => $organization,
        'subscription' => $subscription
      ]);
    }

    public function search(Request $request)
    {
      $payments = DB::table('payments')
                      ->where('amount', 'like', '%'.$request->q.'%')
                      ->where('paid', 0)
                      ->where('deleted_at', null)
                      ->get();

      $organizations = organization::all();
      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/payment/index',
      [
        'payments' => $payments,
        'organizations' => $organizations,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function paid($value='')
    {
      $organizations = organization::all();
      $subscriptions = subscription::all();
      $users = user::all();
      $payments = payment::all()
      ->where('proof', '!=' , null)
      ->where('paid', 0);

      return view('admin/payment/index',
      [
        'payments' => $payments,
        'organizations' => $organizations,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }
}
