<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;

class IndexController extends Controller
{
    public function index()
    {
      $subscriptions = subscription::all();
      return view('welcome', ['subscriptions' => $subscriptions]);
    }
}
