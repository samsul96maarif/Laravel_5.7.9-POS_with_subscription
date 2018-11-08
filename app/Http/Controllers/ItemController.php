<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Store;
use App\Models\Item;
use App\Models\ItemMedias;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{

  public function __construct()
  {
    // auth : unutk mengecek auth
    // gate : unutk mengecek apakah sudah membuat store
    // getSubscription : unutk mengecek subscription store
      $this->middleware(['auth', 'gate', 'get.subscription']);
  }

  public function index()
  {
    $user_id = Auth::id();
    $store = store::where('user_id', $user_id)->first();
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

    $item = new item;
    $item->store_id = $store->id;
    $item->name = $request->name;
    $item->description = $request->description;
    $item->price = $request->price;
    $item->stock = $request->stock;
    $item->save();

    if ($request->file('image') == "") {
      // code...
    } else {
      // menyimpan nilai image
      $file = $request->file('image');
      // mengambil nama file
      $fileName = $file->getClientOriginalName();
      // menyimpan file image kedalam folder "img"
      $request->file('image')->move("img/",$fileName);
      // menyimpan ke dalam database nama file dari image
      $itemMedia = new itemMedias;
      $itemMedia->item_id = $item->id;
      $itemMedia->image = $fileName;
      $itemMedia->save();
    }

    return redirect('/item');
  }

  // update
  // 1. get data melalui id-nya
      public function edit($id){
        $item = item::find($id);
        $itemMedias = itemMedias::where('item_id', $id)->get();

        return view('user/item/edit',
        [
          'item' => $item,
          'itemMedias' => $itemMedias
        ]);
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

        if ($request->file('image') == "") {
          // code...
        } else {
          // menyimpan nilai image
          $file = $request->file('image');
          // mengambil nama file
          $fileName = $file->getClientOriginalName();
          // menyimpan file image kedalam folder "img"
          $request->file('image')->move("img/",$fileName);
          // menyimpan ke dalam database nama file dari image
          $itemMedia = itemMedias::where('item_id', $id)->first();
          // dd($itemMedia);

          if ($itemMedia == null) {
            $itemMedia = new itemMedias;
            $itemMedia->item_id = $id;
            $itemMedia->image = $fileName;
            $itemMedia->save();
          } else {
            $itemMedia->image = $fileName;
            $itemMedia->save();
          }

        }
        return redirect('/item');
      }

      // delete
    public function delete($id)
    {
      $item = item::find($id);
      $item->delete();
      return redirect('/item');
    }

    public function search(Request $request)
    {
      $id = Auth::id();
      $store = store::where('user_id', $id)->first();

      $items = DB::table('items')
                      ->where('name', 'like', '%'.$request->q.'%')
                      ->where('store_id', $store->id)
                      ->get();

      return view('user/item/index', ['items' => $items]);
    }
}
