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
// untuk menggunakan date
use Carbon\Carbon;

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

    // fungsi untuk mengaktifkan package subscription pada organization
    public function confirm(Request $request)
    {
      $now = carbon::now();

      if ($request->pilih === null) {
        return redirect()->back()
        ->with('alert', 'Please Selecet Row');
      }

      $count = count($request->pilih);
      for ($i=0; $i < $count; $i++) {
        $payment = payment::findOrFail($request->pilih[$i]);
        $addDays = $payment->period * 30;
        $organization = organization::findOrFail($payment->organization_id);

        // mengetahui apakah extend
        if ($organization->status == 1) {
          // mengambil expire date sebelumnya
          $expire_date = $organization->expire_date;

          $organization->expire_date = $expire_date->addDays($addDays);
        } else {
          $organization->status = 1;
          $organization->expire_date = $now->addDays($addDays);
        }
        $payment->paid = 1;
        $payment->save();
        
        $organization->save();
      }

      return redirect()
      ->route('admin.payments')
      ->withSuccess('Succeed Confirm Payments');
    }

}
