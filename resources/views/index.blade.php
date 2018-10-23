@extends('layouts/master')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul</h1>

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
        <td>
          <form class="" action="/subscription/{{ $subscription->id }}/pilih" method="get">
            <input type="submit" name="submit" value="beli">
          </form>
        </td>
    </tr>
  @endforeach
  </table>

@endsection
