@extends('layouts/userMaster')

@section('title', 'Items')

@section('headline', 'Items')

@section('content')

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
          <form class="" action="/item/{{ $item->id }}/edit" method="get">
            <input type="submit" name="submit" value="edit">
          </form>
          <form class="" action="/item/{{ $item->id }}/delete" method="post">
            {{ method_field('DELETE') }}
            <input type="submit" name="submit" value="delete">
            {{ csrf_field() }}
          </form>
        </td>
        @php
          $i++;
        @endphp
    </tr>
  @endforeach
</tbody>
</table>

  <form class="" action="/item/create" method="get">
    <input type="submit" name="submit" value="tambah item">
  </form>

@endsection
