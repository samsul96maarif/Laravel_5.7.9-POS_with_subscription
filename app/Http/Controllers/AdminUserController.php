<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Store;

class AdminUserController extends Controller
{
  public function __construct()
  {
      $this->middleware('auth');
      $this->middleware('admin');
  }

  public function index()
  {
    $users = user::all()->where('role', false);
    // dd($users);
    return view('admin/user/index', ['users' => $users]);
  }

  public function show($id)
  {
    $user = user::findOrFail($id);
    $store = store::where('user_id', $id)->first();
    dd($store);
    return view('admin/user/detail', ['user' => $user]);
  }
}
