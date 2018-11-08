@extends('layouts/userMaster')

@section('title', $subscription->name)

@section('headline', 'Completed Payment')

@section('content')

  <div class="card shadow-sm">
    <div class="card-header">

      <br>
      <div class="row">
        <div class="col">
          <p style="margin-bottom:0px;">bill to : </p>
          <h4 class="my-0 font-weight-normal">{{ $user->name }}</h4>
          <p style="margin-bottom:0px;">store name : </p>
          <h4 class="my-0 font-weight-normal">{{ $store->name }}</h4>
        </div>
      </div>
      <div class="row">
        <div class="col">
        </div>
      </div>
    </div>

    <div class="card-body">
      <ul class="list-unstyled mt-3 mb-4">
        <h4 class="my-0 font-weight-normal">Package subscription : </h4>
        <h5 class="my-0 font-weight-normal">{{ $subscription->name }}</h5>
        <li>- free space for items</li>
        <li>- store up to {{ $subscription->num_invoices }} invoices</li>
        <li>- store up to {{ $subscription->num_users }} contacts</li>
        <hr>
        <li>Account Number : {{ $rekening }}</li>
        <li>Account holde Name : PT.Zuragan Indonesia</li>
        {{-- <li>Account holde Name : {{ $Account_Holder_name }}</li> --}}
        <li>Price : Rp.{{ number_format($subscription->price,2,",",".") }}</li>
        <li style="color:green;">unique code : - Rp.{{ number_format($uniq,2,",",".") }}</li>
        <li>Total Amount : Rp.{{ number_format($amount,2,",",".") }}</li>
      </ul>
      {{-- form i have completed payment --}}
      {{-- <form  action="/subscription/beli" method="post">
        <input type="hidden" name="id" value="{{ $subscription->id }}">
        <input type="hidden" name="uniq" value="{{ $code }}">
        {{ csrf_field() }}
        <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="I have Completed Payment">
      </form> --}}
      <a class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription->id }}/buy/proof">I have Completed Payment</a>
    </div>
  </div>

@endsection
