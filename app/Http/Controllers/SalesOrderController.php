<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
// menggunakan auth
use Auth;
use App\Models\SalesOrder;
use App\Models\Item;
use App\Models\Contact;
use App\Models\Store;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Subscription;
// menggunakan db builder
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
    public function __construct()
    {
      // auth : unutk mengecek auth
      // gate : unutk mengecek apakah sudah membuat store
      // getSubscription : unutk mengecek subscription store
      // maxOrder : untuk mengcek quote invoice subscription
      // checkitem : mengecek apakah ada item
      // checkContact : mengecek apakah ada contact
        $this->middleware(['auth', 'gate', 'get.subscription', 'max.order', 'check.item', 'check.contact']);
    }
    public function index()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $salesOrders = SalesOrder::all()->where('store_id', $store->id);
      $contacts = contact::all()->where('store_id', $store->id);
      $invoices = invoice::all();
      $invoiceDetails = invoiceDetail::all();

      return view('user/sales_order/index',
      [
        'salesOrders' => $salesOrders,
        'contacts' => $contacts,
        'invoiceDetails' => $invoiceDetails,
        'invoices' => $invoices
      ]);
    }

    public function create()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $items = item::all()->where('store_id', $store->id);
      $contacts = contact::all()->where('store_id', $store->id);

      return view('user/sales_order/createInvoice',
      [
        'items' => $items,
        'contacts' => $contacts
      ]);
    }

    public function store(Request $request)
    {

      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $subscription = subscription::findOrFail($store->subscription_id);
      $salesOrders = salesOrder::all()->where('store_id', $store->id);

      $this->validate($request, [
        'item_id' => 'required',
        'quantity' => 'required|integer|min:1',
        'contact_id' => 'required',
      ]);

      $i = 0;
      foreach ($salesOrders as $key) {
        $i++;
      }

      if ($i >= $subscription->num_invoices) {
        throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
      }

      $item = item::find($request->item_id);

      if ($request->quantity > $item->stock) {
        throw new \Exception("quantity lebih banyak dari stock barang");
        return redirect('/sales_order/create');
      }

      $price = $item->price;
      $total = $item->price*$request->quantity;

      // sales order
      $salesOrder = new salesOrder;
      $salesOrder->store_id = $store->id;
      $salesOrder->contact_id = $request->contact_id;
      $salesOrder->save();
      // sales order
      $salesOrder->total = $total;
      $salesOrder->order_number = 'SO-'.$salesOrder->id;
      $salesOrder->save();

      // invoice
      $invoice = new invoice;
      $invoice->store_id = $store->id;
      $invoice->sales_order_id = $salesOrder->id;
      $invoice->contact_id = $request->contact_id;
      $invoice->save();
      // invoice
      $invoice->total = $total;
      $invoice->number = 'INV-'.$invoice->id;
      $invoice->save();

      // invoice detail
      $invoiceDetail = new invoiceDetail;
      $invoiceDetail->store_id = $store->id;
      $invoiceDetail->invoice_id = $invoice->id;
      $invoiceDetail->item_id = $request->item_id;
      $invoiceDetail->item_price = $price;
      $invoiceDetail->item_quantity = $request->quantity;
      $invoiceDetail->total = $total;
      $invoiceDetail->save();

      $item->stock = $item->stock - $request->quantity;
      $item->save();

      return redirect('/sales_order')->with('alert', 'Succeed Add Invoice');
    }

    public function show($id)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $salesOrder = salesOrder::findOrFail($id);
      $items = item::all()->where('store_id', $store->id);
      $contacts = contact::all()->where('store_id', $store->id);
      $invoice = invoice::where('sales_order_id', $salesOrder->id)->first();
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice->id);

      return view('user/sales_order/detail',
      [
        'items' => $items,
        'contacts' =>$contacts,
        'salesOrder' => $salesOrder,
        'invoice' => $invoice,
        'invoiceDetails' => $invoiceDetails
      ]);
    }

    public function edit($id)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $contacts = contact::all()->where('store_id', $store->id);
      $salesOrder = salesOrder::findOrFail($id);

      return view('user/sales_order/edit',
      [
        'contacts' => $contacts,
        'salesOrder' => $salesOrder
      ]);
    }

    public function update(Request $request, $id)
    {
      $salesOrder = salesOrder::findOrFail($id);
      $salesOrder->contact_id = $request->contact_id;
      $salesOrder->save();

      $invoice = invoice::where('sales_order_id', $salesOrder->id)->first();
      $invoice->contact_id = $request->contact_id;
      $invoice->save();

      return redirect('/sales_order')->with('alert', 'Succeed Updated Invoice');
    }

    public function delete($id)
    {
      $salesOrder = salesOrder::findOrFail($id);
      $salesOrder->delete();

      return redirect('/sales_order')->with('alert', $salesOrder->name.' Deleted!');
    }

    public function search(Request $request)
    {

      $id = Auth::id();
      $store = store::where('user_id', $id)->first();
      $contacts = contact::all()->where('store_id', $store->id);
      $invoices = invoice::all();
      $invoiceDetails = invoiceDetail::all();

      $salesOrders = DB::table('sales_orders')
                      ->where('order_number', 'like', '%'.$request->q.'%')
                      ->where('store_id', $store->id)
                      ->get();

      return view('user/sales_order/index',
      [
        'salesOrders' => $salesOrders,
        'contacts' => $contacts,
        'invoiceDetails' => $invoiceDetails,
        'invoices' => $invoices
      ]);
    }
}
