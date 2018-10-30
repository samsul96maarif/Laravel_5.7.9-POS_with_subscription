@extends('layouts/userMaster')

@section('title', 'Report')

@section('content')
  <h1>Report By Customer</h1>
  <a href="/report">Report by Item</a>
  <br>
  <br>
  <table>
    <th>No</th>
    <th>Customer</th>
    <th>Amount</th>
    @php
      $j = 0;
      $i = 1;
      $total = 0;
    @endphp
  @foreach ($customers as $customer)
    <tr>
        <td>{{ $i }}</td>
        <td>{{ $customer->name }}</td>
        <td>{{ $customer->total }}</td>
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
    <td>Total</td>
    <td>{{ $total }}</td>
  </tr>
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
