@extends('layouts/adminMaster')

@section('title', 'Report')

@section('headline', 'Report '.$by)

@section('content')

  <script type="text/javascript">

  $(document).ready(function(){
    // custome-search
    $('.hidden-custome-search').hide();
    $('#custome-search').click(function(){
      $('.hidden-custome-search').toggle();
    });
  });
  </script>

  <div class="row">
    <div class="col-md-4">
      <button class="btn btn-primary" id="custome-search" type="button" name="button">Custome Search</button>
      <div class="hidden-custome-search">
        <br>
        <form class="" action="/admin/report" method="post">
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
                  <input type="date" name="start_date" value="{{ $now }}">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col">
                  <label for="">End Date</label>
                </div>
                <div class="col">
                  <input type="date" name="end_date" value="{{ $now }}">
                </div>
              </div>
              <br>
              <input class="btn btn-primary" type="submit" name="submit" value="search">
            </div>
          </div>
          {{ csrf_field() }}
        </form>
        {{-- hidden-custome search --}}
      </div>
      {{-- offset-md4 col-md-4 --}}
    </div>

    <div class="offset-md-5 col-md-3" style="padding-right:0!important;">
      <div class="row text-right btn-block" style="margin-right:0!important;">

        <form class="col-md-12" action="/admin/report" method="post">
          <div class="input-group mb-3" style="margin-bottom:0!important;">
            <select class="form-control" aria-describedby="button-addon2" autocomplete="off" name="by">
              <option value="week">This Week</option>
              <option value="month">This Month</option>
              <option value="year">This Year</option>
              <option value="all">All Periode</option>
            </select>
            <div class="input-group-append">
              <button id="button-addon2" class="btn btn-primary" type="submit" name="submit">Filter</button>
            </div>
          </div>
          {{ csrf_field() }}
        </form>

        {{-- div class="row text-right btn-block" --}}
      </div>
      {{-- offset md 5 col md 3 --}}
    </div>
    {{-- row --}}
  </div>

  <br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th>Subscription Name</th>
      <th>Quantity</th>
      <th>Period</th>
      <th>Amount</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
      $j = 0;
      $total = 0;
    @endphp
  @foreach ($subscriptions as $subscription)
    <tr>
        <th scope="row">{{ $i }}</th>
        <td>
          @foreach ($packages as $package)
            @if ($package->name == $subscription->name)
              <a class="btn" href="/admin/report/{{ $package->id }}">{{ $subscription->name }}</a>
            @endif
          @endforeach
        </td>
        <td>{{ number_format($subscription->count, 0, ",", ".") }}</td>
        <td>{{ $subscription->period }}</td>
        <td>Rp.{{ number_format($subscription->amount,2,",",".") }}</td>
        @php
          $total = $subscription->amount + $total;
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
    <td></td>
    <td></td>
    <td>Rp.{{ number_format($total,2,",",".") }}</td>
  </tr>
</tbody>
</table>

@endsection
