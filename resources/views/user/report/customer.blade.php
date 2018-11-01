@extends('layouts/userMaster')

@section('title', 'Report')

@section('headline', 'Report By customers')

@section('content')
  <a href="/report">Report by Item</a>
  <br>
  <br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th>Customer</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    @php
      $j = 0;
      $i = 1;
      $total = 0;
    @endphp
  @foreach ($customers as $customer)
    <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $customer->name }}</td>
        <td>Rp.{{ $customer->total }}</td>
        @php
          $total = $customer->total + $total;
          $i++;
          $j++;
        @endphp
    </tr>
  @endforeach
  @if ($j == 0)
    <tr>
      <td></td>
      <td></td>
      <td></td>
    </tr>
  @endif
  <tr>
    <th>Total</th>
    <td></td>
    <td>Rp.{{ $total }}</td>
  </tr>
</tbody>
  </table>

  <br>
  <br>
  <form class="" action="/report/customer" method="post">
    <select class="" name="by">
      <option value="week">This Week</option>
      <option value="month">This Month</option>
      <option value="year">This Year</option>
      <option value="all">All Periode</option>
    </select>
    <input type="submit" name="submit" value="cari">
    {{ csrf_field() }}
  </form>
  <br>
  <br>
  <form class="" action="/report/customer" method="post">
    <label for="">Start Date</label>
    <input type="date" name="start_date" value="">
    <br>
    <label for="">End Date</label>
    <input type="date" name="end_date" value="">
    <br>
    <input type="submit" name="submit" value="cari">
    {{ csrf_field() }}
  </form>

@endsection
