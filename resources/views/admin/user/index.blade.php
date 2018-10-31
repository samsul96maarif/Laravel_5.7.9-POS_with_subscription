@extends('layouts/adminMaster')

@section('title', 'Users')

@section('headline', 'Customers')

@section('content')
@php
  $i=1;
@endphp
  <table>
    <th>No</th>
    <th>Name</th>
    <th>Username</th>
    <th>Email</th>
    <th>Store</th>
    @foreach ($users as $user)
      <tr>
        <td>{{ $i }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        @foreach ($stores as $store)
          @if ($user->id == $store->user_id)
            <td><a href="/admin/store/{{ $store->id }}">{{ $store->name }}</a></td>
          @endif
        @endforeach
      </tr>
      @php
        $i++;
      @endphp
    @endforeach
  </table>

  @foreach ($stores as $store)

  @endforeach

@endsection
