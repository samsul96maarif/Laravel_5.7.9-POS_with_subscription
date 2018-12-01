<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Organization;
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
    $users = user::all()->where('admin', false);

    $organizations = organization::all();

    return view('admin/user/index',
    [
      'users' => $users,
      'organizations' => $organizations
    ]);
  }

  public function show($id)
  {
    $user = user::findOrFail($id);
    $organization = organization::where('user_id', $id)->first();

    return view('admin/user/detail',
    [
      'user' => $user,
      'organization' => $organization
    ]);
  }

  public function search(Request $request)
  {

    $users = DB::table('users')
                    ->where('name', 'like', '%'.$request->q.'%')
                    ->where('role', true)
                    ->orWhere('username', 'like', '%'.$request->q.'%')
                    ->where('role', true)
                    ->get();

    $organizations = organization::all();

    return view('admin/user/index',
      [
        'users' => $users,
        'organizations' => $organizations
      ]);
  }
}
