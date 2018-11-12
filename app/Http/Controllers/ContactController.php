<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\Store;
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
    // gate : unutk mengecek apakah sudah membuat store
    // getSubscription : unutk mengecek subscription store
    // maxContact : unutk mengecek quota user subscription
      $this->middleware(['auth', 'gate', 'get.subscription', 'max.contact']);
  }

    public function index()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $contacts = contact::all()->where('store_id', $store->id);

      return view('user/contact/index', ['contacts' => $contacts]);
    }

    public function create()
    {
      return view('user/contact/create');
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required',
        'phone' => 'required|numeric',
        'company_name' => 'required',
        'email' => 'required|string|email|max:255|unique:users',
      ]);

      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $subscription = subscription::findOrFail($store->subscription_id);
      $contacts = contact::all()->where('store_id', $store->id);

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

      return redirect('/contact')->with('alert', 'Succeed Add Contact');
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
            'phone' => 'required|numeric',
            'company_name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
          ]);

          $contact = contact::find($id);
          $contact->name = $request->name;
          $contact->phone = $request->phone;
          $contact->company_name = $request->company_name;
          $contact->email = $request->email;
          $contact->save();

          return redirect('/contact')->with('alert', 'Succeed Updated '.$contact->name);
        }

        // delete
      public function delete($id)
      {
        $contact = contact::find($id);
        $invoice = invoice::where('contact_id', $id)->first();

        if ($invoice == null) {
          $contact->delete();
          return redirect('/contact')->with('alert', $contact->name.' Deleted!');
        }
        throw new \Exception("contact telah digunakan pada invoice, silahkan hapus invoice terlebih dahulu");
      }

      public function search(Request $request)
      {
        $id = Auth::id();
        $store = store::where('user_id', $id)->first();

        $contacts = DB::table('contacts')
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->where('store_id', $store->id)
                        ->get();

        return view('user/contact/index', ['contacts' => $contacts]);
      }
}
