<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Subscription;
use App\Models\Store;
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
    $store = store::where('user_id', $user_id)->first();
    $subscriptions = subscription::all();
    $payment = payment::where('store_id', $store->id)
    ->where('paid', 0)->first();

    return view('user/subscription/index',
    [
      'subscriptions' => $subscriptions,
      'payment' => $payment,
      'store' => $store
    ]);
  }

  // fungsi untuk melihat detail Package subscription
  public function show($id)
  {
    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    return view('user/subscription/show',
    [
      'subscription' => $subscription,
      'store' => $store
    ]);
  }

  public function buy($id)
  {
    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    $uniq = rand(1,999);
    $amount = $subscription->price - $uniq;
    $rekening = '093482';

    if ($store->subscription_id != null && $store->status == 1) {
      // throw new \Exception("anda telah memiliki Package subscription, apakah anda ingin mengahpusnya dan mengganti dengan yang baru?");
    } else {
      // code...
    }

    $store->subscription_id = $subscription->id;
    $store->status = 0;
    $store->expire_date = null;
    $store->save();

    $payment = payment::where('store_id', $store->id)
    ->where('paid', 0)->first();

    if ($payment == null) {
        // pengecekan apakah uniq code sudah ada
        $payment = payment::where('uniq_code', $uniq)->first();
        // perulangan sampai tidak ada yang sama
        while ($payment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price - $uniq;
          $payment = payment::where('uniq_code', $uniq)->first();
        }
        // end batas perulangn pengecekan yang sama
        $payment = new payment;
        $payment->store_id = $store->id;
      } else {
        // pengecekan apakah uniq code sudah ada
        $cariPayment = payment::where('uniq_code', $uniq)->first();
        // perulangan sampai tidak ada yang sama
        while ($cariPayment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price - $uniq;
          $cariPayment = payment::where('uniq_code', $uniq)->first();
        }
      }

      $payment->proof = null;
      $payment->uniq_code = $uniq;
      $payment->amount = $amount;
      $payment->subscription_id = $id;
      $payment->save();

      return view('user/subscription/buy',
      [
        'subscription' => $subscription,
        'user' => $user,
        'store' => $store,
        'uniq' => $uniq,
        'amount' => $amount,
        'rekening' => $rekening
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
    $store = store::where('user_id', $user_id)->first();
    $payment = payment::where('store_id', $store->id)
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

    return redirect('/subscription')
        ->withSuccess('a payment proof has been uploaded.
        wait for confirmation');

  }

  public function pilihExtend($id)
  {
    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    return view('user/subscription/pilih_extend',
    [
      'subscription' => $subscription,
      'store' => $store
    ]);
  }

  public function extend($id)
  {

    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);
    $uniq = rand(1,999);
    $amount = $subscription->price - $uniq;

    $payment = payment::where('store_id', $store->id)
    ->where('paid', 0)->first();

    if ($payment == null) {
        // pengecekan apakah uniq code sudah ada
        $payment = payment::where('uniq_code', $uniq)->first();
        // perulangan sampai tidak ada yang sama
        while ($payment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price - $uniq;
          $payment = payment::where('uniq_code', $uniq)->first();
        }
        // end batas perulangn pengecekan yang sama
        $payment = new payment;
        $payment->store_id = $store->id;
      } else {
        // pengecekan apakah uniq code sudah ada
        $cariPayment = payment::where('uniq_code', $uniq)->first();
        // perulangan sampai tidak ada yang sama
        while ($cariPayment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price - $uniq;
          $cariPayment = payment::where('uniq_code', $uniq)->first();
        }
      }

      $payment->proof = null;
      $payment->uniq_code = $uniq;
      $payment->amount = $amount;
      $payment->subscription_id = $id;
      $payment->save();

    return view('user/subscription/extend',
    [
      'subscription' => $subscription,
      'user' => $user,
      'store' => $store,
      'uniq' => $uniq,
      'amount' => $amount
    ]);
  }

  public function cart()
  {

    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $payment = payment::where('store_id', $store->id)->first();
    $subscription = subscription::findOrFail($payment->subscription_id);
    // $rekening = '093482';

    // mengecek apakah extend atau bukan
    if ($store->status == 1) {

      return view('user/subscription/extend',
      [
        'subscription' => $subscription,
        'user' => $user,
        'store' => $store,
        'uniq' => $payment->uniq_code,
        'amount' => $payment->amount
      ]);

    } else {

      return view('user/subscription/buy',
      [
        'subscription' => $subscription,
        'user' => $user,
        'store' => $store,
        'uniq' => $payment->uniq_code,
        'amount' => $payment->amount
      ]);
    }
  }

}
