<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\Organization;
use App\Models\Payment;
// untuk menggunakan resize
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
// unutk menggunakan auth
use Auth;
// unutk share ke semua view
use View;

class SubscriptionController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      // middleware
      $this->middleware(['auth', 'gate']);
      // rekening
      $accountNumber = '093482';
      $accountHolderName = 'PT.Zuragan Indonesia';

      // Sharing is caring
      View::share(
        [
          'accountNumber' => $accountNumber,
          'accountHolderName' => $accountHolderName
        ]);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    $user_id = Auth::id();
    $organization = organization::where('user_id', $user_id)->first();
    $subscriptions = subscription::all()->where('deleted_at', null);
    // unutk mengurutkan dari harga termurah
    // https://stackoverflow.com/questions/38116513/how-to-sort-an-assocoative-array-in-php-laravel
    $subscriptions = collect($subscriptions)->sortByDesc('price')->reverse()->toArray();
    $payment = payment::where('organization_id', $organization->id)
    ->where('paid', 0)->first();

    return view('user/subscription/index',
    [
      'subscriptions' => $subscriptions,
      'payment' => $payment,
      'organization' => $organization
    ]);
  }

  // fungsi untuk melihat detail Package subscription
  public function show($id)
  {
    $user = Auth::user();
    if ($user->role == 0) {
      return redirect('/')->with('alert', 'access denied');
    }
    $organization = organization::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    return view('user/subscription/show',
    [
      'subscription' => $subscription,
      'organization' => $organization
    ]);
  }

  public function buy(Request $request, $id)
  {
    $user = Auth::user();
    $organization = organization::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    $uniq = rand(1,999);
    $amount = $subscription->price * $request->period - $uniq;
    $oriAmount = $subscription->price * $request->period;

    // mengecek apakah ini sedang extend atau ingin membeli
    if ($organization->subscription_id == $id && $organization->status == 1 ) {
      // extend
    } else {
      $organization->subscription_id = $subscription->id;
      $organization->status = 0;
      $organization->expire_date = null;
      $organization->save();
    }

    $payment = payment::where('organization_id', $organization->id)
    ->where('paid', 0)->first();

    if ($payment == null) {
        // pengecekan apakah uniq code sudah ada
        $payment = payment::where('uniq_code', $uniq)
          ->where('paid', 0)->first();
        // perulangan sampai tidak ada yang sama
        while ($payment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price * $request->period - $uniq;
          $payment = payment::where('uniq_code', $uniq)
            ->where('paid', 0)->first();
        }
        // end batas perulangn pengecekan yang sama
        $payment = new payment;
        $payment->organization_id = $organization->id;
      } else {
        // pengecekan apakah uniq code sudah ada
        $cariPayment = payment::where('uniq_code', $uniq)
          ->where('paid', 0)->first();
        // perulangan sampai tidak ada yang sama
        while ($cariPayment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price * $request->period - $uniq;
          $cariPayment = payment::where('uniq_code', $uniq)
            ->where('paid', 0)->first();
        }
      }

      $payment->proof = null;
      $payment->subscription_name = $subscription->name;
      $payment->uniq_code = $uniq;
      $payment->amount = $amount;
      $payment->subscription_id = $id;
      $payment->period = $request->period;
      $payment->save();

      return view('user/subscription/buy',
      [
        'subscription' => $subscription,
        'user' => $user,
        'organization' => $organization,
        'uniq' => $uniq,
        'amount' => $amount,
        'payment' => $payment,
        'oriAmount' => $oriAmount
      ]);
  }

  public function uploadProof($id)
  {
    return view('user/subscription/uploadProof', ['id' => $id]);
  }

  public function storeProof(Request $request, $id)
  {
    $this->validate($request, [
      'proof' => 'required',
    ]);

    $user_id = Auth::id();
    $organization = organization::where('user_id', $user_id)->first();
    $payment = payment::where('organization_id', $organization->id)
    ->where('paid', 0)->first();

    // menyimpan nilai image
    $image = $request->file('proof');
    // mengambil nama file
    $fileName = $image->getClientOriginalName();
    // membuat path untuk tempat simpan
    $path = public_path('proof/'.$fileName);
    // resize
    // menyimpan file image kedalam folder "proof"
    $file = Image::make($image)->widen(318)->save($path);
    // menyimpan ke dalam database nama file dari image
    $payment->proof = $fileName;
    $payment->save();

    return redirect('/subscriptions')
        ->withSuccess('a payment proof has been uploaded.
        wait for confirmation');

  }

  public function cart()
  {

    $user = Auth::user();
    $organization = organization::where('user_id', $user->id)->first();
    $payment = payment::where('organization_id', $organization->id)
      ->where('paid', 0)->first();
    $subscription = subscription::findOrFail($payment->subscription_id);
    $oriAmount = $subscription->price * $payment->period;

    return view('user/subscription/buy',
    [
      'subscription' => $subscription,
      'user' => $user,
      'organization' => $organization,
      'uniq' => $payment->uniq_code,
      'amount' => $payment->amount,
      'payment' => $payment,
      'oriAmount' => $oriAmount
    ]);
  }

}
