<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Store;
use App\Models\Item;
use App\Models\ItemMedias;
use App\Models\InvoiceDetail;

use App\Cores\Jsonable;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;

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
    // return $this->json(Response::HTTP_OK, "Fetch Item", $items);
  }

  public function create()
  {
    // tes
    // return view('tes/item/create');

    return view('user/item/create');
  }

  public function store(Request $request)
  {
    // https://stackoverflow.com/questions/14558343/how-to-remove-dots-from-numbers
    $strPrice = str_replace(".", "", $request->price);
    $strStock = str_replace(".", "", $request->stock);
    // convert to integer
    $intPrice = (int)$strPrice;
    $intStock = (int)$request->stock;

    $request->price = $intPrice;
    $request->stock = $intStock;

    $this->validate($request, [
      'name' => 'required',
      'description' => 'required',
    ]);

    if ($request->price > 1) {
      // bila price lebih dari satu lanjut ke stock
      if ($request->stock > 1) {
        // bila stock lebih dari satu lewat
      } else {
        $this->validate($request, [
          'stock' => 'required|integer',
        ]);
      }

    } else {

      $this->validate($request, [
        'stock' => 'required',
        'price' => 'required|integer',
      ]);
    }

    $user_id = Auth::id();
    $store = store::where('user_id', $user_id)->first();

    // mengecek agar tidak ada duplikasi nama item
    $items = item::all()->where('store_id', $store->id);
    foreach ($items as $value) {
      if ($value->name == $request->name) {
        return redirect()
        ->route('item.create')
        ->with('alert', 'Failed Add Item, Name '.$request->name.' Already Exist, In Your Inventory');
      }
    }

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

    return redirect('/item')->withSuccess('Succeed Add Item');
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

        // https://stackoverflow.com/questions/14558343/how-to-remove-dots-from-numbers
        $strPrice = str_replace(".", "", $request->price);
        $strStock = str_replace(".", "", $request->stock);
        // convert to integer
        $intPrice = (int)$strPrice;
        $intStock = (int)$strStock;

        $request->price = $intPrice;
        $request->stock = $intStock;

        $this->validate($request, [
          'name' => 'required',
          'description' => 'required',
        ]);

        if ($request->price > 1) {

          if ($request->stock > 1) {

          } else {
            $this->validate($request, [
              'stock' => 'required|integer',
            ]);
          }

        } else {
          $this->validate($request, [
            'stock' => 'required',
            'price' => 'required|integer',
          ]);
        }

        $item = item::find($id);
        // pengecekan agar tidak ada nama yang sama pada item
        $user = Auth::user();
        $store = store::where('user_id', $user->id)->first();
        $items = item::all()->where('store_id', $store->id);
        foreach ($items as $value) {
          if ($request->name == $value->name && $request->name != $item->name) {

            return redirect()
            ->route('item.edit', ['id' => $id])
            ->with('alert', 'Failed Update Item, Name '.$request->name.' Already Exist, In Your Inventory');
          }
        }

        $nameBefore = $item->name;

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
        return redirect('/item')->withSuccess('Succeed Updated '.$nameBefore);
      }

      // delete
    public function delete($id)
    {
      $item = item::find($id);
      // mencari tahu apakah item telah digunakan sebelumnya
      $invoiceDetail = invoiceDetail::where('item_id', $id)->first();

      if ($invoiceDetail == null) {
        $item->delete();
        return redirect('/item')->withSuccess($item->name.' Deleted!');
      }

      // mengarahkan kembali ke item
      return redirect()->route('item')
      ->with('alert', 'Failed, Item '.$item->name.' Already Used In Invoice, Please Delete Invoice First');
      throw new \Exception("contact telah digunakan pada invoice, silahkan hapus invoice terlebih dahulu");
    }

    public function search(Request $request)
    {
      $id = Auth::id();
      $store = store::where('user_id', $id)->first();

      $items = DB::table('items')
                      ->where('name', 'like', '%'.$request->q.'%')
                      ->where('store_id', $store->id)
                      ->where('deleted_at', null)
                      ->get();

      return view('user/item/index', ['items' => $items]);
    }
}
