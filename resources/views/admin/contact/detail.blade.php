@extends('layouts/adminMaster')

@section('title', 'home')

@section('content')

  <table>
    <th>Name</th>
    <th>Username</th>
    <th>Email</th>
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
      </tr>
  </table>

@endsection
