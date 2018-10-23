@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul</h1>

  <table>
    <th>Name</th>
    <th>Price</th>
    <th>Number invoices</th>
    <th>Number users</th>
    <th>Action</th>
    <tr>
        <td>{{ $subscription->name }}</td>
        <td>{{ $subscription->price }}</td>
        <td>{{ $subscription->num_invoices }}</td>
        <td>{{ $subscription->num_users }}</td>
        <td>
        </td>
    </tr>
  </table>
  <form class="" action="/subscription/beli" method="post">
    <input type="hidden" name="id" value="{{ $subscription->id }}">
    {{ csrf_field() }}
    <input type="submit" name="submit" value="beli">
  </form>

@endsection
