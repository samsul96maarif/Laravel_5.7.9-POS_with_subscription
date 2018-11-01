@extends('layouts/userMaster')

@section('title', 'Report')

@section('headline', 'Report By Items')

@section('content')
  <a href="/report/by_customer">Report by Customer</a>
  <br>
  <br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th>Item Name</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
      $j = 0;
      $total = 0;
    @endphp
  @foreach ($items as $item)
    <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $item->name }}</td>
        <td>Rp.{{ $item->total }}</td>
        @php
          $total = $item->total + $total;
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
<form class="" action="/report/item" method="post">
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
<form class="" action="/report/item" method="post">
  <label for="">Start Date</label>
  <input type="date" name="start_date" value="{{ $now }}">
  <br>
  <label for="">End Date</label>
  <input type="date" name="end_date" value="{{ $now }}">
  <br>
  <input type="submit" name="submit" value="cari">
  {{ csrf_field() }}
</form>

@endsection
