@extends('layouts/userMaster')

@section('title', $subscription->name)

@section('headline', $subscription->name)

@section('content')

  <div class="row justify-content-center">
    <div class="card-deck mb-3 text-center">
      <div class="card mb-4 shadow-sm">
        <div class="card-header">
          <h4 class="my-0 font-weight-normal">{{ $subscription->name }}</h4>
        </div>
        <div class="card-body">
          <h1 class="card-title pricing-card-title">Rp.{{ $subscription->price }} <small class="text-muted">/ month</small></h1>
          <ul class="list-unstyled mt-3 mb-4">
            <li>free space for items</li>
            <li>store {{ $subscription->num_invoices }} invoice</li>
            <li>store {{ $subscription->num_users }} contact</li>
          </ul>
          <form  action="/subscription/beli" method="post">
            <input type="hidden" name="id" value="{{ $subscription->id }}">
            {{ csrf_field() }}
            <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="buy">
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
