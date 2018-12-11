@extends('layouts/userMaster')

@section('title', 'Subscriptions')

@section('headline', 'Subscriptions')

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif
  {{-- alert bila sukses mengirim payment proof --}}
  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  {{-- bila belum mengirim payment proof --}}
  @if ($payment != null)
    @if ($payment->proof == null)
      <div class="row">
        <div class="col">
          <a class="btn btn-primary" href="/subscription/cart">Waiting For Payment Proof</a>
        </div>
      </div>
      <br>
    @else
      <div class="row">
        <div class="col">
          <a class="btn btn-primary" href="/subscription/payment/proof">Waiting For Validation</a>
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
            <h4 class="my-0 font-weight-normal">{{ $subscription['name'] }}</h4>
          </div>
          <div class="card-body">
            <h3 class="card-title pricing-card-title">Rp.{{ number_format($subscription['price'],2,",",".") }} <small class="text-muted">/ month</small></h3>
            <ul class="list-unstyled mt-3 mb-4">
              @if ($subscription['num_items'] === null)
                <li>Unlimited Space For Items</li>
              @else
                <li>Store Up to {{ number_format($subscription['num_items'], 0, ",", ".") }} Items</li>
              @endif
              {{-- num invoice --}}
              @if ($subscription['num_invoices'] === null)
                <li>Unlimited Space For Invoices</li>
              @else
                <li>Store Up to {{ number_format($subscription['num_invoices'], 0, ",", ".") }} Invoices</li>
              @endif
              {{-- num contact --}}
              @if ($subscription['num_users'] === null)
                <li>Unlimited Employees</li>
              @else
                <li>{{ number_format($subscription['num_users'], 0, ",", ".") }} Employees</li>
              @endif

              @if ($organization->subscription_id == $subscription['id'])
                @if ($payment != null)
                  @if ($payment->organization_id == $organization->id && $payment->paid == 0)
                    @if ($organization->status == 1)
                      <hr>
                      <li><b>Awaiting Payment For Extend Period</b></li>
                    @endif
                    {{-- ($payment->organization_id == $organization->id) --}}
                  @endif
                  {{-- ($payment != null) --}}
                @endif
                {{-- ($organization->subscription_id == $subscription->id) --}}
              @endif
            </ul>
            @if ($organization->subscription_id == $subscription['id'])
              @if ($organization->status == 1)
                <div class="row">
                  <div class="col btn-atas">
                    <button type="button" name="" class="btn btn-lg btn-block btn-success">Activated</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col">

                    {{-- bila tidak ada ditabel payment maka akan ditawarkan extend periode --}}
                    @if ($payment == null)
                      <a class="btn btn-lg btn-block btn-primary" href="/subscription/{{ $subscription['id'] }}/detail">Extend Periode</a>
                    @endif
                  </div>
                </div>
                {{-- $organization->status == 1 --}}
              @else
                {{-- mengetahui sudah kirim proof atw belum --}}
                @if ($payment != null)
                  @if ($payment->proof == null)
                    <button type="button" name="" class="btn btn-lg btn-block btn-secondary">Awaiting Payment</button>
                  @else
                    <button type="button" name="" class="btn btn-lg btn-block btn-secondary">Waiting Validation</button>
                  @endif
                  {{-- payment != null --}}
                @endif
                {{-- <button type="button" name="" class="btn btn-lg btn-block btn-secondary">Awaiting Payment</button> --}}
              @endif

              {{-- $organization->subscription_id == $subscription->id --}}
            @else
              {{-- bila sudah memiliki subscription maka akan muncul alert --}}
              @if ($organization->subscription_id != null)
                <a onclick="return myFunction()" class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription['id'] }}/detail">Buy</a>
              @else
                <a class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription['id'] }}/detail">Buy</a>
              @endif
            @endif
            {{-- <div class="card-body"> --}}
          </div>
          {{-- <div class="card mb-4 shadow-sm"> --}}
        </div>
      @endforeach
    </div>

@endsection
