<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Store;
// menggunakan db builder
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
  public function __construct()
  {
    // mengecek auth
      $this->middleware('auth');
      // mengecek role user 1 / 0
      $this->middleware('admin');
  }

  public function index()
  {
    // memanggil user biasa(role = false)
    $users = user::all()->where('role', false);
    $stores = store::all();

    return view('admin/user/index',
    [
      'users' => $users,
      'stores' => $stores
    ]);
  }

  public function show($id)
  {
    $user = user::findOrFail($id);
    $store = store::where('user_id', $id)->first();

    return view('admin/user/detail',
    [
      'user' => $user,
      'store' => $store
    ]);
  }

  public function search(Request $request)
  {

    $users = DB::table('users')
                    ->where('name', 'like', '%'.$request->q.'%')
                    ->where('role', false)
                    ->get();

    $stores = store::all();

    return view('admin/user/index',
      [
        'users' => $users,
        'stores' => $stores
      ]);
  }
}
