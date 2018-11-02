@extends('layouts/userMaster')

@section('title', 'Contacts')

@section('headline', 'Contacts')

@section('content')

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Phone</th>
      <th scope="col">Company Name</th>
      <th scope="col">Email</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
    @foreach ($contacts as $contact)
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $contact->name }}</td>
        <td>{{ $contact->phone }}</td>
        <td>{{ $contact->company_name }}</td>
        <td>{{ $contact->email }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <form class="" action="/contact/{{ $contact->id }}/edit" method="edit">
                  <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
              </form>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/contact/{{ $contact->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                  <input class="btn btn-outline-danger" type="submit" name="submit" value="delete">
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

  <form class="" action="/contact/create" method="get">
    <input class="btn btn-primary" type="submit" name="submit" value="add contact">
  </form>

@endsection
