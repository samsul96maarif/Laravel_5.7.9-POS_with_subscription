<?php

namespace App\Http\Controllers;

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

    public function create($salesOrder_id, $invoice_id)
    {
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
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
          throw new \Exception("quantity lebih banyak dari stock barang");
          return redirect('/sales_order/create');
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
          $message = $item->name.' telah ada dalam sales order '.$salesOrder->order_number.' qty  telah ditambahkan';
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

      return redirect()->route('sales_order_bill', ['id' => $salesOrder->id])->with('alert', $message);
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
// mengecek bila quantity lebih sedikit dari sebelumnya
// maka quantity item ditambah dengan selisih
      if ($request->quantity_old > $request->quantity) {
        $addStock = $request->quantity_old - $request->quantity;
        $item->stock = $item->stock + $addStock;
        $item->save();
        // bila lebih sedikit maka akan dikurang dengan selisihnya
      } else {
        $minStock = $request->quantity-$request->quantity_old;
        $item->stock = $item->stock - $minStock;
        // mengecek bila hasil selisih kurang dari 0 maka tidak bisa
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

      $invoice = invoice::findOrFail($invoice_id);
      $invoice->total = $total;
      $invoice->save();

      $salesOrder = salesOrder::findOrFail($request->salesOrder_id);
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect('/sales_order')->with('alert', 'Succeed Updated Invoice');
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

        return redirect()->route('sales_order_bill', ['id' => $salesOrder_id])
        ->withSuccess('Cannot delete item, you have no item on order');
      }

      $invoice = invoice::findOrFail($invoice_id);
      $invoice->total = $total;
      $invoice->save();

      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect()->route('sales_order_bill', ['id' => $salesOrder_id]);
      return redirect('/sales_order')->with('alert', 'Succeed Updated Invoice');
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

        return redirect()->route('sales_order_bill', ['id' => $salesOrder_id])
        ->withSuccess('Cannot delete item, you have no item on order');
      }

// perubahan total invoice
      $invoice->total = $total;
      $invoice->save();
// perubahn tota sales order
      $salesOrder->total = $total;
      $salesOrder->save();

      return redirect()->route('sales_order_bill', ['id' => $salesOrder_id]);
    }

    public function increase($salesOrder_id, $invoice_id, $invoiceDetail_id)
    {

      $invoiceDetail = invoiceDetail::findOrFail($invoiceDetail_id);
      $invoice = invoice::findOrFail($invoice_id);
      $salesOrder = salesOrder::findOrFail($salesOrder_id);
      $item = item::findOrFail($invoiceDetail->item_id);
      // mengetahui apakah quantity order lebih dari stcok barang
      if ($item->stock < 1) {
        throw new \Exception("stock barang telah habis");
        return redirect('/sales_order/create');
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

      return redirect()->route('sales_order_bill', ['id' => $salesOrder_id]);
    }
}
