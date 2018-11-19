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
        {{-- unutk mengetahui sedang extend atau ingin membeli package --}}
        @if ($store->status == 1 && $store->subscription_id == $subscription->id)
          <h4 class="my-0 font-weight-normal">Extend Periode for Package : </h4>
        @else
          <h4 class="my-0 font-weight-normal">Package subscription : </h4>
        @endif
        <h5 class="my-0 font-weight-normal">{{ $subscription->name }}</h5>
        <li>- free space for items</li>
        <li>- store up to {{ $subscription->num_invoices }} invoices</li>
        <li>- store up to {{ $subscription->num_users }} contacts</li>
        <hr>
        <h4 class="my-0 font-weight-normal">Please Transfer to :</h4>
        <li>Account Number : {{ $accountNumber }}</li>
        <li>Account holde Name : {{ $accountHolderName }}</li>
        <li>Price : Rp.{{ number_format($oriAmount,2,",",".") }} / {{ $payment->period }} Month</li>
        <li style="color:green;">unique code : - Rp.{{ number_format($uniq,2,",",".") }}</li>
        <li>Total Amount : Rp.{{ number_format($amount,2,",",".") }}</li>
        <hr>
        @if ($payment->proof != null)
          <div class="row justify-content-center">
            <div class="col">
              <h4 class="my-0 font-weight-normal">Payment Proof :</h4>
              <img src="{{ asset('proof/'.$payment->proof) }}" alt="payment proof" class="rounded mx-auto d-block">
            </div>
          </div>
          <br>
          <div class="row justify-content-center">
            <div class="col text-center">
              <form class="" action="/subscription/{{ $subscription->id }}/buy/proof" method="post" value="post" enctype="multipart/form-data">
                <div class="row justify-content-center">
                  <div class="col text-center">
                    <label for="" class="col-form-label">use anothere image : </label>
                    <input class="" type="file" name="proof" value="{{ old('proof') }}">
                    @if($errors->has('proof'))
                      <p>{{ $errors->first('proof') }}</p>
                    @endif
                  </div>
                </div>
                <br>
                <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="Change payment Proof Image">
                {{ csrf_field() }}
              </form>
            </div>
          </div>
          <br>
        @else
          </ul>
          <a class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription->id }}/buy/proof">I have Completed Payment</a>
        @endif
    </div>
  </div>

@endsection
