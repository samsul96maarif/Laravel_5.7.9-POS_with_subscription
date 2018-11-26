<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\User;
use App\Models\Store;
use App\Models\Subscription;
// unutk menggunakn db builder
use Illuminate\Support\Facades\DB;
// unutk menggunakan date
use Carbon\Carbon;

class AdminReportTesController extends Controller
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
    $users = user::all()->where('role', 0);
    $stores = store::all();
    $data = payment::all()->where('paid', 1);
    // $subscriptions = subscription::all();

    $now = Carbon::now();
    $startDate = Carbon::now();
    $endDate = Carbon::now();
    $year = $now->year;
    $month = $now->month;
    $sekarang = $now->format('m/d/Y');
    $by = 'on '.$now->englishMonth;

    $subscriptions = DB::table('payments')
            ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
            ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
            ->where('payments.deleted_at', null)
            ->where('payments.paid', 1)
            ->whereYear('payments.updated_at', '=', $year)
            ->whereMonth('payments.updated_at', '=', $month)
            ->groupBy('payments.subscription_id', 'subscriptions.name')
            ->get();

            return view('tes/report/payment',
            [
              'by' => $by,
              'now' => $sekarang,
              'subscriptions' => $subscriptions,
              // 'payments' => $payments,
              'users' => $users,
              'stores' => $stores,
              'subscriptions' => $subscriptions,
              'now' => $now
            ]);

    if(count($data) > 0){ //mengecek apakah data kosong atau tidak
        $res['message'] = "Success!";
        $res['values'] = $data;
        // return response($res);
    }
    else{
        $res['message'] = "Empty!";
        $res['values'] = $data;
        // return response($res);
    }
    $payments = json_decode($res['values']);

    return view('admin/report/index', [
      'payments' => $payments,
      'users' => $users,
      'stores' => $stores,
      'subscriptions' => $subscriptions,
      'now' => $now
    ]);
  }

  public function searchBy(Request $request)
  {

    $subscriptions = subscription::all();
    $users = user::all();
    $stores = store::all();

    $now = Carbon::now();
    $startDate = Carbon::now();
    $endDate = Carbon::now();
    $year = $now->year;
    $month = $now->month;
    $sekarang = $now->format('m/d/Y');

    if ($now->dayOfWeekIso < 7) {
      if ($now->dayOfWeekIso < 6) {
        if ($now->dayOfWeekIso < 5) {
          if ($now->dayOfWeekIso < 4) {
            if ($now->dayOfWeekIso < 3) {
              if ($now->dayOfWeekIso < 2) {
                $endDate->addDays(6);
              } else {
                $startDate->subDays(1);
                $endDate->addDays(5);
              }
            } else {
              $startDate->subDays(2);
              $endDate->addDays(4);
            }
          } else {
            $startDate->subDays(3);
            $endDate->addDays(3);
          }
        } else {
          $startDate->subDays(4);
          $endDate->addDays(2);
        }
      } else {
        $startDate->subDays(5);
        $endDate->addDays(1);
      }
    } else {
      $startDate->subDays(6);
    }

    $startDate->hour(0)->minute(0)->second(0);
    $endDate->hour(0)->minute(0)->second(0);

    if ($request->by == 'year') {
      $by = 'This Year '.$year;

      $subscriptions = DB::table('payments')
              ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
              ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
              ->where('payments.deleted_at', null)
              ->where('payments.paid', 1)
              ->whereYear('payments.updated_at', '=', $year)
              ->groupBy('payments.subscription_id', 'subscriptions.name')
              ->get();

    } elseif ($request->by == 'month') {
      $by = 'This Month '.$now->englishMonth;

      $subscriptions = DB::table('payments')
              ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
              ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
              ->where('payments.deleted_at', null)
              ->where('payments.paid', 1)
              ->whereYear('payments.updated_at', '=', $year)
              ->whereMonth('payments.updated_at', '=', $month)
              ->groupBy('payments.subscription_id', 'subscriptions.name')
              ->get();

    } elseif ($request->by == 'week') {
      $by = 'This Week';

      $subscriptions = DB::table('payments')
              ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
              ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
              ->where('payments.deleted_at', null)
              ->where('payments.paid', 1)
              ->whereBetween('payments.updated_at', [$startDate, $endDate])
              ->groupBy('payments.subscription_id', 'subscriptions.name')
              ->get();

    } elseif ($request->by == 'all') {
      $by = 'This All';

      return redirect()->route('admin.report');

    } else {
      $by = 'From '.$request->start_date.' To '.$request->end_date;

      $subscriptions = DB::table('payments')
              ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
              ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
              ->where('payments.deleted_at', null)
              ->where('payments.paid', 1)
              ->whereBetween('payments.updated_at', [$request->start_date, $request->end_date])
              ->groupBy('payments.subscription_id', 'subscriptions.name')
              ->get();
    }

    return view('admin/report/index',
    [
      'subscriptions' => $subscriptions,
      'by' => $by,
      'now' => $sekarang
    ]);

  }

  public function show($id)
  {
      $subscription = subscription::findOrFail($id);
      $users = user::all();
      $stores = store::all();
      $payments = payment::all()
      ->where('subscription_id', $id)
      ->where('paid', 1);

      return view('admin/report/detail',
      [
        'subscription' => $subscription,
        'stores' => $stores,
        'users' => $users,
        'payments' => $payments
      ]);
  }

}
