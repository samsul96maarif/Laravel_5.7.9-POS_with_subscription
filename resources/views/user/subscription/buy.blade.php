@extends('layouts/userMaster')

@section('title', $subscription->name)

@section('headline', 'Complete Payment')

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-header">

      <br>
      <div class="row">
        <div class="col">
          <p style="margin-bottom:0px;">Bill to : </p>
          <h4 class="my-0 font-weight-normal">{{ $user->name }}</h4>
          <p style="margin-bottom:0px;">Company Name : </p>
          <h4 class="my-0 font-weight-normal">{{ $organization->name }}</h4>
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
        @if ($organization->status == 1 && $organization->subscription_id == $subscription->id)
          <h4 class="my-0 font-weight-normal">Extend Periode For Package : </h4>
        @else
          <h4 class="my-0 font-weight-normal">Package Subscription : </h4>
        @endif
        <h5 class="my-0 font-weight-normal">{{ $subscription->name }}</h5>
        @if ($subscription->num_items === null)
          <li>- Free Space For Items</li>
        @else
          <li>- Store Up to {{ number_format($subscription->num_items, 0, ",", ".") }} Items</li>
        @endif
        @if ($subscription->num_invoices === null)
          <li>- Free Space For Invoices</li>
        @else
          <li>- Store Up to {{ number_format($subscription->num_invoices, 0, ",", ".") }} Invoices</li>
        @endif
        @if ($subscription->num_users == 0)
          <li>- Free Space For Contacts</li>
        @else
          <li>- {{ number_format($subscription->num_users, 0, ",", ".") }} Employees</li>
        @endif
        <hr>
        <h4 class="my-0 font-weight-normal">Please Transfer to :</h4>
        <li>Account Number : {{ $accountNumber }}</li>
        <li>Account holde Name : {{ $accountHolderName }}</li>
        <li>Price : Rp.{{ number_format($oriAmount,2,",",".") }} / {{ $payment->period }} Month</li>
        <li style="color:green;">Unique Code : - Rp.{{ number_format($uniq,2,",",".") }}</li>
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
                    <label for="" class="col-form-label">Use Anothere Image : </label>
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
