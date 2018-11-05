@extends('layouts/adminMaster')

@section('title', 'Users')

@section('headline', 'Customers')

@section('content')

  <div class="col-md-4 offset-md-8">
    <form class="" action="/admin/user/search" method="get">
      <div class="input-group mb-3">
        <input type="search" name="q" class="form-control" placeholder="Search" aria-describedby="button-addon2" value="">
        <div class="input-group-append">
          <input id="button-addon2" class="btn btn-primary" type="submit" name="submit" value="Search">
        </div>
        {{-- {{ csrf_field() }} --}}
      </div>
    </form>
  </div>

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
