<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Models\SalesOrder;
use App\Models\Invoice;
use App\Models\Store;
use App\Models\Item;
use App\Models\Contact;
use App\Models\InvoiceDetail;
// unutk menggunakan auth
use Auth;

class InvoiceController extends Controller
{
    public function __construct()
    {
      // auth : unutk mengecek auth
      // gate : unutk mengecek apakah sudah membuat store
      // getSubscription : unutk mengecek subscription store
      // maxOrder : untuk mengcek quote invoice subscription
        $this->middleware(['auth', 'gate', 'get.subscription', 'max.order']);
    }

    public function store(Request $request, $salesOrder_id, $invoice_id)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $invoice = invoice::findOrFail($invoice_id);

      $this->validate($request, [
        'item' => 'required',
        'quantity' => 'required|min:1',
      ]);

      $count = count($request->item);
      // pembuatan invoice detail
      for ($i=0; $i < $count; $i++) {
        $item = item::where('name', $request->item[$i])
        ->where('store_id', $store->id)
        ->first();

        // mengetahui apakah quantity order lebih dari stcok barang
        if ($request->quantity[$i] > $item->stock) {
          return redirect()->route('sales.order.bill', ['id' => $salesOrder->id])
          ->with('alert', $request->item[$i].' Out Of Stock');

          throw new \Exception("quantity lebih banyak dari stock barang");
        }

        // mengecek apakah item sudah ada di invoice detail
        $invoiceDetail = invoiceDetail::where('invoice_id', $invoice_id)
        ->where('item_id', $item->id)->first();

        if ($invoiceDetail != null) {
          $price = $item->price;
          $total = $item->price*$request->quantity[$i];

          $invoiceDetail->item_quantity = $invoiceDetail->item_quantity + $request->quantity[$i];
          $invoiceDetail->total = $invoiceDetail->total + $total;
          $invoiceDetail->save();
          $message = $item->name.' already exist In sales order '.$salesOrder->order_number.' qty Has been Added';
        } else {
          // invoice detail
          $invoiceDetail = new invoiceDetail;
          $invoiceDetail->store_id = $store->id;
          $invoiceDetail->invoice_id = $invoice_id;
          $invoiceDetail->item_id = $item->id;
          $invoiceDetail->item_price = $item->price;
          $invoiceDetail->item_quantity = $request->quantity[$i];
          $invoiceDetail->total = $item->price*$request->quantity[$i];
          $invoiceDetail->save();
          $message = 'Succeed Updated Invoice';
        }
        $item->stock = $item->stock - $request->quantity[$i];
        $item->save();
      }

// unutk menambahkan total dari semua invoice detail ke invoice dan sales order
      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      foreach ($invoiceDetails as $invoice_detail) {
        $total = $invoice_detail->total + $total;
      }

      // invoice
      $invoice->total = $total;
      $invoice->save();
      // sales order
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect()->route('sales.order.bill', ['id' => $salesOrder->id]);
  }

    public function delete($salesOrder_id, $invoice_id, $invoiceDetail_id)
    {
      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);
      $item = item::findOrFail($invoiceDetail->item_id);
      $item->stock = $item->stock + $invoiceDetail->item_quantity;
      $item->save();
      $invoiceDetail->delete();
// unutk menambahkan total dari semua invoice detail ke invoice dan sales order
      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      foreach ($invoiceDetails as $invoice_detail) {
        $total = $invoice_detail->total + $total;
      }

      if ($total == 0) {
        // unutk merestore
        $invoiceDetail = invoiceDetail::withTrashed()->findOrFail($invoiceDetail_id);
        $invoiceDetail->restore();

        return redirect()->route('sales.order.bill', ['id' => $salesOrder_id])
        ->with('alert', 'Cannot Delete Item, You Have No Item In Order');
      }

      $invoice = invoice::findOrFail($invoice_id);
      $invoice->total = $total;
      $invoice->save();

      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect()->route('sales.order.bill', ['id' => $salesOrder_id]);
    }

    public function decrease($salesOrder_id, $invoice_id, $invoiceDetail_id)
    {
      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);
      $invoice = invoice::findOrFail($invoice_id);
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $item = item::findOrFail($invoiceDetail->item_id);

      $invoiceDetail->item_quantity = $invoiceDetail->item_quantity - 1;

      if ($invoiceDetail->item_quantity == 0) {
        $invoiceDetail->delete();
      }else {
        $invoiceDetail->total = $invoiceDetail->item_quantity*$item->price;
        $invoiceDetail->save();
      }
// item
      $item->stock = $item->stock + 1;
      $item->save();
// perubahan pada total invoice dan sales order
      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      foreach ($invoiceDetails as $value) {
        $total = $value->total + $total;
      }

      // bila total sama dengan 0 maka akan menghapus otomatis invoice dan sales order
      if ($total == 0) {
        // unutk merestore
        $invoiceDetail = invoiceDetail::withTrashed()->findOrFail($invoiceDetail_id);
        $invoiceDetail->restore();

        // item
        $item->stock = $item->stock - 1;
        $item->save();

        return redirect()->route('sales.order.bill', ['id' => $salesOrder_id])
        ->with('alert', 'Cannot Delete Item, You Have No Item In Order');
      }

// perubahan total invoice
      $invoice->total = $total;
      $invoice->save();
// perubahn tota sales order
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect()->route('sales.order.bill', ['id' => $salesOrder_id]);
    }

    public function increase($salesOrder_id, $invoice_id, $invoiceDetail_id)
    {

      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);
      $invoice = invoice::findOrFail($invoice_id);
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $item = item::findOrFail($invoiceDetail->item_id);
      // mengetahui apakah quantity order lebih dari stcok barang
      if ($item->stock < 1) {
        return redirect()->route('sales.order.bill', ['id' => $salesOrder->id])
        ->with('alert', $item->name.' Out Of Stock');

        throw new \Exception("stock barang telah habis");
      }

      $invoiceDetail->item_quantity = $invoiceDetail->item_quantity + 1;
      $invoiceDetail->total = $invoiceDetail->item_quantity*$item->price;
      $invoiceDetail->save();
// pengutrangn item
      $item->stock = $item->stock - 1;
      $item->save();
      // perubahan pada total invoice dan sales order
      $total = 0;
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice_id);
      foreach ($invoiceDetails as $value) {
        $total = $value->total + $total;
      }
      // perubahan total invoice
      $invoice->total = $total;
      $invoice->save();
      // perubahn tota sales order
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect()->route('sales.order.bill', ['id' => $salesOrder_id]);
    }
}
