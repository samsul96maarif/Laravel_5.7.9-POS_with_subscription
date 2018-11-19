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

    <div class="card-body"  style="padding-top:0!important;">
      <ul class="list-unstyled mt-3 mb-4" style="margin-top:0!important";>
        <div class="card-header">
          <h4 class="my-0 font-weight-normal">Extend Period Package : </h4>
        </div>
        <h5 class="my-0 font-weight-normal" style="margin-bottom:10px;">{{ $subscription->name }}</h5>
        <li>- free space for items</li>
        <li>- store up to {{ $subscription->num_invoices }} invoices</li>
        <li>- store up to {{ $subscription->num_users }} contacts</li>

        <div class="card-header">
          <h5 class="my-0 font-weight-normal">Please Transfer to :</h5>
        </div>

        <li>Account Number : {{ $accountNumber }}</li>
        <li>Account holde Name : {{ $accountHolderName }}</li>
        {{-- <li>Account holde Name : {{ $Account_Holder_name }}</li> --}}
        <li>Price : Rp.{{ number_format($subscription->price,2,",",".") }}</li>
        <li style="color:green;">unique code : - Rp.{{ number_format($uniq,2,",",".") }}</li>
        <li>Total Amount : Rp.{{ number_format($amount,2,",",".") }}</li>
      </ul>
      {{-- form i have completed payment --}}
      {{-- <form  action="/subscription/extend" method="post">
        <input type="hidden" name="id" value="{{ $subscription->id }}">
        <input type="hidden" name="uniq" value="{{ $code }}">
        {{ csrf_field() }}
        <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="I have Completed Payment">
      </form> --}}
      <a class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription->id }}/extend/proof">I have Completed Payment</a>
    </div>
  </div>

@endsection
