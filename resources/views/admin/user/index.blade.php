@extends('layouts/adminMaster')

@section('title', 'Users')

@section('headline', 'Customers')

@section('content')
@php
  $i=1;
@endphp
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Store</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
        <tr>
          <th scope="row">{{ $i }}</th>
          <td><a class="btn" href="/admin/user/{{ $user->id }}">{{ $user->name }}</a></td>
          <td>{{ $user->username }}</td>
          <td>{{ $user->email }}</td>
          @foreach ($stores as $store)
            @if ($user->id == $store->user_id)
              <td><a class="btn" href="/admin/store/{{ $store->id }}">{{ $store->name }}</a></td>
            @endif
          @endforeach
        </tr>
        @php
        $i++;
        @endphp
      @endforeach
    </tbody>
  </table>

@endsection
