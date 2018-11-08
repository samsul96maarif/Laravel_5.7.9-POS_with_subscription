<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Auth;
use Hash;
use Validator;

class PasswordController extends Controller
{
  public function change()
  {
      $id = Auth::id();
      $user = user::findOrFail($id);

      if ($user->role == 1) {
        $extend = 'adminMaster';
      }else {
        $extend = 'userMaster';
      }

      return view('password.change',
      [
        'user' => $user,
        'extend' => $extend
      ]);
  }

  /**
   * @return mixed Redirect
   */
  public function update(Request $request)
  {
      // custom validator
      Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
          return Hash::check($value, \Auth::user()->password);
      });

      // message for custom validation
      $messages = [
          'password' => 'Invalid current password.',
      ];

      // validate form
      $validator = Validator::make(request()->all(), [
          'current_password'      => 'required|password',
          'password'              => 'required|min:6|confirmed',
          'password_confirmation' => 'required',

      ], $messages);

      // if validation fails
      if ($validator->fails()) {
          return redirect()
              ->back()
              ->withErrors($validator->errors());
      }

      // update password
      $id = Auth::id();
      $user = user::findOrFail($id);

      // $user->password = bcrypt(request('password'));
      $user->password = bcrypt($request->password);
      $user->save();

      return redirect()
          ->route('password.change')
          ->withSuccess('Password has been updated.');
  }
}
