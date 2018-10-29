@extends('layouts/adminMaster')

@section('title', 'Contacts')

@section('content')

  <table>
    <th>Store Id</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Company Name</th>
    <th>Email</th>
    @foreach ($contacs as $contact)
      <tr>
        <td>{{ $contact->store_id }}</td>
        <td>{{ $contact->name }}</td>
        <td>{{ $contact->phone }}</td>
        <td>{{ $contact->company_name }}</td>
        <td>{{ $contact->email }}</td>
      </tr>
    @endforeach
  </table>

@endsection
