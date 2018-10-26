<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Store;
use App\Models\Item;

class ItemController extends Controller
{

  public function __construct()
  {
      $this->middleware(['auth', 'gate', 'get.subscription']);
  }

  public function index()
  {
    $user_id = Auth::id();
    $store = store::where('user_id', $user_id)->first();
    // dd($store);
    $items = item::all()->where('store_id', $store->id);

    return view('user/item/index', ['items' => $items]);
  }

  public function create()
  {
    return view('user/item/create');
  }

  public function store(Request $request)
  {
    $this->validate($request, [
      'name' => 'required',
      'description' => 'required',
      'stock' => 'required|integer',
      'price' => 'required|integer',
    ]);

    $user_id = Auth::id();

    $store = store::where('user_id', $user_id)->first();
    // dd($store->id);
    $item = new item;
    $item->store_id = $store->id;
    $item->name = $request->name;
    $item->description = $request->description;
    $item->price = $request->price;
    $item->stock = $request->stock;
    $item->save();
    return redirect('/item');
  }

  // update
  // 1. get data melalui id-nya
      public function edit($id){
        $item = item::find($id);
        return view('user/item/edit', ['item' => $item]);
      }
  // 2. store data update
      public function update(Request $request, $id){

        $this->validate($request, [
          'name' => 'required',
          'description' => 'required',
          'stock' => 'required|integer',
          'price' => 'required|integer',
        ]);

        $item = item::find($id);
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->save();
        return redirect('/item');
      }

      // delete
    public function delete($id)
    {
      $item = item::find($id);
      $item->delete();
      return redirect('/item');
    }
}
