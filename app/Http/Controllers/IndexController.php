<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;

class IndexController extends Controller
{
    public function index()
    {
      $subscriptions = subscription::all()->where('deleted_at', null);
      // unutk mengurutkan dari harga termurah
      $subscriptions = collect($subscriptions)->sortByDesc('price')->reverse()->toArray();
      
      return view('welcome', ['subscriptions' => $subscriptions]);
    }
}
