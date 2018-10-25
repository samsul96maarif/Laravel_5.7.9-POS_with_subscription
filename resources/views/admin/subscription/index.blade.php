@extends('layouts/adminMaster')

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
        <td><a href="/admin/subscription/{{ $subscription->id }}">{{ $subscription->name }}</a></td>
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
            <!-- <input type="hidden" name="_methode" value="DELETE"> -->
          </form>
        </td>
    </tr>
  @endforeach
  </table>

  <form class="" action="/admin/subscription/create" method="get">
    <input type="submit" name="submit" value="tambah subscription">
  </form>
@endsection
