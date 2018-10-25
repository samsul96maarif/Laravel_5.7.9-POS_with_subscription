<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Store;

use Carbon\Carbon;

class AdminStoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
      $stores = store::all();
      return view('admin/store/index', ['stores' => $stores]);
    }

    public function active(Request $request, $id)
    {
      $now = carbon::now();
      $store = store::find($id);
      $store->status = $request->status;
      if ($store->status == 1) {
        $store->expire_date = Carbon::now()->addDays(30);
        // dd($store->expire_date);
      } else {
        $store->expire_date = null;
        $store->subscription_id = null;
      }

      $store->save();
      return redirect('/admin/store');
    }

    public function extend(Request $request, $id)
    {
      $store = store::find($id);
      $now = $store->expire_date;
      // dd($now);
      if ($store->status == 1) {
        $store->expire_date = $now->addDays(30);
        // dd($store->expire_date);
      }
      $store->save();
      return redirect('/admin/store');
    }

}
