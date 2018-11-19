@extends('layouts/userMaster')

@section('title', 'Subscription')

@section('headline', 'Subscriptions')

@section('content')
  {{-- alert bila sukses mengirim payment proof --}}
  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  {{-- bila belum mengirim payment proof --}}
  @if ($payment != null)
    @if ($payment->proof == null)
      <div class="row">
        <div class="col">
          <a href="/subscription/cart">Waiting for Payment Proof</a>
        </div>
      </div>
      <br>
    @else
      <div class="row">
        <div class="col">
          <a href="/subscription/payment/proof">Waiting for validation</a>
        </div>
      </div>
      <br>
    @endif
  @endif

  <script>
  function myFunction() {
    var r = confirm("You Have Package Subscription before, Do You wanna Channge Package?");
    if (r == true) {
      return true;
    } else {
      return false;
    }
  }
  </script>

    <div class="card-deck mb-3 text-center justify-content-center">
      @foreach ($subscriptions as $subscription)
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">{{ $subscription->name }}</h4>
          </div>
          <div class="card-body">
            <h3 class="card-title pricing-card-title">Rp.{{ number_format($subscription->price,2,",",".") }} <small class="text-muted">/ month</small></h3>
            <ul class="list-unstyled mt-3 mb-4">
              <li>free space for items</li>
              <li>store {{ $subscription->num_invoices }} invoice</li>
              <li>store {{ $subscription->num_users }} contact</li>
              @if ($store->subscription_id == $subscription->id)
                @if ($payment != null)
                  @if ($payment->store_id == $store->id && $payment->paid == 0)
                    @if ($store->status == 1)
                      <li><b>awaiting payment for extend period</b></li>
                    @endif
                    {{-- ($payment->store_id == $store->id) --}}
                  @endif
                  {{-- ($payment != null) --}}
                @endif
                {{-- ($store->subscription_id == $subscription->id) --}}
              @endif
            </ul>
            @if ($store->subscription_id == $subscription->id)
              @if ($store->status == 1)
                <div class="row">
                  <div class="col btn-atas">
                    <button type="button" name="" class="btn btn-lg btn-block btn-success">activated</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col">

                    {{-- bila tidak ada ditabel payment maka akan ditawarkan extend periode --}}
                    @if ($payment == null)
                      <a class="btn btn-lg btn-block btn-primary" href="/subscription/{{ $subscription->id }}/detail">extend periode</a>
                    {{-- @else --}}
                      {{-- @if ($payment->store_id == $store->id) --}}
                        {{-- <div class="col"> --}}
                        {{-- <a class="btn btn-block btn-secondary" href="#">awaiting payment for extend period</a> --}}
                        {{-- <button type="button" name="" class="btn btn-lg btn-block btn-secondary">awaiting payment for extend period</button> --}}
                        {{-- </div> --}}
                      {{-- @endif --}}
                    @endif
                    {{-- <form  action="/subscription/{{ $subscription->id }}/detail_packet" method="get">
                      <input class="btn btn-lg btn-block btn-primary" type="submit" name="submit" value="extend periode">
                    </form> --}}
                  </div>
                </div>
                {{-- $store->status == 1 --}}
              @else
                <button type="button" name="" class="btn btn-lg btn-block btn-secondary">Awaiting Payment</button>
              @endif

              {{-- $store->subscription_id == $subscription->id --}}
            @else
              <a onclick="return myFunction()" class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription->id }}/detail">buy</a>
            @endif
            {{-- <div class="card-body"> --}}
          </div>
          {{-- <div class="card mb-4 shadow-sm"> --}}
        </div>
      @endforeach
    </div>

@endsection
