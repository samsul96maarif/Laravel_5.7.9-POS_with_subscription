<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SalesOrder;
use App\Models\Contact;
use App\Models\Store;

class AdminSalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
      $salesOrders = salesOrder::all();
      $contacts = contact::all();
      $store = store::all();
      // dd($salesOrders);
      return view('admin/sales_order/index',
      [
        'salesOrders' => $salesOrders
      ]);
    }
}
