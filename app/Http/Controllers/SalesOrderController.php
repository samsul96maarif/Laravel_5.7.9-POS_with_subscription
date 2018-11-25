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
      $contacts = contact::all()->where('store_id', $store->id)->where('deleted_at', null);
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
      $contacts = contact::all()->where('store_id', $store->id)->where('deleted_at', null);

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
      // memanggil semua contact unutk dihitung
      // sudah berapa contact yang dimiliki store
      $contacts = contact::all()->where('store_id', $store->id)->where('deleted_at', null);

      if ($request->name == null) {

        $this->validate($request, [
          'contact' => 'required',
        ]);

        // mencari contact yang sesuai request
        $contact = contact::where('name', $request->contact)
        ->where('store_id', $store->id)
        ->where('deleted_at', null)
        ->first();

        if ($contact == null) {
          throw new \Exception("kontak tidak ditemukan");
        }
      } else {
        $this->validate($request, [
          'name' => 'required',
        ]);

        if ($request->phone != null) {
          $this->validate($request, [
            'phone' => 'numeric',
          ]);
        }

        if ($request->email != null) {
          $this->validate($request, [
            'email' => 'string|email|max:255|unique:users',
          ]);
        }
        // mengcek apakah contact ovelrload dari package
        $i = 0;
        foreach ($contacts as $key) {
          $i++;
        }

        if ($i >= $subscription->num_users) {
          throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
        }

        $contact = new contact;
        $contact->store_id = $store->id;
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->company_name = $request->company_name;
        $contact->email = $request->email;
        $contact->save();
      }

      $this->validate($request, [
        'item' => 'required',
        'quantity' => 'required|min:1',
      ]);

      // menghitung jumlah sales order
      $i = 0;
      foreach ($salesOrders as $key) {
        $i++;
      }

      if ($i >= $subscription->num_invoices) {
        throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
      }

    $count = count($request->item);
    $total = 0;
    for ($i=0; $i < $count; $i++) {
      $item = item::where('name', $request->item[$i])
      ->where('store_id', $store->id)
      ->first();
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
        $item = item::where('name', $request->item[$i])
        ->where('store_id', $store->id)
        ->first();
        // mengecek apakah item sudah ada di invoice detail
        $invoiceDetail = invoiceDetail::where('invoice_id', $invoice->id)
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
          $invoiceDetail->invoice_id = $invoice->id;
          $invoiceDetail->item_id = $item->id;
          $invoiceDetail->item_price = $item->price;
          $invoiceDetail->item_quantity = $request->quantity[$i];
          $invoiceDetail->total = $item->price*$request->quantity[$i];
          $invoiceDetail->save();
        }

        $item->stock = $item->stock - $request->quantity[$i];
        $item->save();
      }

      return redirect()->route('sales_order_bill', ['id' => $salesOrder->id]);
      return redirect('/sales_order')->with('alert', 'Succeed Add Invoice');
    }

    public function show($id)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $salesOrder = salesOrder::findOrFail($id);
      $items = item::all()->where('store_id', $store->id);
      $contacts = contact::all()->where('store_id', $store->id)->where('deleted_at', null);
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

    public function bill($id)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $salesOrder = salesOrder::findOrFail($id);
      $items = item::all()->where('store_id', $store->id);
      $contacts = contact::all()->where('store_id', $store->id)->where('deleted_at', null);
      $invoice = invoice::where('sales_order_id', $salesOrder->id)->first();
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice->id);

      return view('user/sales_order/bill',
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
      $contacts = contact::all()->where('store_id', $store->id)->where('deleted_at', null);
      $salesOrder = salesOrder::findOrFail($id);

      return view('user/sales_order/edit',
      [
        'contacts' => $contacts,
        'salesOrder' => $salesOrder
      ]);
    }

    public function update(Request $request, $id)
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      // mencari contact yang sesuai request
      $contact = contact::where('name', $request->contact)
      ->where('store_id', $store->id)
      ->where('deleted_at', null)
      ->first();

      if ($contact == null) {
        throw new \Exception("kontak tidak ditemukan");
      }

      $salesOrder = salesOrder::findOrFail($id);
      $salesOrder->contact_id = $contact->id;
      $salesOrder->save();

      $invoice = invoice::where('sales_order_id', $salesOrder->id)->first();
      $invoice->contact_id = $contact->id;
      $invoice->save();

      return redirect()->route('sales_order_bill', ['id' => $salesOrder->id])->with('alert', 'Succeed Updated Invoice');
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
      $contacts = contact::all()->where('store_id', $store->id)->with('alert', 'Succeed Updated Invoice');
      $invoices = invoice::all();
      $invoiceDetails = invoiceDetail::all();

      $salesOrders = DB::table('sales_orders')
                      ->where('order_number', 'like', '%'.$request->q.'%')
                      ->where('store_id', $store->id)
                      // ->where('deleted_at', null)
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
