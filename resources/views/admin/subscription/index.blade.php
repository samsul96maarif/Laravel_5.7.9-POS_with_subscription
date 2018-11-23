@extends('layouts/adminMaster')

@section('title', 'Subscriptions')

@section('headline', 'Subscriptions')

@section('content')

  @php
    $alert = 'alert-success';
  @endphp
  @if (session('success') == 'Delete package Failed, package has been used')
    @php
      $alert = 'alert-danger';
    @endphp
  @endif

  @if (session()->has('success'))
    <div class="alert {{ $alert }}">{{ session('success') }}</div>
  @endif

  <table class="table">
    <thead>
      <th>#</th>
      <th>Package Name</th>
      <th>Price</th>
      <th>Invoices quota</th>
      <th>Contacts quota</th>
      <th>Action</th>
    </thead>
    <tbody>
      @php
        $i = 1;
      @endphp
      @foreach ($subscriptions as $subscription)
        <tr>
          <th scope="row">{{ $i }}</th>
          <td><a class="btn" href="/admin/subscription/{{ $subscription->id }}">{{ $subscription->name }}</a></td>
          <td>Rp.{{ number_format($subscription->price,2,",",".") }}</td>
          <td>{{ $subscription->num_invoices }}</td>
          <td>{{ $subscription->num_users }}</td>
          <td>
            <div class="row">
              <div class="col text-right btn-kiri">
                <form class="" action="/admin/subscription/{{ $subscription->id }}/edit" method="get">
                  <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
                </form>
              </div>
              <div class="col text-left btn-kanan">
                <form class="" action="/admin/subscription/{{ $subscription->id }}/delete" method="post">
                  {{ method_field('DELETE') }}
                  <input onclick="return confirm('Are you sure wanna delete package {{ $subscription->name}}')" class="btn btn-outline-danger" type="submit" name="submit" value="delete">
                  {{ csrf_field() }}
                </form>
              </div>
            </div>
          </td>
        </tr>
        @php
          $i++;
        @endphp
      @endforeach
    </tbody>
  </table>

  <form class="" action="/admin/subscription/create" method="get">
    <input class="btn btn-primary" type="submit" name="submit" value="add subscription">
  </form>
@endsection
