@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul</h1>

  <table>
    <th>No</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Company Name</th>
    <th>Email</th>
    <th>Action</th>
    @php
      $i = 1;
    @endphp
  @foreach ($contacts as $contact)
    <tr>
        <td>{{ $i }}</td>
        <td>{{ $contact->name }}</td>
        <td>{{ $contact->phone }}</td>
        <td>{{ $contact->company_name }}</td>
        <td>{{ $contact->email }}</td>
        <td>
          <form class="" action="/contact/{{ $contact->id }}/edit" method="edit">
            <input type="submit" name="submit" value="edit">
          </form>
          <form class="" action="/contact/{{ $contact->id }}/delete" method="post">
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
  <form class="" action="/contact/create" method="get">
    <input type="submit" name="submit" value="tambah contact">
  </form>

@endsection
