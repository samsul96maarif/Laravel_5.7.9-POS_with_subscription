<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\Store;
use App\Models\Subscription;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

use Auth;

class ContactController extends Controller
{

  public function __construct()
  {
      $this->middleware(['auth', 'gate', 'get.subscription', 'max.contact']);
  }

    public function index()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      // dd($store_id);
      $contacts = contact::all()->where('store_id', $store->id);
      // dd($contacts);

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

      // dd($i);
      if ($i >= $subscription->num_users) {
        throw new \Exception("kuota sales order telah melebihi kapasitas, silahkan upgrade paket");
      }

      // dd($store->id);
      $contact = new contact;
      $contact->store_id = $store->id;
      $contact->name = $request->name;
      $contact->phone = $request->phone;
      $contact->company_name = $request->company_name;
      $contact->email = $request->email;
      $contact->save();
      return redirect('/contact');
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
          return redirect('/contact');
        }

        // delete
      public function delete($id)
      {
        $contact = contact::find($id);
        $invoice = invoice::where('contact_id', $id)->first();
        // dd($invoice);
        if ($invoice == null) {
          // dd($contact);
          $contact->delete();
          return redirect('/contact');
        }
        throw new \Exception("contact telah digunakan pada invoice, silahkan hapus invoice terlebih dahulu");
      }

      public function search(Request $request)
      {
        $id = Auth::id();
        // dd($user);
        $store = store::where('user_id', $id)->first();

        $contacts = DB::table('contacts')
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->where('store_id', $store->id)
                        ->get();

        return view('user/contact/index', ['contacts' => $contacts]);
      }
}
