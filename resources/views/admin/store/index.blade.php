@extends('layouts/adminMaster')

@section('title', 'Stores')

@section('headline', $filter.' Store')

@section('content')

  <div class="row">
    <div class="col-md-4">
      <div class="card md">
        <div class="card-body">
          <form class="" action="/admin/store" method="post">
            <div class="row">
              <div class="col">
                <select class="form-control" name="filter">
                  <option value="active">Active</option>
                  <option value="awaiting">Awaiting Payment</option>
                  <option value="not">Not Subscribe</option>
                  <option value="all">All</option>
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
    </div>

    <div class="col-md-4 offset-md-4">
      <form class="" action="/admin/store/search" method="get">
        <div class="input-group mb-3">
          <input type="search" name="q" class="form-control" placeholder="Search" aria-describedby="button-addon2" value="">
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
      <th>Store Name</th>
      <th>Owner</th>
      <th>Package Subscription</th>
      <th>Phone</th>
      <th>Company Address</th>
      <th>Zipcode</th>
      <th>Status</th>
      <th>Expiry Date</th>
      {{-- <th>Action</th> --}}
    </thead>
    <tbody>
      @foreach ($stores as $store)
        <tr>
          <th scope="row">{{ $i }}</th>
          <td><a href="/admin/store/{{ $store->id }}">{{ $store->name }}</a></td>
          @foreach ($users as $user)
            @if ($store->user_id == $user->id)
              <td><a href="/admin/user/{{ $store->user_id }}">{{ $user->name }}</a></td>
            @endif
          @endforeach
          @foreach ($subscriptions as $subscription)
            @if ($store->subscription_id == $subscription->id)
              <td><a href="/admin/subscription/{{ $store->subscription_id }}">{{ $subscription->name }}</a></td>
            @endif
          @endforeach
          @if ($store->subscription_id == null)
            <td>Dont Have yet</td>
          @endif
          <td>{{ $store->phone }}</td>
          <td>{{ $store->company_address}}</td>
          <td>{{ $store->zipcode}}</td>
          @if ($store->subscription_id > 0)
            @if ($store->status == 0)
              <td>awaiting paymnet</td>
            @else
              <td>active</td>
            @endif
            <td>{{ $store->expire_date }}</td>

            {{-- <td>
              @if ($store->status == 0)
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input class="btn btn-sm btn-primary" type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              @else
                <div class="row btn-atas">
                  <div class="col">
                    <form class="" action="/admin/store/{{ $store->id }}/extend" method="post">
                      {{ method_field('PUT') }}
                      <input type="text" name="expire_date" value="30" hidden>
                      <input class="btn btn-sm btn-warning" type="submit" name="submit" value="extend period">
                      {{ csrf_field() }}
                    </form>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <form class="" action="/admin/store/{{ $store->id }}" method="post">
                      {{ method_field('PUT') }}
                      <input type="text" name="status" value="0" hidden>
                      <input class="btn btn-sm btn-danger"type="submit" name="submit" value="deactivate">
                      {{ csrf_field() }}
                    </form>
                  </div>
                </div>
              @endif
            </td> --}}

          @else
            <td>not subscribe</td>
            <td></td>
            <td></td>
          @endif

        </tr>
        @php
          $i++;
        @endphp
      @endforeach

    </tbody>
  </table>

@endsection
