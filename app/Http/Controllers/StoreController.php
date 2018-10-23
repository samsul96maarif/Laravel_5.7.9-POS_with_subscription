<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;

use App\Models\Usestore;

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
    $tes = usestore::all();
    dd($tes);
    return view('welcome');
  }

  public function index()
    {
      // $userStore = Te::find(1);
      // dd($userStore->status);
      $user_id = Auth::user()->id;
      // $user_id = 2;
      $usestore = usestore::where('user_id', $user_id)->first();

      $store_id = $usestore->store_id;
      $store = store::where('id', $store_id)->first();

      // dd($store);
      return view('user/store/index', ['store' => $store]);
    }

  // create
// 1. mengarahkan ke form
  public function create(){
    // $store = store::find(1);
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
      'zipcode' => 'required|integer',
    ]);

    $store = new store;
    $store->name = $request->name;
    $store->phone = $request->phone;
    $store->company_address = $request->company_address;
    $store->zipcode = $request->zipcode;
    $store->save();

    // user_stores
    $usestore = new usestore;
    $usestore->user_id = Auth::user()->id;
    $usestore->store_id = $store->id;
    // dd($te->store_id);
    $usestore->save();
    return redirect('/home');
  }

  // 1. store data update
        public function update(Request $request, $id){

          $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric',
            'company_address' => 'required',
            'zipcode' => 'required|integer',
          ]);

          $store = store::find($id);
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
      // dd($store->id);
      $usestore = usestore::where('store_id', $store->id)->first();
      // dd($tes);
      $store->delete();
      $usestore->delete();
      return redirect('/home');
    }
}
