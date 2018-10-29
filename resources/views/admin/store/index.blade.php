@extends('layouts/adminMaster')

@section('title', 'Stores')

@section('content')

  <table>
    <th>User Id</th>
    <th>Subscription Id</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Company Address</th>
    <th>Zipcode</th>
    <th>Status</th>
    <th>Expiry Date</th>
    <th>Action</th>
    @foreach ($stores as $store)
      <tr>
        <td><a href="/admin/user/{{ $store->user_id }}">{{ $store->user_id }}</a></td>
        <td><a href="/admin/subscription/{{ $store->subscription_id }}">{{ $store->subscription_id}}</a></td>
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
    @endforeach
  </table>

@endsection
