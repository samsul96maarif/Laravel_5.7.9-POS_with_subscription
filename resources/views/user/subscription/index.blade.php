@extends('layouts/userMaster')

@section('title', 'Subscription')

@section('headline', 'Subscriptions')

@section('content')

    <div class="card-deck mb-3 text-center">
      @foreach ($subscriptions as $subscription)
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">{{ $subscription->name }}</h4>
          </div>
          <div class="card-body">
            <h2 class="card-title pricing-card-title">Rp.{{ $subscription->price }} <small class="text-muted">/ month</small></h2>
            <ul class="list-unstyled mt-3 mb-4">
              <li>free space for items</li>
              <li>store {{ $subscription->num_invoices }} invoice</li>
              <li>store {{ $subscription->num_users }} contact</li>
            </ul>
            @if ($store->subscription_id == $subscription->id)
              @if ($store->status == 1)
                <button type="button" name="" class="btn btn-lg btn-block btn-outline-primary" style="background-color:#007bff;color:white;">Active</button>
              @else
                <button type="button" name="" class="btn btn-lg btn-block btn-outline-primary" style="background-color:#4f4f4f;color:white;">Awaiting Payment</button>
              @endif
            @else
            <form  action="/subscription/{{ $subscription->id }}/pilih" method="get">
              <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="buy">
            </form>
            @endif
          </div>
        </div>
      @endforeach
    </div>

@endsection
