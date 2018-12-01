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
            <li>Free Space For Items</li>
            {{-- num invoice --}}
            @if ($subscription->num_invoices == 0)
              <li>Free Space For Invoices</li>
            @else
              <li>Store Up to {{ number_format($subscription->num_invoices, 0, ",", ".") }} Invoices</li>
            @endif
            {{-- num contact --}}
            @if ($subscription->num_users == 0)
              <li>Free Space For Contacts</li>
            @else
              <li>Store Up to {{ number_format($subscription->num_users, 0, ",", ".") }} Contacts</li>
            @endif
            
            <hr>
            @php
            $message = 'How Many Months Do You Want to Subscribe';
            $confirm = 'Do You Wanna Buy Package '.$subscription->name;
            $action = 'Buy';
            @endphp
            {{-- percabangan untuk mengetahui organization extend atau ingin membeli --}}
            @if ($organization->status == 1 && $organization->subscription_id == $subscription->id)
              <li>Expire Date : {{ date('d-m-Y', strtotime($organization->expire_date)) }}</li>
              @php
              $message = 'How Many Months Do You Want To Extend The Package';
              $confirm = 'Do You Wanna Extend Package '.$subscription->name;
              $action = 'Extend Period';
              @endphp
            @endif
            {{-- percabangan untuk mengetahui organization extend atau ingin membeli --}}
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
