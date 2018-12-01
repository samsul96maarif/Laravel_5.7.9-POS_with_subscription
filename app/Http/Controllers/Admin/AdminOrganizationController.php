<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SalesOrder;
use App\Models\Contact;
use App\Models\Item;
use App\Models\Payment;
// untuk menggunakan db builder
use Illuminate\Support\Facades\DB;
// menggunakan auth bawaan laravel
use Auth;
// untuk menggunakan date
use Carbon\Carbon;

class AdminOrganizationController extends Controller
{
    public function __construct()
    {
      // untuk mengecek auth
        $this->middleware('auth');
        // unutk mengecek role user 1 / 0
        $this->middleware('admin');
    }

    public function index()
    {
      $organizations = organization::all();
      $subscriptions = subscription::all();
      $users = user::all();
      $filter = 'All';

      return view('admin/organization/index',
      [
        'organizations' => $organizations,
        'filter' => $filter,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function show($id)
    {
      $organization = organization::findOrFail($id);
      $subscription = subscription::where('id', $organization->subscription_id)->first();
      $user = user::where('id', $organization->user_id)->first();
      $contacts = contact::all()->where('organization_id', $id);
      $salesOrders = salesOrder::all()->where('organization_id', $id);
      $items = item::all()->where('organization_id', $id);
      // mengetahui jumlah sales order dari organization
      $numSalesOrders = count($salesOrders);
      // mengetahui jumlah contact dari organization
      $numContacts = count($contacts);
      // unutk mengetahui item dari organization
      $numItems = count($items);

      return view('admin/organization/detail',
      [
        'organization' => $organization,
        'subscription' => $subscription,
        'user' => $user,
        'numSalesOrders' => $numSalesOrders,
        'numContacts' => $numContacts,
        'numItems' => $numItems
      ]);
    }

    // fungsi untuk mengaktifkan package subscription pada organization
    public function active(Request $request, $id)
    {
      $now = carbon::now();
      $organization = organization::find($id);
      $organization->status = $request->status;

      if ($organization->status == 1) {
        $payment = payment::where('organization_id', $organization->id)
          ->where('paid', 0)->first();

        $subscription = subscription::findOrFail($payment->subscription_id);

        $addDays = $payment->period * 30;
        $organization->expire_date = Carbon::now()->addDays($addDays);
        $payment->paid = 1;
        $payment->save();
      } else {
        // unutk menonaktifkan package subscription
        // sekarang fungsi ini masih dinonaktofkan
        $organization->expire_date = null;
        $organization->subscription_id = null;
      }

      $organization->save();

      return redirect()
      ->route('admin.organization.detail', ['id' => $organization->id])
      ->withSuccess('Activate Package '.$subscription->name.' For '.$organization->name.' Succeed');
    }

    // fungsi untuk menambah masa aktif package subscription
    public function extend(Request $request, $id)
    {
      $organization = organization::find($id);
      $now = $organization->expire_date;

      if ($organization->status == 1) {
        $addDays = $request->period * 30;
        $organization->expire_date = $now->addDays($addDays);
      }

      $payment = payment::where('organization_id', $organization->id)
      ->where('paid', 0)->first();
      $payment->paid = 1;
      $payment->save();

      $organization->save();

      return redirect()
      ->route('admin.organization.detail', ['id' => $organization->id])
      ->withSuccess('Extend Package '.$subscription->name.' For '.$organization->name.' Succeed');
    }

    public function filter(Request $request)
    {
      if ($request->filter == 'active') {
        $organizations = organization::all()->where('status', 1);
      } elseif ($request->filter == 'awaiting') {
        $organizations = organization::all()
        ->where('status', 0)
        ->where('subscription_id', '!=', null);
      } elseif ($request->filter == 'not') {
        $organizations = organization::all()->where('subscription_id', null);
      } else {
        $organizations = organization::all();
      }

      if ($request->filter == 'awaiting') {
        $filter = 'Awaiting Paymnet';
      } elseif ($request->filter == 'not') {
        $filter = 'Not Subscribe';
      } else {
        $filter = 'Active';
      }

      $subscriptions = subscription::all();
      $users = user::all();

      return view('admin/organization/index',
      [
        'organizations' => $organizations,
        'filter' => $filter,
        'subscriptions' => $subscriptions,
        'users' => $users
      ]);
    }

    public function search(Request $request)
    {
      $id = Auth::id();

      $organizations = DB::table('organizations')
                      ->where('name', 'like', '%'.$request->q.'%')
                      ->where('deleted_at', null)
                      ->get();

      $subscriptions = subscription::all();
      $users = user::all();
      $filter = 'All';

      return view('admin/organization/index',
        [
          'organizations' => $organizations,
          'filter' => $filter,
          'subscriptions' => $subscriptions,
          'users' => $users
        ]);
    }

}
