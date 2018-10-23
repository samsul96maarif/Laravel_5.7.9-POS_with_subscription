<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Models\Constore;
use App\Models\Usestore;

use Auth;

class ContactController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

    public function index()
    {
      $user_id = Auth::id();
      // dd($user_id);

      // user_stores
      $user_store = usestore::where('user_id', $user_id)->first();
      $store_id = $user_store->store_id;
      // dd($store_id);

      $contacts = contact::all()->where('store_id', $store_id);
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

      $user_store = usestore::where('user_id', $user_id)->first();
      // dd($user_store->store_id);
      $contact = new contact;
      $contact->store_id = $user_store->store_id;
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
        $contact->delete();
        return redirect('/contact');
      }
}
