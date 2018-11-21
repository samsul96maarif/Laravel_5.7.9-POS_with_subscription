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
        // $this->middleware(['auth', 'gate', 'get.subscription', 'max.order', 'check.item', 'check.contact']);
        $this->middleware(['auth', 'gate', 'get.subscription', 'max.order', 'check.item']);
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

      // tes autocompleate 21 nov
      // return view('tes/autocompleate21');

      // tes autocompleate api jqueryui
      // return view('tes/autocompleate');
      // end tes jquery

      // tes javasript
      // return view('tes/createInvoice',
      // [
      //   'items' => $items,
      //   'contacts' => $contacts
      // ]);
      // end tes
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
      // memanggil semua sales order unutk dihitung
      // sudah berapa sales order yang dimiliki store
      $salesOrders = salesOrder::all()->where('store_id', $store->id);

      $this->validate($request, [
        // 'item_id' => 'required',
        // 'quantity' => 'required|integer|min:1',
        'contact' => 'required',
      ]);
      // menghitung jumlah sales order
      $i = 0;
      foreach ($salesOrders as $key) {
        $i++;
      }

      if ($i >= $subscription->num_invoices) {
        throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
      }

    // mencari contact yang sesuai request
    $contact = contact::where('name', $request->contact)->first();
    if ($contact == null) {
      throw new \Exception("kontak tidak ditemukan");
    }

    $count = count($request->item);
    $total = 0;
    for ($i=0; $i < $count; $i++) {
      $item = item::where('name', $request->item[$i])->first();
      // mengetahui apakah quantity order lebih dari stcok barang
      if ($request->quantity[$i] > $item->stock) {
        throw new \Exception("quantity lebih banyak dari stock barang");
        return redirect('/sales_order/create');
      }

      $total = $total + $item->price*$request->quantity[$i];
    }

      // sales order
      $salesOrder = new salesOrder;
      $salesOrder->store_id = $store->id;
      $salesOrder->contact_id = $contact->id;
      $salesOrder->save();
      // sales order
      $salesOrder->total = $total;
      $salesOrder->order_number = 'SO-'.$salesOrder->id;
      $salesOrder->save();

      // invoice
      $invoice = new invoice;
      $invoice->store_id = $store->id;
      $invoice->sales_order_id = $salesOrder->id;
      $invoice->contact_id = $contact->id;
      $invoice->save();
      // invoice
      $invoice->total = $total;
      $invoice->number = 'INV-'.$invoice->id;
      $invoice->save();
      // pembuatan invoice detail
      for ($i=0; $i < $count; $i++) {
        $item = item::where('name', $request->item[$i])->first();
        // invoice detail
        $invoiceDetail = new invoiceDetail;
        $invoiceDetail->store_id = $store->id;
        $invoiceDetail->invoice_id = $invoice->id;
        $invoiceDetail->item_id = $item->id;
        $invoiceDetail->item_price = $item->price;
        $invoiceDetail->item_quantity = $request->quantity[$i];
        $invoiceDetail->total = $item->price*$request->quantity[$i];
        $invoiceDetail->save();

        $item->stock = $item->stock - $request->quantity[$i];
        $item->save();
      }

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
