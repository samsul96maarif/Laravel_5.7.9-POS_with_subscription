<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// unutk menggunkan auth
use Auth;

class UserController extends Controller
{

    public function __construct()
    {
      // auth : unutk mengecek auth
      // gate : unutk mengecek apakah sudah membuat store
        $this->middleware(['auth', 'gate']);
    }

    public function index()
    {
      $user = user::where('id', Auth::id())->first();

      return view('user/profile/index', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {

      $this->validate($request, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
      ]);

      $user = user::findOrFail($id);
      $user->name = $request->name;
      $user->email = $request->email;
      $user->save();

      return redirect('profile')->withSuccess('Profile has been updated.');
    }
}
