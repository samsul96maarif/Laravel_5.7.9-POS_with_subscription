<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\SalesOrder;
use App\Models\Item;
use App\Models\Contact;
use App\Models\Store;
use App\Models\Invoice;
use App\Models\InvoiceDetail;

class SalesOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'gate', 'get.subscription']);
    }

    public function index()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $contacts = contact::all()->where('store_id', $store->id);
      $salesOrders = SalesOrder::all()->where('store_id', $store->id);
      $invoices = invoice::all();
      $invoiceDetails = invoiceDetail::all();
      return view('user/sales_order/index', ['salesOrders' => $salesOrders, 'contacts' => $contacts, 'invoiceDetails' => $invoiceDetails, 'invoices' => $invoices]);
    }

    public function create()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $items = item::all()->where('store_id', $store->id);
      $contacts = contact::all()->where('store_id', $store->id);
      return view('user/sales_order/create', ['items' => $items, 'contacts' => $contacts]);
    }

    public function store(Request $request)
    {

      $this->validate($request, [
        'item_id' => 'required',
        'quantity' => 'required|integer|min:1',
        'contact_id' => 'required',
      ]);

      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $item = item::find($request->item_id);
      $price = $item->price;
      $total = $item->price*$request->quantity;

      $salesOrders = new salesOrder;
      $invoice = new invoice;
      $invoiceDetail = new invoiceDetail;

      // sales order
      $salesOrders->store_id = $store->id;
      // invoice detail
      $invoiceDetail->item_id = $request->item_id;
      $invoiceDetail->contact_id = $request->contact_id;
      $invoiceDetail->item_price = $price;
      $invoiceDetail->item_quantity = $request->quantity;
      $invoiceDetail->total = $total;

      $salesOrders->save();

      // invoice
      $invoice->sales_order_id = $salesOrders->id;
      $invoice->save();

      // invoice detail
      $invoiceDetail->invoice_id = $invoice->id;
      $invoiceDetail->save();
      // sales order
      $salesOrders->total = $total;
      $salesOrders->order_number = 'SO-'.$salesOrders->id;
      $salesOrders->save();
      // invoice
      $invoice->total = $total;
      $invoice->number = 'INV-'.$invoice->id;
      $invoice->save();




      // dd($request->item_id);
    }
}
