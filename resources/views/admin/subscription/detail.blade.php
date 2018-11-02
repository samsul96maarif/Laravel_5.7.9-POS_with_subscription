@extends('layouts/adminMaster')

@section('title', $subscription->name)

@section('content')

  <table class="table">
    <thead>
      <th>Name</th>
      <th>Price</th>
      <th>Number invoices</th>
      <th>Number users</th>
      <th>Action</th>
    </thead>
    <tbody>
      <tr>
        <td>{{ $subscription->name }}</td>
        <td>{{ $subscription->price }}</td>
        <td>{{ $subscription->num_invoices }}</td>
        <td>{{ $subscription->num_users }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <form class="" action="/admin/subscription/{{ $subscription->id }}/edit" method="get">
                <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
              </form>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/admin/subscription/{{ $subscription->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <input class="btn btn-outline-danger" type="submit" name="submit" value="delete">
                {{ csrf_field() }}
              </form>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <br>
  <table class="table">
    <thead>
      <th>#</th>
      <th>Owner</th>
      <th>Name</th>
      <th>Phone</th>
      <th>Company Address</th>
      <th>Zipcode</th>
      <th>Status</th>
      <th>Expiry Date</th>
      <th>Action</th>
    </thead>
    <tbody>
      @php
        $i=1;
      @endphp
      @foreach ($stores as $store)
        <tr>
          <th scope="row">{{ $i }}</th>
          @foreach ($users as $user)
            @if ($store->user_id == $user->id)
              <td><a class="btn" href="/admin/user/{{ $store->user_id }}">{{ $user->name }}</a></td>
            @endif
          @endforeach
          <td><a class="btn" href="/admin/store/{{ $store->id }}">{{ $store->name }}</a></td>
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
            <td>
              @if ($store->status == 0)
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              @else
                <div class="row btn-atas">
                  <div class="col">
                    <form class="" action="/admin/store/{{ $store->id }}/extend" method="post">
                      {{ method_field('PUT') }}
                      <input type="text" name="expire_date" value="30" hidden>
                      <input class="btn btn-warning" type="submit" name="submit" value="extend period">
                      {{ csrf_field() }}
                    </form>
                  </div>
                </div>
                </form>
                <div class="row">
                  <div class="col">
                    <form class="" action="/admin/store/{{ $store->id }}" method="post">
                      {{ method_field('PUT') }}
                      <input type="text" name="status" value="0" hidden>
                      <input class="btn btn-danger" type="submit" name="submit" value="deactivate">
                      {{ csrf_field() }}
                  </div>
                </div>
              @endif
            </td>
          @endif
        </tr>
        @php
          $i++;
        @endphp
      @endforeach

    </tbody>
  </table>

@endsection
