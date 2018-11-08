<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Subscription;

use Auth;

class StoreController extends Controller
{

  public function __construct()
  {
      $this->middleware('auth');
  }

  public function home()
  {
    // $user_store = User_Store::where('user_id', )
    $tes = store::all();
    dd($tes);
    return view('welcome');
  }

  public function index()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $subscription = subscription::where('id', $store->subscription_id)->first();
      // dd($subscription);
      // dd($store->subscription_id->name);
      // dd($store);
      return view('user/store/index',
      [
        'store' => $store,
        'subscription' => $subscription
      ]);
    }

  // create
// 1. mengarahkan ke form
  public function create(){
    // $store = store::find(1);
    if (Auth::user()->isAdmin()) {
      return redirect('/admin');
    }
    return view('user/store/create');
  }
  // 2.store data
  public function store(Request $request){
    // contoh penggunaan validate dimana :
    // 1. value name required
    $this->validate($request, [
      'name' => 'required',
      'phone' => 'required|numeric',
      'company_address' => 'required',
      'zipcode' => 'required|numeric',
    ]);

    $user_id = Auth::id();
    $store = new store;

    if ($request->file('logo') == "") {
        // code...
      } else {
        // menyimpan nilai image
        $file = $request->file('logo');
        // mengambil nama file
        $fileName = $file->getClientOriginalName();
        // menyimpan file image kedalam folder "img"
        $request->file('logo')->move("logo/",$fileName);
        // menyimpan ke dalam database nama file dari image
        $store->logo = $fileName;
      }

    $store->user_id = $user_id;
    $store->name = $request->name;
    $store->phone = $request->phone;
    $store->company_address = $request->company_address;
    $store->zipcode = $request->zipcode;
    $store->save();
    return redirect('/home');
  }

  // 1. store data update
        public function update(Request $request, $id){

          $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric',
            'company_address' => 'required',
            'zipcode' => 'required|numeric',
          ]);

          $store = store::find($id);

          if ($request->file('logo') == "") {
              // code...
            } else {
              // menyimpan nilai image
              $file = $request->file('logo');
              // mengambil nama file
              $fileName = $file->getClientOriginalName();
              // menyimpan file image kedalam folder "img"
              $request->file('logo')->move("logo/",$fileName);
              // menyimpan ke dalam database nama file dari image
              $store->logo = $fileName;
            }

          $store->name = $request->name;
          $store->phone = $request->phone;
          $store->company_address = $request->company_address;
          $store->zipcode = $request->zipcode;
          $store->save();
          return redirect('/home');
        }

        // delete
        public function delete($id)
        {
          $store = store::find($id);
          $store->delete();
          return redirect('/home');
        }
}
