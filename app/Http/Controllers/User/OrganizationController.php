<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Organization;
use App\Models\Subscription;
// unutk menggunakan auth
use Auth;

class OrganizationController extends Controller
{

  public function __construct()
  {
    // auth : unutk mengecek auth
      $this->middleware('auth');
  }

  public function index()
    {
      $user = Auth::user();

      if ($user->role == 0) {
        // return redirect()->route('employe.sales.orders', ['id' => $user->id]);
        return redirect()->route('sales.orders');
      }

      $organization = organization::where('user_id', $user->id)->first();
      $subscription = subscription::where('id', $organization->subscription_id)->first();

      return view('user/organization/index',
      [
        'organization' => $organization,
        'subscription' => $subscription
      ]);
    }

  // create
// 1. mengarahkan ke form
  public function create(){

    // bila admin di alihkan ke hal admin
    if (Auth::user()->isAdmin()) {
      return redirect('/admin');
    }
    return view('user/organization/create');
  }
  // 2.store data
  public function store(Request $request)
  {

    $user_id = Auth::id();
    // contoh penggunaan validate dimana :
    // 1. value name required
    $this->validate($request, [
      'name' => 'required',
      'phone' => 'required|numeric',
      'company_address' => 'required',
      'zipcode' => 'required|numeric',
    ]);

    $organization = new organization;

    if ($request->file('logo') == "") {
        // code...
      } else {
        // menyimpan nilai image
        $file = $request->file('logo');
        // mengambil nama file
        $fileName = $file->getClientOriginalName();
        // menyimpan file image kedalam folder "img"
        $request->file('logo')->move("logo/",$fileName);
        // menyimpan ke dalam database nama file dari image
        $organization->logo = $fileName;
      }

    $organization->user_id = $user_id;
    $organization->name = $request->name;
    $organization->phone = $request->phone;
    $organization->company_address = $request->company_address;
    $organization->zipcode = $request->zipcode;
    $organization->save();

    return redirect('/home')->withSuccess('Company Profile Has been Created.');
  }

  // 1. store data update
        public function update(Request $request, $id)
        {

          $organization = organization::find($id);

          $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|numeric',
            'company_address' => 'required',
            'zipcode' => 'required|numeric',
          ]);


          if ($request->file('logo') == "") {
              // code...
            } else {
              // menyimpan nilai image
              $file = $request->file('logo');
              // mengambil nama file
              $fileName = $file->getClientOriginalName();
              // menyimpan file image kedalam folder "img"
              $request->file('logo')->move("logo/",$fileName);
              // menyimpan ke dalam database nama file dari image
              $organization->logo = $fileName;
            }

          $organization->name = $request->name;
          $organization->phone = $request->phone;
          $organization->company_address = $request->company_address;
          $organization->zipcode = $request->zipcode;
          $organization->save();

          return redirect('/home')->withSuccess('Company Profile Has been Updated.');
        }

        // delete
        public function delete($id)
        {
          $organization = organization::find($id);
          $organization->delete();

          return redirect('/home');
        }
}
