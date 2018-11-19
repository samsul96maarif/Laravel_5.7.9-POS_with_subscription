<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;

class IndexController extends Controller
{
    public function index()
    {
      $subscriptions = subscription::all();

      // $data = subscription::all();
      //
      // if(count($data) > 0){ //mengecek apakah data kosong atau tidak
      //     $res['message'] = "Success!";
      //     $res['values'] = $data;
      //     return response($res);
      // }
      // else{
      //     $res['message'] = "Empty!";
      //     return response($res);
      // }

      return view('welcome', ['subscriptions' => $subscriptions]);
    }
}
