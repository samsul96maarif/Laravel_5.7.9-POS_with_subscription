@extends('layouts/adminMaster')

@section('title', $subscription->name)

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <table class="table">
    <thead>
      <th>Package Name</th>
      <th>Price</th>
      <th>Items Quota</th>
      <th>Invoices Quota</th>
      <th>Employees Quota</th>
      {{-- action dialih fungsikan dari index --}}
      {{-- <th>Action</th> --}}
    </thead>
    <tbody>
      <tr>
        <td>{{ $subscription->name }}</td>
        <td>Rp.{{ number_format($subscription->price,2,",",".") }}</td>
        @if ($subscription->num_items === null)
          <td><i class="fas fa-infinity"></i></td>
        @else
          <td>{{ number_format($subscription->num_items, 0, ",", ".") }}</td>
        @endif

        @if ($subscription->num_invoices === null)
          <td><i class="fas fa-infinity"></i></td>
        @else
          <td>{{ number_format($subscription->num_invoices, 0, ",", ".") }}</td>
        @endif

        @if ($subscription->num_users === null)
          <td><i class="fas fa-infinity"></i></td>
        @else
          <td>{{ number_format($subscription->num_users, 0, ",", ".") }}</td>
        @endif
        {{-- action dialih fungsikan hanya dari index --}}
        {{-- <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <form class="" action="/admin/subscription/{{ $subscription->id }}/edit" method="get">
                <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
              </form>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/admin/subscription/{{ $subscription->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <input class="btn btn-outline-danger" type="submit" name="submit" value="delete">
                {{ csrf_field() }}
              </form>
            </div>
          </div>
        </td> --}}
      </tr>
    </tbody>
  </table>

  <div class="row">

    <div class="col-md-3">
      <div class="row btn-block">

        <form class="col-md-12" action="/admin/subscription/{{ $subscription->id }}" method="post">
          <div class="input-group mb-3" style="margin-bottom:0!important;">
            <select class="form-control" aria-describedby="button-addon2" autocomplete="off" name="filter">
              <option value="active">Active</option>
              <option value="awaiting">Awaiting Payment</option>
              {{-- <option value="all">All</option> --}}
            </select>
            <div class="input-group-append">
              <button id="button-addon2" class="btn btn-primary" type="submit" name="submit">Filter</button>
            </div>
          </div>
          {{ csrf_field() }}
        </form>

        {{-- div class="row text-right btn-block" --}}
      </div>
      {{-- col md 3 --}}
    </div>

    {{-- <div class="col-md-4">
      <div class="card md">
        <div class="card-body">
          <form class="" action="/admin/subscription/{{ $subscription->id }}" method="post">
            <div class="row">
              <div class="col">
                <select class="form-control" name="filter">
                  <option value="active">Active</option>
                  <option value="awaiting">Awaiting Payment</option>
                  <option value="all">All</option>
                </select>
              </div>
              <div class="col-md-3">
                <input class="btn btn-primary" type="submit" name="submit" value="cari">
              </div>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div> --}}
    <div class="col">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="my-0 font-weight-normal">{{ $filter }} Company</h4>
        </div>
      </div>
    </div>
  </div>
  <br>

  <table class="table">
    <thead>
      <th>#</th>
      <th>Owner</th>
      <th>Company Name</th>
      {{-- <th>Phone</th> --}}
      {{-- <th>Company Address</th> --}}
      {{-- <th>Zipcode</th> --}}
      <th>Status</th>
      <th>Expiry Date</th>
      {{-- dialih fungsikan lewat payment --}}
      {{-- <th>Action</th> --}}
    </thead>
    <tbody>
      @php
        $i=1;
      @endphp
      @foreach ($organizations as $organization)
        <tr>
          <th scope="row">{{ $i }}</th>
          @foreach ($users as $user)
            @if ($organization->user_id == $user->id)
              <td><a class="btn" href="/admin/user/{{ $organization->user_id }}">{{ $user->name }}</a></td>
            @endif
          @endforeach
          <td><a class="btn" href="/admin/organization/{{ $organization->id }}">{{ $organization->name }}</a></td>
          {{-- <td>{{ $organization->phone }}</td> --}}
          {{-- <td>{{ $organization->company_address}}</td> --}}
          {{-- <td>{{ $organization->zipcode}}</td> --}}
          @if ($organization->subscription_id > 0)
            @if ($organization->status == 0)
              <td>
                <a href="#" class="btn btn-outline-secondary">Awaiting Payment</a>
              </td>
            @else
              <td>
                <a href="#" class="btn btn-outline-primary">Active</a>
              </td>
            @endif
            {{-- filter agar yang keluar hanya yng ada expire date nya --}}
            @if ($organization->expire_date != null)
              <td>{{ date('d-m-Y', strtotime($organization->expire_date)) }}</td>
            @else
              <td></td>
            @endif
            {{-- fungsi ini dialihkan ke payment --}}
            {{-- <td>
              @if ($organization->status == 0)
              <div class="row">
                <div class="col">
                  <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                    {{ method_field('PUT') }}
                    <input type="text" name="status" value="1" hidden>
                    <input class="btn btn-primary" type="submit" name="submit" value="activate">
                    {{ csrf_field() }}
                  </form>
                </div>
              </div>
              @else
                <div class="row btn-atas">
                  <div class="col">
                    <form class="" action="/admin/organization/{{ $organization->id }}/extend" method="post">
                      {{ method_field('PUT') }}
                      <input type="text" name="expire_date" value="30" hidden>
                      <input class="btn btn-warning" type="submit" name="submit" value="extend period">
                      {{ csrf_field() }}
                    </form>
                  </div>
                </div>
                </form>
                <div class="row">
                  <div class="col">
                    <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                      {{ method_field('PUT') }}
                      <input type="text" name="status" value="0" hidden>
                      <input class="btn btn-danger" type="submit" name="submit" value="deactivate">
                      {{ csrf_field() }}
                  </div>
                </div>
              @endif
            </td> --}}
          @endif
        </tr>
        @php
          $i++;
        @endphp
      @endforeach

    </tbody>
  </table>

@endsection
