<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Organization;

use Auth;

class EmployeController extends Controller
{
  public function __construct()
  {
    // auth : unutk mengecek auth
    // gate : unutk mengecek apakah sudah membuat Organization
    // getSubscription : unutk mengecek subscription Organization
    // maxContact : unutk mengecek quota user subscription
      $this->middleware(['auth', 'gate', 'get.subscription']);
  }

    public function index()
    {
      $user = Auth::user();
      $organization = organization::where('user_id', $user->id)->first();

      $employes = user::all()->where('organization_id', $organization->id)
      ->where('role', false);

      return view('user/employe/index', ['employes' => $employes]);
    }
// tambah employe
// 1. arahakan ke form
    public function create()
    {
      return view('user/employe/create');
    }

    // 2. simpan hasil
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'username' => 'required|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6'
        ]);

        $user = Auth::user();
        $organization = organization::where('user_id', $user->id)->first();

        $employe = new user;
        $employe->name = $request->name;
        $employe->username = $request->username;
        $employe->organization_id = $organization->id;
        $employe->role = 0;
        $employe->email = $request->email;
        $employe->password = bcrypt($request->password);
        $employe->save();

        return redirect()->route('employes')->withSuccess('Succeed Add Employe');
    }
}
