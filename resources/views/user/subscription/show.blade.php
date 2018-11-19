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
          <h1 class="card-title pricing-card-title">Rp.{{ number_format($subscription->price,2,",",".") }} <small class="text-muted">/ month</small></h1>
          <form class="" action="/subscription/{{ $subscription->id }}/cart" method="post">
          <ul class="list-unstyled mt-3 mb-4">
            <li>free space for items</li>
            <li>store {{ $subscription->num_invoices }} invoice</li>
            <li>store {{ $subscription->num_users }} contact</li>
            <li>berapa bulan anda ingin berlangganan : </li>
            {{-- unutk mengambil ingin berapa bulan user ingin langsung berlangganan --}}
              {{ csrf_field() }}
              <select class="col-md-4 offset-md-4 form-control" name="period">
                @for ($i = 1; $i < 7; $i++)
                  <option value="{{ $i }}">{{ $i }} bulan</option>
                @endfor
              </select>
              @php
                $action = 'buy';
              @endphp
              {{-- percabangan untuk mengetahui store extend atau ingin membeli --}}
              @if ($store->status == 1 && $store->subscription_id == $subscription->id)
                <li>expire date : {{ $store->expire_date }}</li>
                @php
                  $action = 'Extend Period';
                @endphp
              {{-- </ul>
              <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="Extend Periode"> --}}
              {{-- <a class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription->id }}/cart">Extend Periode</a> --}}
              @endif
            </ul>
            <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="{{ $action }}">
            {{-- <a class="btn btn-lg btn-block btn-outline-primary" href="/subscription/{{ $subscription->id }}/cart">buy</a> --}}
          </form>
            {{-- card-body --}}
        </div>
        {{-- card mb-4 shadow-sm --}}
      </div>
      {{-- card-deck --}}
    </div>
    {{-- row justify-content-center --}}
  </div>

@endsection
