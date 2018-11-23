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
            <hr>
            @php
            $message = 'berapa bulan anda ingin berlangganan';
            $confirm = 'Do you wanna buy package '.$subscription->name;
            $action = 'buy';
            @endphp
            {{-- percabangan untuk mengetahui store extend atau ingin membeli --}}
            @if ($store->status == 1 && $store->subscription_id == $subscription->id)
              <li>expire date : {{ date('d-m-Y', strtotime($store->expire_date)) }}</li>
              @php
              $message = 'berapa bulan anda ingin memperpanjang package';
              $confirm = 'Do you wanna extend package '.$subscription->name;
              $action = 'Extend Period';
              @endphp
            @endif
            {{-- percabangan untuk mengetahui store extend atau ingin membeli --}}
            <li><b>{{ $message }} : </b></li>
            {{-- unutk mengambil ingin berapa bulan user ingin langsung berlangganan --}}
              {{ csrf_field() }}
              <select class="col-md-4 offset-md-4 form-control" name="period">
                @for ($i = 1; $i < 7; $i++)
                  <option value="{{ $i }}">{{ $i }} bulan</option>
                @endfor
              </select>
            </ul>
            <input onclick="return confirm('{{ $confirm }}')" class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="{{ $action }}">
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
