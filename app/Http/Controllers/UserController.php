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
      $user = user::findOrFail($id);
// mengecek apakah emailnya diganti
      if ($user->email != $request->email) {
        $this->validate($request, [
          'email' => 'required|string|email|max:255|unique:users',
        ]);

        $user->email = $request->email;
      }

      $this->validate($request, [
        'name' => 'required|string|max:255',
      ]);

      $user->name = $request->name;
      $user->save();

      return redirect('profile')->withSuccess('Profile Has been Updated.');
    }
}
