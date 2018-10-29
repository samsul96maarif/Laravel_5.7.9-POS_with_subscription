@extends('layouts/adminMaster')

@section('title', '{{ $subscription->name }}')

@section('content')

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
          <form class="" action="/admin/subscription/{{ $subscription->id }}/edit" method="get">
            <input type="submit" name="submit" value="edit">
          </form>
          <form class="" action="/admin/subscription/{{ $subscription->id }}/delete" method="post">
            {{ method_field('DELETE') }}
            <input type="submit" name="submit" value="delete">
            {{ csrf_field() }}
          </form>
        </td>
    </tr>
  </table>

@endsection
