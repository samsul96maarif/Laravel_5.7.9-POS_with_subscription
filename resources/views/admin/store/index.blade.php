@extends('layouts/adminMaster')

@section('title', 'Stores')

@section('content')

  <form class="" action="/admin/store/filter" method="post">
    <select class="" name="filter">
      <option value="active">Active</option>
      <option value="awaiting">Awaiting</option>
      <option value="not">Not Subscribe</option>
      <option value="all">All Periode</option>
    </select>
    <input type="submit" name="submit" value="cari">
    {{ csrf_field() }}
  </form>
  <br>
  <br>

  @php
    $i = 1;
  @endphp
  <table>
    <th>No</th>
    <th>Owner</th>
    <th>Subscription</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Company Address</th>
    <th>Zipcode</th>
    <th>Status</th>
    <th>Expiry Date</th>
    <th>Action</th>
    @foreach ($stores as $store)
      <tr>
        <td>{{ $i }}</td>
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
        <td>{{ $store->name }}</td>
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
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="0" hidden>
                  <input type="submit" name="submit" value="deactivate">
                  {{ csrf_field() }}
                </form>
                <form class="" action="/admin/store/{{ $store->id }}/extend" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="expire_date" value="30" hidden>
                  <input type="submit" name="submit" value="extend period">
                  {{ csrf_field() }}
                </form>
              @endif
          </td>
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
  </table>

@endsection
