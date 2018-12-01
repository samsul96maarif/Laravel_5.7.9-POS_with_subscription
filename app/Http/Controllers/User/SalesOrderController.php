<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// menggunakan auth
use Auth;
use App\Models\SalesOrder;
use App\Models\Item;
use App\Models\Contact;
use App\Models\Organization;
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
      // gate : unutk mengecek apakah sudah membuat Organization
      // getSubscription : unutk mengecek subscription Organization
      // maxOrder : untuk mengcek quote invoice subscription
      // checkitem : mengecek apakah ada item
      // checkContact : mengecek apakah ada contact
        // $this->middleware(['auth', 'gate', 'get.subscription', 'max.order', 'check.item', 'check.contact']);
        $this->middleware(['auth', 'gate', 'get.subscription', 'max.order', 'check.item']);
    }
    public function index()
    {
      $user_id = Auth::id();
      $organization = organization::where('user_id', $user_id)->first();
      $salesOrders = SalesOrder::all()->where('organization_id', $organization->id);
      $contacts = contact::all()->where('organization_id', $organization->id)->where('deleted_at', null);
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
      $organization = organization::where('user_id', $user_id)->first();
      $items = item::all()->where('organization_id', $organization->id);
      $contacts = contact::all()->where('organization_id', $organization->id)->where('deleted_at', null);
      $subscription = subscription::findOrFail($organization->subscription_id);
      $salesOrders = salesOrder::all()->where('organization_id', $organization->id);

      // menghitung jumlah sales order
      $i = 0;
      foreach ($salesOrders as $key) {
        $i++;
      }

      // klo bukan unlimeted
      if ($subscription->num_users != 0) {
        if ($i >= $subscription->num_invoices) {
          return redirect()->route('subscription')
          ->with('alert', 'Quota Sales Order Has Exceeded Capacity, Please Upgrade Your Package');

          throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
        }
      }

      return view('user/sales_order/createInvoice',
      [
        'items' => $items,
        'contacts' => $contacts
      ]);
    }

    public function store(Request $request)
    {

      $user_id = Auth::id();
      $organization = organization::where('user_id', $user_id)->first();
      $subscription = subscription::findOrFail($organization->subscription_id);
      // memanggil semua sales order unutk dihitung
      // sudah berapa sales order yang dimiliki organization
      $salesOrders = salesOrder::all()->where('organization_id', $organization->id);
      // memanggil semua contact unutk dihitung
      // sudah berapa contact yang dimiliki organization
      $contacts = contact::all()->where('organization_id', $organization->id)->where('deleted_at', null);

      if ($request->name == null) {

        $this->validate($request, [
          'contact' => 'required',
        ]);

        // mencari contact yang sesuai request
        $contact = contact::where('name', $request->contact)
        ->where('organization_id', $organization->id)
        ->where('deleted_at', null)
        ->first();

        if ($contact == null) {
          return redirect()->route('sales.order.create')
          ->with('alert', $request->contact.' Not Found');
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

        // klo bukan unlimeted
        if ($subscription->num_users != 0) {
          if ($i >= $subscription->num_users) {
            return redirect()
            ->route('sales.order.create')
            ->with('alert', 'Quota Contact Has Exceeded Capacity, Cannot Add Contact or Please Upgrade Your Packag');
            // throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
          }
        }

        $found = 0;
        $contacts = contact::all();
        foreach ($contacts as $value) {
          if ($value->name === $request->name) {
            $contact = contact::where('name', $value->name)->first();
            $found = 1;
          }
        }

        if ($found == 0) {
          $contact = new contact;
          $contact->organization_id = $organization->id;
          $contact->name = $request->name;
          $contact->phone = $request->phone;
          $contact->company_name = $request->company_name;
          $contact->email = $request->email;
          $contact->save();
        }
      }

      $this->validate($request, [
        'item' => 'required',
        'quantity' => 'required|min:1',
      ]);

    $count = count($request->item);
    $total = 0;
    for ($i=0; $i < $count; $i++) {
      $item = item::where('name', $request->item[$i])
      ->where('organization_id', $organization->id)
      ->first();
      // mengetahui apakah quantity order lebih dari stcok barang
      if ($request->quantity[$i] > $item->stock) {
        return redirect()->route('sales.order.create')
        ->with('alert', $request->item[$i].' Out Of Stock');

        throw new \Exception("quantity lebih banyak dari stock barang");
      }

      $total = $total + $item->price*$request->quantity[$i];
    }

      // sales order
      $salesOrder = new salesOrder;
      $salesOrder->organization_id = $organization->id;
      $salesOrder->contact_id = $contact->id;
      $salesOrder->contact_name = $contact->name;
      $salesOrder->save();
      // sales order
      $salesOrder->total = $total;
      $salesOrder->order_number = 'SO-'.$salesOrder->id;
      $salesOrder->save();

      // invoice
      $invoice = new invoice;
      $invoice->organization_id = $organization->id;
      $invoice->sales_order_id = $salesOrder->id;
      $invoice->contact_id = $contact->id;
      $invoice->contact_name = $contact->name;
      $invoice->save();
      // invoice
      $invoice->total = $total;
      $invoice->number = 'INV-'.$invoice->id;
      $invoice->save();

      // pembuatan invoice detail
      for ($i=0; $i < $count; $i++) {
        $item = item::where('name', $request->item[$i])
        ->where('organization_id', $organization->id)
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
          $message = $item->name.' already exist In sales order '.$salesOrder->order_number.' qty Has been Added';
          // $message = $item->name.' telah ada dalam sales order '.$salesOrder->order_number.' qty  telah ditambahkan';
        } else {
          // invoice detail
          $invoiceDetail = new invoiceDetail;
          $invoiceDetail->organization_id = $organization->id;
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

      return redirect()->route('sales.order.bill', ['id' => $salesOrder->id]);
    }

    public function show($id)
    {
      $user_id = Auth::id();
      $organization = organization::where('user_id', $user_id)->first();
      $salesOrder = salesOrder::findOrFail($id);
      $items = item::all()->where('organization_id', $organization->id);
      $contacts = contact::all()->where('organization_id', $organization->id)->where('deleted_at', null);
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
      $organization = organization::where('user_id', $user_id)->first();
      $salesOrder = salesOrder::findOrFail($id);
      $items = item::all()->where('organization_id', $organization->id);
      $contacts = contact::all()->where('organization_id', $organization->id)->where('deleted_at', null);
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
// untuk update contact/customer
    public function update(Request $request, $id)
    {
      $user_id = Auth::id();
      $organization = organization::where('user_id', $user_id)->first();
      // mencari contact yang sesuai request
      $contact = contact::where('name', $request->contact)
      ->where('organization_id', $organization->id)
      ->where('deleted_at', null)
      ->first();

      if ($contact == null) {
        return redirect()->route('sales.order.bill', ['id' => $id])
        ->with('alert', $request->contact.' Not Found');

        throw new \Exception("kontak tidak ditemukan");
      }

      $salesOrder = salesOrder::findOrFail($id);
      $salesOrder->contact_id = $contact->id;
      $salesOrder->save();

      $invoice = invoice::where('sales_order_id', $salesOrder->id)->first();
      $invoice->contact_id = $contact->id;
      $invoice->save();

      return redirect()
      ->route('sales.order.bill', ['id' => $salesOrder->id])
      ->withSuccess('Succeed Updated Invoice');
    }
// ketika cancel atau delete sales order
    public function delete($id)
    {
      $salesOrder = salesOrder::findOrFail($id);
      $invoice = invoice::where('sales_order_id', $id)->first();
      $invoiceDetails = invoiceDetail::all()->where('invoice_id', $invoice->id);

      foreach ($invoiceDetails as $invoiceDetail) {
        $invoiceDetail->delete();
      }

      $invoice->delete();
      $salesOrder->delete();

      return redirect('/sales_orders')->withSuccess($salesOrder->order_number.' Deleted!');
    }

    public function search(Request $request)
    {

      $id = Auth::id();
      $organization = organization::where('user_id', $id)->first();
      $contacts = contact::all()
      ->where('organization_id', $organization->id);

      $invoices = invoice::all();
      $invoiceDetails = invoiceDetail::all();

      $salesOrders = DB::table('sales_orders')
                      ->where('order_number', 'like', '%'.$request->q.'%')
                      ->where('organization_id', $organization->id)
                      ->where('deleted_at', null)
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
