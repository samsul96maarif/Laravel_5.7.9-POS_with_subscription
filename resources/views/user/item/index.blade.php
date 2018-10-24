@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul</h1>

  <table>
    <th>No</th>
    <th>Name</th>
    <th>Price</th>
    @php
      $i = 1;
    @endphp
  @foreach ($items as $item)
    <tr>
        <td>{{ $i }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->price }}</td>
        <td>
          <form class="" action="/item/{{ $item->id }}/edit" method="edit">
            <input type="submit" name="submit" value="edit">
          </form>
        </td>
        <td>
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
  </table>
  <form class="" action="/item/create" method="get">
    <input type="submit" name="submit" value="tambah item">
  </form>

@endsection
