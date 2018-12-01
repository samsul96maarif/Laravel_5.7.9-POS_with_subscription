<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\Invoice;
// unutk menggunakan db builder
use Illuminate\Support\Facades\DB;
// untuk menggunakan auth
use Auth;

class ContactController extends Controller
{

  public function __construct()
  {
    // auth : unutk mengecek auth
    // gate : unutk mengecek apakah sudah membuat Organization
    // getSubscription : unutk mengecek subscription Organization
    // maxContact : unutk mengecek quota user subscription
      $this->middleware(['auth', 'gate', 'get.subscription', 'max.contact']);
  }

    public function index()
    {
      $user_id = Auth::id();
      $organization = organization::where('user_id', $user_id)->first();
      $data = contact::all()
      ->where('organization_id', $organization->id)
      ->where('deleted_at', null);

      if(count($data) > 0){ //mengecek apakah data kosong atau tidak
          $res['message'] = "Success!";
          $res['values'] = $data;
          // return response($res);
      }
      else{
          $res['message'] = "Empty!";
          $res['values'] = $data;
          // return response($res);
      }
      $contacts = json_decode($res['values']);

      return view('user/contact/index', ['contacts' => $contacts]);
    }

    public function create()
    {
      return view('user/contact/create');
    }

    public function store(Request $request)
    {
      $user_id = Auth::id();
      $organization = organization::where('user_id', $user_id)->first();
      $subscription = subscription::findOrFail($organization->subscription_id);

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

      $contacts = contact::all()->where('organization_id', $organization->id);

      // mengecek apakan contact dengan nama yang dimasukkan sudah ada
      foreach ($contacts as $value) {
        if ($request->name === $value->name) {
          return redirect()
          ->route('contact.create')
          ->with('alert', 'Failed Add Contact, Name '.$request->name.' Already Exist, In Your Contact');
        }
      }

      $i = 0;
      foreach ($contacts as $key) {
        $i++;
      }

      // klo bukan unlimeted
      if ($subscription->num_users != 0) {
        if ($i >= $subscription->num_users) {
          return redirect()
          ->route('contact')
          ->with('alert', 'Quota Contact Has Exceeded Capacity, Please Upgrade Your Package');
          // throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
        }
      }


      $contact = new contact;
      $contact->organization_id = $organization->id;
      $contact->name = $request->name;
      $contact->phone = $request->phone;
      $contact->company_name = $request->company_name;
      $contact->email = $request->email;
      $contact->save();

      return redirect('/contact')->withSuccess('Succeed Add Contact');
    }

    // update
    // 1. get data melalui id-nya
    public function edit($id){
      $contact = contact::find($id);

      return view('user/contact/edit', ['contact' => $contact]);
    }
    // 2. store data update
    public function update(Request $request, $id){

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

      $contact = contact::find($id);

    // mengecek apakan contact dengan nama yang dimasukkan sudah ada
      $user = Auth::user();
      $organization = organization::where('user_id', $user->id)->first();
      $contacts = contact::all()->where('organization_id', $organization->id);
      foreach ($contacts as $value) {
        if ($request->name === $value->name && $request->name != $contact->name) {

          return redirect()
          ->route('contact.edit', ['id' => $id])
          ->with('alert', 'Failed Update Contact, Name '.$request->name.' Already Exist, In Your Contact');
        }
      }

      $nameBefore = $contact->name;

      $contact->name = $request->name;
      $contact->phone = $request->phone;
      $contact->company_name = $request->company_name;
      $contact->email = $request->email;
      $contact->save();

          // mengarahkan kembali ke contact_detail
        return redirect()->route('contact.edit', [$id])
        ->withSuccess('Succeed Updated '.$nameBefore);
      }

        // delete
      public function delete($id)
      {
        $contact = contact::find($id);
        $invoice = invoice::where('contact_id', $id)->first();

        if ($invoice == null) {
          $contact->delete();
          return redirect('/contacts')->withSuccess($contact->name.' Deleted!');
        }
        // mengarahkan kembali ke contact
        return redirect()->route('contacts')
        ->with('alert', 'Failed, Contact '.$contact->name.' Already Used In Invoice, Please Delete Invoice First');
        throw new \Exception("contact telah digunakan pada invoice, silahkan hapus invoice terlebih dahulu");
      }

      public function search(Request $request)
      {
        $id = Auth::id();
        $organization = organization::where('user_id', $id)->first();

        $contacts = DB::table('contacts')
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->where('organization_id', $organization->id)
                        ->where('deleted_at', null)
                        ->get();

        return view('user/contact/index', ['contacts' => $contacts]);
      }
}
