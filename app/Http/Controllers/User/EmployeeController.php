<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Organization;
// menggunakan db builder
use Illuminate\Support\Facades\DB;

use Auth;

class EmployeeController extends Controller
{
  public function __construct()
  {
    // auth : unutk mengecek auth
    // gate : unutk mengecek apakah sudah membuat Organization
    // getSubscription : unutk mengecek subscription Organization
    // maxContact : unutk mengecek quota user subscription
      $this->middleware('max.user', ['only' => ['create']]);
      $this->middleware(['auth', 'gate', 'get.subscription']);
  }

    public function index()
    {
      $user = Auth::user();
      $organization = organization::where('user_id', $user->id)->first();

      $employees = user::all()->where('organization_id', $organization->id)
      ->where('role', false);

      return view('user/employee/index', ['employees' => $employees]);
    }
// tambah employe
// 1. arahakan ke form
    public function create()
    {
      return view('user/employee/create');
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

        $employee = new user;
        $employee->name = $request->name;
        $employee->username = $request->username;
        $employee->organization_id = $organization->id;
        $employee->role = 0;
        $employee->email = $request->email;
        $employee->password = bcrypt($request->password);
        $employee->save();

        return redirect()->route('employees')->withSuccess('Succeed Add Employee');
    }

    public function edit($id)
    {
      $employee = user::findOrFail($id);

      return view('user/employee/edit', ['employee' => $employee]);
    }

    public function update(Request $request, $id)
    {
      $employee = user::findOrFail($id);

      if ($request->password != "") {
        $request->validate([
            'password' => 'required|string|confirmed|min:6'
        ]);
        $employee->password = bcrypt($request->password);
      }

      if ($request->username != $employee->username) {
        $request->validate([
            'username' => 'required|unique:users'
        ]);
      }

      if ($request->email != $employee->email) {
        $request->validate([
            'email' => 'required|string|email|unique:users'
        ]);
      }

      $request->validate([
        'name' => 'required|string|max:20',
        'username' => 'required',
        'email' => 'required|string|email'
      ]);

      $nameBefore = $employee->name;
      $employee->name = $request->name;
      $employee->username = $request->username;
      $employee->email = $request->email;
      $employee->save();

      return redirect('/employees')->withSuccess('Succeed Updated '.$nameBefore);
    }

    public function delete($id)
    {
      $employee = user::findOrFail($id);
      $name = $employee->name;
      $employee->delete();

      return redirect('/employees')->withSuccess('Succeed Deleted '.$name);
    }

    public function search(Request $request)
    {
      $user = Auth::user();
      $organization = organization::where('user_id', $user->id)->first();

      $employees = DB::table('users')
      ->where('name', 'like', '%'.$request->q.'%')
      ->where('organization_id', $organization->id)
      ->get();

      return view('user/employee/index',
      [
        'employees' => $employees
      ]);
    }
}
