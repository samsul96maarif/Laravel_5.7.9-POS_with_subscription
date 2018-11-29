<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\Subscription;
// unutk menggunakan auth
use Auth;

class StoreController extends Controller
{

  public function __construct()
  {
    // auth : unutk mengecek auth
      $this->middleware('auth');
  }

  public function index()
    {
      $user_id = Auth::id();
      $store = store::where('user_id', $user_id)->first();
      $subscription = subscription::where('id', $store->subscription_id)->first();

      return view('user/store/index',
      [
        'store' => $store,
        'subscription' => $subscription
      ]);
    }

  // create
// 1. mengarahkan ke form
  public function create(){

    // bila admin di alihkan ke hal admin
    if (Auth::user()->isAdmin()) {
      return redirect('/admin');
    }
    return view('user/store/create');
  }
  // 2.store data
  public function store(Request $request)
  {

    $user_id = Auth::id();
    // contoh penggunaan validate dimana :
    // 1. value name required
    $this->validate($request, [
      'name' => 'required',
      'phone' => 'required|numeric',
      'company_address' => 'required',
      'zipcode' => 'required|numeric',
    ]);

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

    return redirect('/home')->withSuccess('Company Profile Has been Created.');
  }

  // 1. store data update
        public function update(Request $request, $id)
        {

          $store = store::find($id);

          $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric',
            'company_address' => 'required',
            'zipcode' => 'required|numeric',
          ]);


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

          return redirect('/home')->withSuccess('Company Profile Has been Updated.');
        }

        // delete
        public function delete($id)
        {
          $store = store::find($id);
          $store->delete();

          return redirect('/home');
        }
}
