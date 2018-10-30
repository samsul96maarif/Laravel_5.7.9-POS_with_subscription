@extends('layouts/adminMaster')

@section('title', $user->name)

@section('content')

  <table>
    <th>Name</th>
    <th>Username</th>
    <th>Email</th>
    <th>Store</th>
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        @if ($store == null)
          <td>dont have yet</td>
        @else
          <td><a href="/admin/store/{{ $store->id }}">{{ $store->name }}</a></td>
        @endif
      </tr>
  </table>

@endsection
