<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\Contact;
use Auth;

// use App\Models\Item;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{
  public function __construct()
  {

  }

  public function index()
  {
    return view('tes/item_autocomplete');
  }

  public function loadData(Request $request)
    {
    	if ($request->has('q')) {
    		$cari = $request->q;
    		$data = DB::table('items')->select('id', 'name')->where('name', 'LIKE', '%'.$cari.'%')->get();
    		return response()->json($data);
    	}
    }

    function fetch(Request $request)
    {
      $user = Auth::user();
      $store = store::where('user_id', $user->id)->first();

      if($request->get('query'))
      {
        $query = $request->get('query');
        $data = DB::table('contacts')
        ->where('name', 'LIKE', "%{$query}%")
        ->where('store_id', $store->id)
        ->get();

        $output = '<div class="dropdown-menu" style="display:block; position:relative">';
        foreach($data as $row)
        {
          $output .= '
          <span class="btn dropdown-item">'.$row->name.'</span>
          ';
        }
        $output .= '</div>';
        echo $output;
      }

    }
}
