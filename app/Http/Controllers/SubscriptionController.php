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

class SubscriptionController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware(['auth', 'gate']);
      // $this->user = Auth::id();
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Http\Response
   */
  public function home()
  {
      return view('user/index');
  }

  public function index()
  {
    $user_id = Auth::id();
    $store = store::where('user_id', $user_id)->first();
    $subscriptions = subscription::all();
    $payment = payment::where('store_id', $store->id)->first();
    // dd($payment);
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

    $uniq = rand(1,999);
    $amount = $subscription->price - $uniq;
    $code = substr($amount, -3);
    $rekening = '093482';

    $payment = payment::where('uniq_code', 778)->first();

    if ($payment != null) {
      $uniq = rand(1,999);
      $amount = $subscription->price - $uniq;
      $code = substr($amount, -3);
      $payment = payment::where('uniq_code', $code)->first();
    }
    return view('user/subscription/show',
    [
      'subscription' => $subscription,
      'code' => $code,
      'user' => $user,
      'store' => $store,
      'subscription' => $subscription,
      'uniq' => $uniq,
      'amount' => $amount,
      'rekening' => $rekening
    ]);
  }

  public function buy($id)
  {
    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);

    $uniq = rand(1,999);
    $amount = $subscription->price - $uniq;
    $code = substr($amount, -3);
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

    $payment = payment::where('store_id', $store->id)->first();

    // dd($payments);
    if ($payment == null) {
        // pengecekan apakah uniq code sudah ada
        $payment = payment::where('uniq_code', $code)->first();
        // perulangan sampai tidak ada yang sama
        while ($payment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price - $uniq;
          $code = substr($amount, -3);
          $payment = payment::where('uniq_code', $code)->first();
        }
        // end batas perulangn pengecekan yang sama
        $payment = new payment;
        $payment->store_id = $store->id;
      } else {
        // pengecekan apakah uniq code sudah ada
        $cariPayment = payment::where('uniq_code', $code)->first();
        // perulangan sampai tidak ada yang sama
        while ($cariPayment != null) {
          $uniq = rand(1,999);
          $amount = $subscription->price - $uniq;
          $code = substr($amount, -3);
          $cariPayment = payment::where('uniq_code', $code)->first();
        }
      }
      $payment->uniq_code = $code;
      $payment->save();

      return view('user/subscription/buy',
      [
        'subscription' => $subscription,
        'code' => $code,
        'user' => $user,
        'store' => $store,
        'subscription' => $subscription,
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
    $payment = payment::where('store_id', $store->id)->first();

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
    // $uniq = $store->id;
    $uniq = rand(1,999);
    $amount = $subscription->price - $uniq;
    $code = substr($amount, -3);

    $payment = payment::where('uniq_code', 778)->first();

    if ($payment != null) {
      // dd($payment);
      $uniq = rand(1,999);
      $amount = $subscription->price - $uniq;
      $code = substr($amount, -3);
      $payment = payment::where('uniq_code', $code)->first();
    }
    // dd($newstring);
    // $amount = 292340000001239;
    // dd(number_format($amount,2,",","."));
    $rekening = '093482';
    return view('user/subscription/pilih_extend',
    [
      'subscription' => $subscription,
      'code' => $code,
      'user' => $user,
      'store' => $store,
      'subscription' => $subscription,
      'uniq' => $uniq,
      'amount' => $amount,
      'rekening' => $rekening
    ]);
  }

  public function extend($id)
  {
    $user = Auth::user();
    $store = store::where('user_id', $user->id)->first();
    $subscription = subscription::findOrFail($id);
    // $uniq = $store->id;
    $uniq = rand(1,999);
    $amount = $subscription->price - $uniq;
    $code = substr($amount, -3);
    $rekening = '093482';

    // fun extend periode
    $payment = payment::where('store_id', $store->id)->first();
    // dd($payment);

    if ($payment == null) {
      $payment = payment::where('uniq_code', $code)->first();

      while ($payment != null) {
        $uniq = rand(1,999);
        $amount = $subscription->price - $uniq;
        $code = substr($amount, -3);
        $payment = payment::where('uniq_code', $code)->first();
      }

      $payment = new payment;
      $payment->uniq_code = $code;
      $payment->store_id = $store->id;
      $payment->save();
    } else {
      $payment->uniq_code = $code;
      $payment->save();
    }

    return view('user/subscription/extend',
    [
      'subscription' => $subscription,
      'code' => $code,
      'user' => $user,
      'store' => $store,
      'subscription' => $subscription,
      'uniq' => $uniq,
      'amount' => $amount,
      'rekening' => $rekening
    ]);
  }

  // public function beli(Request $request)
  // {
  //   $subscription = subscription::find($request->id);
  //   $user_id = Auth::id();
  //   // dd($user_id);
  //   $store = store::where('user_id', $user_id)->first();
  //   $store->subscription_id = $subscription->id;
  //   $store->status = 0;
  //   $store->expire_date = null;
  //   $store->save();
  //
  //   $payments = payment::where('store_id', $store->id)->first();
  //   // dd($payments);
  //
  //   if ($payments == null) {
  //     $payment = new payment;
  //     $payment->uniq_code = $request->uniq;
  //     $payment->store_id = $store->id;
  //     $payment->save();
  //   } else {
  //     $payments->uniq_code = $request->uniq;
  //     $payments->save();
  //   }
  //
  //   return redirect('/subscription');
  // }
  // batas fun beli

  public function extendPeriod(Request $request)
  {
    $subscription = subscription::find($request->id);
    $user_id = Auth::id();
    // dd($user_id);
    $store = store::where('user_id', $user_id)->first();
    // $store->subscription_id = $subscription->id;
    // $store->status = 0;
    // $store->expire_date = null;
    // $store->save();

    $payments = payment::where('store_id', $store->id)->first();
    // dd($payments);

    if ($payments == null) {
      $payment = new payment;
      $payment->uniq_code = $request->uniq;
      $payment->store_id = $store->id;
      $payment->save();
    } else {
      $payments->uniq_code = $request->uniq;
      $payments->save();
    }

    return redirect('/subscription');
  }

  public function bill($request, $store)
  {
    // dd($this->user);
    $id = Auth::id();
    $store = store::where('user_id', $id)->first();
    $subscription = subscription::find($request->id);
    dd($subscription);
    $amount = $subscription->price - $store->id;
    $rekening = '093482';
  }
}
