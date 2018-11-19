@extends('layouts/userMaster')

@section('title', 'Items')

@section('headline', 'Items')

@section('content')

  @if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
  @endif

  <div class="col-md-4 offset-md-8">
    <form class="" action="/item/search" method="get">
      <div class="input-group mb-3">
        <input type="search" name="q" class="form-control" placeholder="Search" aria-describedby="button-addon2" value="">
        <div class="input-group-append">
          <input id="button-addon2" class="btn btn-primary" type="submit" name="submit" value="Search">
        </div>
      </div>
    </form>
  </div>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Price</th>
      <th scope="col">Stock</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
  @foreach ($items as $item)
    <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $item->name }}</td>
        <td>{{ $item->description }}</td>
        <td>{{ $item->price }}</td>
        <td>{{ $item->stock }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <form class="" action="/item/{{ $item->id }}/edit" method="get">
                <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
              </form>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/item/{{ $item->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                  <input onclick="return confirm('Do you wanna Delete {{ $item->name }}')" class="btn btn-outline-danger" type="submit" name="submit" value="delete">
                {{ csrf_field() }}
              </form>
            </div>
          </div>
        </td>
        @php
          $i++;
        @endphp
    </tr>
  @endforeach
</tbody>
</table>

  <form class="" action="/item/create" method="get">
    <input class="btn btn-primary" type="submit" name="submit" value="add item">
  </form>

@endsection
