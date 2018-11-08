<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SalesOrder;
use App\Models\Store;
use App\Models\Invoice;
use App\Models\Contact;
// unutk menggunakn db builder
use Illuminate\Support\Facades\DB;
// unutk menggunakan auth
use Auth;
// unutk menggunakan date
use Carbon\Carbon;

class ReportController extends Controller
{

    public function __construct()
    {
      // auth : unutk mengecek auth
      // gate : unutk mengecek apakah sudah membuat store
      // getSubscription : unutk mengecek subscription store
        $this->middleware(['auth', 'gate', 'get.subscription']);
    }

    public function salesByCustomerMonth()
    {
      $now = Carbon::now();
      $year = $now->year;
      $month = $now->month;
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();

      $by = 'This Month '.$now->englishMonth;
      // ex : "October"
      $customers = DB::table('sales_orders')
      ->join('contacts', 'contacts.id','=','sales_orders.contact_id')
      ->select('contacts.name', DB::raw("SUM(sales_orders.total) as total"))
      ->where('sales_orders.store_id', $store->id)
      ->whereYear('sales_orders.created_at', '=', $year)
      ->whereMonth('sales_orders.created_at', '=', $month)
      ->groupBy('sales_orders.contact_id', 'contacts.name')
      ->get();

      // $users = DB::table('invoices')
      // ->join('contacts', 'contacts.id','=','invoices.contact_id')
      // ->select('contacts.name', DB::raw("SUM(invoices.total) as total"))
      // ->groupBy('invoices.contact_id', 'contacts.name')
      // ->get();

        return view('user/report/customer',
        [
          'customers' => $customers,
          'by' => $by
        ]);

    }

    public function salesByItemMonth()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $now = Carbon::now();
      $year = $now->year;
      $month = $now->month;
      $sekarang = $now->format('m/d/Y');
      $by = 'This Month '.$now->englishMonth;
      // ex : "October"

      $items = DB::table('invoice_details')
      ->join('items', 'items.id','=','invoice_details.item_id')
      ->select('items.name', DB::raw("SUM(invoice_details.total) as total"))
      ->where('invoice_details.store_id', $store->id)
      ->whereYear('invoice_details.created_at', '=', $year)
      ->whereMonth('invoice_details.created_at', '=', $month)
      ->groupBy('invoice_details.item_id', 'items.name')
      ->get();

        return view('user/report/item',
        [
          'items' => $items,
          'by' => $by,
          'now' => $sekarang
        ]);

    }

    public function Item(Request $request)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
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

        $items = DB::table('invoice_details')
                ->join('items', 'items.id','=','invoice_details.item_id')
                ->select('items.name', DB::raw("SUM(invoice_details.total) as total"))
                ->where('invoice_details.store_id', $store->id)
                ->whereYear('invoice_details.created_at', '=', $year)
                ->groupBy('invoice_details.item_id', 'items.name')
                ->get();
      } elseif ($request->by == 'month') {
        $by = 'This Month '.$now->englishMonth;

        $items = DB::table('invoice_details')
                ->join('items', 'items.id','=','invoice_details.item_id')
                ->select('items.name', DB::raw("SUM(invoice_details.total) as total"))
                ->where('invoice_details.store_id', $store->id)
                ->whereYear('invoice_details.created_at', '=', $year)
                ->whereMonth('invoice_details.created_at', '=', $month)
                ->groupBy('invoice_details.item_id', 'items.name')
                ->get();
      } elseif ($request->by == 'week') {
        // $by = 'This Week From '.$startDate->toFormattedDateString().' To '.$endDate->toFormattedDateString();
        $by = 'This Week';

        $items = DB::table('invoice_details')
                ->join('items', 'items.id','=','invoice_details.item_id')
                ->select('items.name', DB::raw("SUM(invoice_details.total) as total"))
                ->where('invoice_details.store_id', $store->id)
                ->whereBetween('invoice_details.created_at', [$startDate, $endDate])
                ->groupBy('invoice_details.item_id', 'items.name')
                ->get();
      } elseif ($request->by == 'all') {
        $by = 'All';
        $items = DB::table('invoice_details')
                ->join('items', 'items.id','=','invoice_details.item_id')
                ->select('items.name', DB::raw("SUM(invoice_details.total) as total"))
                ->where('invoice_details.store_id', $store->id)
                ->groupBy('invoice_details.item_id', 'items.name')
                ->get();
      } else {
        $by = 'From '.$request->start_date.' To '.$request->end_date;

        $items = DB::table('invoice_details')
                ->join('items', 'items.id','=','invoice_details.item_id')
                ->select('items.name', DB::raw("SUM(invoice_details.total) as total"))
                ->where('invoice_details.store_id', $store->id)
                ->whereBetween('invoice_details.created_at', [$request->start_date, $request->end_date])
                ->groupBy('invoice_details.item_id', 'items.name')
                ->get();
      }

        return view('user/report/item',
        [
          'items' => $items,
          'by' => $by,
          'now' => $sekarang
        ]);
    }

    public function Customer(Request $request)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
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

        $customers = DB::table('sales_orders')
                ->join('contacts', 'contacts.id','=','sales_orders.contact_id')
                ->select('contacts.name', DB::raw("SUM(sales_orders.total) as total"))
                ->where('sales_orders.store_id', $store->id)
                ->whereYear('sales_orders.created_at', '=', $year)
                ->groupBy('sales_orders.contact_id', 'contacts.name')
                ->get();
      } elseif ($request->by == 'month') {
        $by = 'This Month '.$now->englishMonth;

        $customers = DB::table('sales_orders')
                ->join('contacts', 'contacts.id','=','sales_orders.contact_id')
                ->select('contacts.name', DB::raw("SUM(sales_orders.total) as total"))
                ->where('sales_orders.store_id', $store->id)
                ->whereYear('sales_orders.created_at', '=', $year)
                ->whereMonth('sales_orders.created_at', '=', $month)
                ->groupBy('sales_orders.contact_id', 'contacts.name')
                ->get();
      } elseif ($request->by == 'week') {
        $by = 'This Week';

        $customers = DB::table('sales_orders')
                ->join('contacts', 'contacts.id','=','sales_orders.contact_id')
                ->select('contacts.name', DB::raw("SUM(sales_orders.total) as total"))
                ->where('sales_orders.store_id', $store->id)
                ->whereBetween('sales_orders.created_at', [$startDate, $endDate])
                ->groupBy('sales_orders.contact_id', 'contacts.name')
                ->get();
      } elseif ($request->by == 'all') {
        $by = 'This All';

        $customers = DB::table('sales_orders')
                ->join('contacts', 'contacts.id','=','sales_orders.contact_id')
                ->select('contacts.name', DB::raw("SUM(sales_orders.total) as total"))
                ->where('sales_orders.store_id', $store->id)
                ->groupBy('sales_orders.contact_id', 'contacts.name')
                ->get();
      } else {
        $by = 'From '.$request->start_date.' To '.$request->end_date;

        $customers = DB::table('sales_orders')
                ->join('contacts', 'contacts.id','=','sales_orders.contact_id')
                ->select('contacts.name', DB::raw("SUM(sales_orders.total) as total"))
                ->where('sales_orders.store_id', $store->id)
                ->whereBetween('sales_orders.created_at', [$request->start_date, $request->end_date])
                ->groupBy('sales_orders.contact_id', 'contacts.name')
                ->get();
      }

        return view('user/report/customer',
        [
          'customers' => $customers,
          'by' => $by,
          'now' => $sekarang
        ]);
    }
}
