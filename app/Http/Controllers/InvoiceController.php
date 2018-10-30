<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SalesOrder;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\Item;
use App\Models\Contact;
use App\Models\InvoiceDetail;

use Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'gate', 'get.subscription', 'max.order', 'check.item']);
    }
    public function create($salesOrder_id, $invoice_id)
    {
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      // dd($salesOrder);
      $invoice = invoice::findOrFail($invoice_id);
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $items = item::all()->where('store_id', $store->id);
      return view('user/sales_order/addItem',
      [
        'items' => $items,
        'invoiceDetails' => $invoiceDetails,
        'salesOrder' => $salesOrder,
        'invoice' => $invoice
      ]);
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'item_id' => 'required',
        'quantity' => 'required|integer|min:1',
      ]);

      $item = item::find($request->item_id);

      if ($request->quantity > $item->stock) {
        throw new \Exception("quantity lebih banyak dari stock barang");
        return redirect('/sales_order');
      }

      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $price = $item->price;
      $total = $item->price*$request->quantity;

      $invoiceDetail = new invoiceDetail;
      // invoice detail
      $invoiceDetail->store_id = $store->id;
      $invoiceDetail->item_id = $request->item_id;
      $invoiceDetail->item_price = $price;
      $invoiceDetail->item_quantity = $request->quantity;
      $invoiceDetail->total = $total;
      // invoice detail
      $invoiceDetail->invoice_id = $request->invoice;
      $invoiceDetail->save();

      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $request->invoice);
      foreach ($invoiceDetails as $invoice_detail) {
        $total = $invoice_detail->total + $total;
      }

      // dd($total);
      $invoice = invoice::findOrFail($request->invoice);
      $invoice->total = $total;
      $invoice->save();
      // dd($invoice);
      $salesOrder = salesOrder::findOrFail($request->sales_order);
      $salesOrder->total = $total;
      $salesOrder->save();

      $item->stock = $item->stock - $request->quantity;
      $item->save();

      return redirect('/sales_order');
    }

    public function edit($salesOrder_id, $invoice_id, $invoiceDetail_id)
    {
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $invoice = invoice::findOrFail($invoice_id);
      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);

      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $items = item::all()->where('store_id', $store->id);
      return view('user/sales_order/editInvoice',
      [
        'salesOrder' => $salesOrder,
        'invoice' => $invoice,
        'invoiceDetail' => $invoiceDetail,
        'items' => $items
      ]);
    }

    public function update(Request $request, $invoice_id, $invoiceDetail_id)
    {
      $this->validate($request, [
        'item_id' => 'required',
        'quantity' => 'required|integer|min:1',
      ]);
      $item = item::find($request->item_id);

      if ($request->quantity_old > $request->quantity) {
        $addStock = $request->quantity_old - $request->quantity;
        $item->stock = $item->stock + $addStock;
        $item->save();
      } else {
        // dd($request->quantity_old);
        $minStock = $request->quantity-$request->quantity_old;
        // dd($minStock);
        $item->stock = $item->stock - $minStock;
        // dd($item->stock);
        if ($item->stock < 0) {
          throw new \Exception("quantity lebih banyak dari stock barang");
          return redirect('/sales_order');
        }
        $item->save();
      }

      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $price = $item->price;
      $total = $item->price*$request->quantity;
      // invoice detail
      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);
      $invoiceDetail->item_id = $request->item_id;
      $invoiceDetail->item_price = $price;
      $invoiceDetail->item_quantity = $request->quantity;
      $invoiceDetail->total = $total;
      $invoiceDetail->save();

      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      foreach ($invoiceDetails as $invoice_detail) {
        $total = $invoice_detail->total + $total;
      }

      // dd($total);
      $invoice = invoice::findOrFail($invoice_id);
      $invoice->total = $total;
      $invoice->save();
      // dd($invoice);
      $salesOrder = salesOrder::findOrFail($request->salesOrder_id);
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect('/sales_order');
    }

    public function delete($salesOrder_id, $invoice_id, $invoiceDetail_id)
    {
      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);
      $item = item::findOrFail($invoiceDetail->item_id);
      // dd($item);
      $item->stock = $item->stock + $invoiceDetail->item_quantity;
      // dd($item->stock);
      $item->save();
      $invoiceDetail->delete();

      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      foreach ($invoiceDetails as $invoice_detail) {
        $total = $invoice_detail->total + $total;
      }

      // dd($total);
      $invoice = invoice::findOrFail($invoice_id);
      $invoice->total = $total;
      $invoice->save();
      // dd($invoice);
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect('/sales_order');
    }
}
