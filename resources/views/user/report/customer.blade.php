@extends('layouts/userMaster')

@section('title', 'Report')

@section('headline', 'Report By customers '.$by)

@section('content')

  <div class="row">
    <div class="col">
      <a class="btn" href="/report">Report by Item</a>
    </div>
    <div class="col-md-4">
      <form class="" action="/report/customer" method="post">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col">
                <select class="form-control" class="" name="by">
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                  <option value="year">This Year</option>
                  <option value="all">All Periode</option>
                </select>
              </div>
              <div class="col">
                <input class="btn btn-primary" type="submit" name="submit" value="search">
              </div>
            </div>
          </div>
        </div>
        {{ csrf_field() }}
      </form>
    </div>
  </div>

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
          <td>Rp.{{ number_format($customer->total,2,",",".") }}</td>
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
      <td>Rp.{{ number_format($total,2,",",".") }}</td>
    </tr>
  </tbody>
  </table>

  <div class="col-md-4">
    <form class="" action="/report/customer" method="post">
      <div class="card">
        <div class="card-header">
          Custome Search
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col">

              <label for="">Start Date</label>
            </div>
            <div class="col">
              <input type="date" name="start_date" value="">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <label for="">End Date</label>
            </div>
            <div class="col">
              <input type="date" name="end_date" value="">
            </div>
          </div>
          <br>
          <input class="btn btn-primary" type="submit" name="submit" value="search">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>

@endsection
