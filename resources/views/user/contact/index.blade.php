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
