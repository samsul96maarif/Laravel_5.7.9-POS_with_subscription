<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\Item;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
  public function __construct()
  {

  }

  public function index()
  {
    return view('tes/index');
  }

  public function loadData(Request $request)
    {
    	if ($request->has('q')) {
    		$cari = $request->q;
    		$data = DB::table('items')->select('id', 'name')->where('name', 'LIKE', '%'.$cari.'%')->get();
    		return response()->json($data);
    	}
    }
}
