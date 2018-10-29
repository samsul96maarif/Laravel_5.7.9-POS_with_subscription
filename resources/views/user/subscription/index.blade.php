@extends('layouts/userMaster')

@section('title', 'Subscription')

@section('content')
  <h1>Subscription</h1>

  <table>
    <th>Name</th>
    <th>Price</th>
    <th>Number invoices</th>
    <th>Number users</th>
    <th>Action</th>
  @foreach ($subscriptions as $subscription)
    <tr>
      <td>{{ $subscription->name }}</td>
      <td>{{ $subscription->price }}</td>
      <td>{{ $subscription->num_invoices }}</td>
      <td>{{ $subscription->num_users }}</td>
      @if ($store->subscription_id == $subscription->id)
        @if ($store->status == 1)
          <td>active</td>
        @else
          <td>awaiting payment</td>
        @endif
      @else
        <td>
          <form class="" action="/subscription/{{ $subscription->id }}/pilih" method="get">
            <input type="submit" name="submit" value="beli">
          </form>
        </td>
      @endif
    </tr>
  @endforeach
  </table>

@endsection
