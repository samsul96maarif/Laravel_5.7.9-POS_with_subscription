<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SalesOrder;

class SalesOrderController extends Controller
{
    public function index()
    {
      $salesOrders = SalesOrder::all();
      dd($salesOrders);
    }
}
