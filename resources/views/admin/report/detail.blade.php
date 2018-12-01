@extends('layouts/adminMaster')

@section('title', $subscription->name)

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table">
    <thead>
      <th>Package Name</th>
      <th>Price</th>
      <th>Items Quota</th>
      <th>Invoices Quota</th>
      <th>Contacts Quota</th>
    </thead>
    <tbody>
      <tr>
        <td>{{ $subscription->name }}</td>
        <td>Rp.{{ number_format($subscription->price,2,",",".") }}</td>
        @if ($subscription->num_items === null)
          <td><i class="fas fa-infinity"></i></td>
        @else
          <td>{{ number_format($subscription->num_items, 0, ",", ".") }}</td>
        @endif

        @if ($subscription->num_invoices === null)
          <td><i class="fas fa-infinity"></i></td>
        @else
          <td>{{ number_format($subscription->num_invoices, 0, ",", ".") }}</td>

        @endif
        @if ($subscription->num_users === null)
          <td><i class="fas fa-infinity"></i></td>
        @else
          <td>{{ $subscription->num_users }}</td>
        @endif
      </tr>
    </tbody>
  </table>

  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="my-0 font-weight-normal">Payment</h4>
        </div>
      </div>
    </div>
  </div>
  <br>

  <table class="table">
    <thead>
      <th>#</th>
      <th>Date</th>
      <th>Unique Code</th>
      <th>Company Name</th>
      <th>Owner</th>
      <th>Period</th>
      <th>Amount</th>
    </thead>
    <tbody>
      @php
        $i=1;
        $total = 0;
      @endphp
      @foreach ($payments as $payment)
        <tr>
          <th scope="row">{{ $i }}</th>
            <td>{{ date('d-m-Y', strtotime($payment->updated_at)) }}</td>
            <td>{{ $payment->uniq_code}}</td>
            {{-- mencari organization name --}}
            @foreach ($organizations as $organization)
              @if ($organization->id == $payment->organization_id)
                <td><a class="btn" href="/admin/organization/{{ $organization->id }}">{{ $organization->name }}</a></td>
                {{-- mencari user yang memiliki organization --}}
                @foreach ($users as $user)
                    @if ($organization->user_id == $user->id)
                      <td><a class="btn" href="/admin/user/{{ $organization->user_id }}">{{ $user->name }}</a></td>
                    @endif
                @endforeach

              @endif
              {{-- mencari organization --}}
            @endforeach
            <td>{{ $payment->period }}</td>
            <td>Rp.{{ number_format($payment->amount,2,",",".") }}</td>
        </tr>
        @php
          $total = $payment->amount + $total;
          $i++;
        @endphp
        {{-- perulangan paymnet --}}
      @endforeach
      <tr>
        <th>Total</th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Rp.{{ number_format($total,2,",",".") }}</td>
      </tr>
    </tbody>
  </table>

@endsection
