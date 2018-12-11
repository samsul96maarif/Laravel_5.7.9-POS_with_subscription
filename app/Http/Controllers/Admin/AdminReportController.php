<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Payment;
use App\Models\User;
use App\Models\Organization;
use App\Models\Subscription;
// unutk menggunakn db builder
use Illuminate\Support\Facades\DB;
// unutk menggunakan date
use Carbon\Carbon;

class AdminReportController extends Controller
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
      $packages = subscription::withTrashed()->get();
      $now = Carbon::now();
      $startDate = Carbon::now();
      $endDate = Carbon::now();
      $year = $now->year;
      $month = $now->month;
      $sekarang = $now->format('m/d/Y');
      $by = 'on '.$now->englishMonth;

      $data = DB::table('payments')
            ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
            ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.subscription_id) as count"), DB::raw("SUM(payments.period) as period"))
            ->where('payments.deleted_at', null)
            ->where('payments.paid', 1)
            ->whereYear('payments.updated_at', '=', $year)
            ->whereMonth('payments.updated_at', '=', $month)
            ->groupBy('payments.subscription_id', 'subscriptions.name')
            ->get();

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
      $subscriptions = json_decode($res['values']);

      return view('admin/report/index',
            [
              'by' => $by,
              'packages' => $packages,
              'now' => $sekarang,
              'subscriptions' => $subscriptions,
              'now' => $now
            ]);
    }

    public function searchBy(Request $request)
    {
      $packages = subscription::all();
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
        $by = 'In '.$year;

        $subscriptions = DB::table('payments')
                ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
                ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
                ->where('payments.deleted_at', null)
                ->where('payments.paid', 1)
                ->whereYear('payments.updated_at', '=', $year)
                ->groupBy('payments.subscription_id', 'subscriptions.name')
                ->get();

      } elseif ($request->by == 'month') {

        return redirect()->route('admin.report');

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
        $by = 'All Time';

        $subscriptions = DB::table('payments')
                ->join('subscriptions', 'subscriptions.id','=','payments.subscription_id')
                ->select('subscriptions.name', DB::raw("SUM(payments.amount) as amount"), DB::raw("count(payments.amount) as count"), DB::raw("SUM(payments.period) as period"))
                ->where('payments.deleted_at', null)
                ->where('payments.paid', 1)
                ->groupBy('payments.subscription_id', 'subscriptions.name')
                ->get();

      } else {
        $mulai = date('d-m-Y', strtotime($request->start_date));
        $sampai = date('d-m-Y', strtotime($request->end_date));
        $by = 'From '.$mulai.' To '.$sampai;

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
        'packages' => $packages,
        'now' => $sekarang
      ]);

    }

    public function show($id)
    {
        $subscription = subscription::findOrFail($id);
        $users = user::all();
        $organizations = organization::all();
        $payments = payment::all()
        ->where('subscription_id', $id)
        ->where('paid', 1);

        return view('admin/report/detail',
        [
          'subscription' => $subscription,
          'organizations' => $organizations,
          'users' => $users,
          'payments' => $payments
        ]);
    }

}
