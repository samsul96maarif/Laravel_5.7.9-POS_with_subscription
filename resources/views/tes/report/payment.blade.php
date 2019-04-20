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
      {{-- <div class="card">
        <div class="card-body">
        </div>
      </div> --}}
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

    <div class="offset-md-4 col-md-4">
      <form class="" action="/admin/report" method="post">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-7">
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


    {{-- row --}}
  </div>

  <br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th>Item Name</th>
      <th>Jumlah</th>
      <th>Periode</th>
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
        <td>{{ $subscription->name }}</td>
        <td>{{ $subscription->count }}</td>
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
