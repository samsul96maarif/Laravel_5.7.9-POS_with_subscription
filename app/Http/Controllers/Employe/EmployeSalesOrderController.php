<?php

namespace App\Http\Controllers\Employe;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Organization;
use App\Models\SalesOrder;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\InvoiceDetail;

class EmployeSalesOrderController extends Controller
{

  public function index()
  {
    $user = Auth::user();
    $organization = organization::findOrFail($user->organization_id);
    $salesOrders = SalesOrder::all()->where('organization_id', $organization->id)
    ->where('writer_id', $user->id);
    $contacts = contact::all()->where('organization_id', $organization->id)->where('deleted_at', null);
    $invoices = invoice::all();
    $invoiceDetails = invoiceDetail::all();

    return view('employe/sales_order/index',
    [
      'salesOrders' => $salesOrders,
      'organization' => $organization,
      'contacts' => $contacts,
      'invoiceDetails' => $invoiceDetails,
      'invoices' => $invoices
    ]);
  }

}
