@extends('layouts/adminMaster')

@section('title', 'Stores')

@section('headline', 'payment')

@section('content')

  <div class="row">
    {{-- <div class="col-md-4">
      <div class="card md">
        <div class="card-header">
          appears according to package subscription
        </div>
        <div class="card-body">
          <form class="" action="/admin/store" method="post">
            <div class="row">
              <div class="col">
                <select class="form-control" name="filter">
                  @foreach ($subscriptions as $subscription)
                    <option value={{ $subscription->id }}>{{ $subscription->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <input class="btn btn-primary" type="submit" name="submit" value="Search">
              </div>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div> --}}

    <div class="col-md-4 offset-md-8">
      <form class="" action="/admin/payment/search" method="get">
        <div class="input-group mb-3">
          <input type="search" name="q" class="form-control" placeholder="Search Unique Code" aria-describedby="button-addon2" value="">
          <div class="input-group-append">
            <input id="button-addon2" class="btn btn-primary" type="submit" name="submit" value="Search">
          </div>
        </div>
      </form>
    </div>
  </div>
  <br>
  @php
    $i = 1;
  @endphp
  <table class="table">
    <thead>
      <th>#</th>
      <th>Proof</th>
      <th>Unique Code</th>
      <th>Store Name</th>
      <th>Package Subscription</th>
      <th>Action</th>
    </thead>
    <tbody>
      @foreach ($payments as $payment)
        <tr>
          <th scope="row">{{ $i }}</th>
          {{-- mengecek bila bukti transfer sudah ada atau belum --}}
          @if ($payment->proof != null)
            <td><b>True</b></td>
            {{-- uniq code adalah 3 digit terakhir dari transfer --}}
            <td><a class="btn" href="/admin/payment/{{ $payment->id }}">{{ $payment->uniq_code }}</a></td>
          @else
            <td>False</td>
            {{-- uniq code adalah 3 digit terakhir dari transfer --}}
            <td>{{ $payment->uniq_code }}</td>
          @endif

          @foreach ($stores as $store)
            @if ($payment->store_id == $store->id)
              <td><a class="btn" href="/admin/store/{{ $store->id }}">{{ $store->name }}</a></td>

              @foreach ($subscriptions as $subscription)
                @if ($subscription->id == $store->subscription_id)
                  <td><a class="btn" href="/admin/subscription/{{ $store->subscription_id }}">{{ $subscription->name }}</a></td>
                  <td>
                    {{-- unutk mengaktifkan --}}
                    @if ($store->status == 0)
                      <form class="" action="/admin/store/{{ $store->id }}" method="post">
                        {{ method_field('PUT') }}
                        <input type="text" name="status" value="1" hidden>
                        <input class="btn btn-primary" type="submit" name="submit" value="activate">
                        {{ csrf_field() }}
                      </form>
                      {{-- unutk meng extend --}}
                    @else
                      <div class="row btn-atas">
                        <div class="col">
                          <form class="" action="/admin/store/{{ $store->id }}/extend" method="post">
                            {{ method_field('PUT') }}
                            {{-- rencananya expire date bisa docustome ingin
                            menambah berapa bulan --}}
                            <input type="text" name="expire_date" value="30" hidden>
                            <input class="btn btn-warning" type="submit" name="submit" value="extend period">
                            {{ csrf_field() }}
                          </form>
                        </div>
                      </div>
                      {{-- unutk menonaktifkan --}}
                      {{-- <div class="row">
                        <div class="col">
                          <form class="" action="/admin/store/{{ $store->id }}" method="post">
                            {{ method_field('PUT') }}
                            <input type="text" name="status" value="0" hidden>
                            <input class="btn btn-sm btn-danger"type="submit" name="submit" value="deactivate">
                            {{ csrf_field() }}
                          </form>
                        </div>
                      </div> --}}
                    @endif
                  </td>
                @endif
                {{-- foreach subscription --}}
              @endforeach

            @endif
            {{-- foreach stores --}}
          @endforeach
        </tr>
        @php
        $i++;
        @endphp
        {{-- foreach payments --}}
      @endforeach

    </tbody>
  </table>

@endsection
