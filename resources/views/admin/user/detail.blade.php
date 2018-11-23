@extends('layouts/adminMaster')

@section('title', $user->name)

@section('content')

  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="my-0 font-weight-normal">Username : {{ $user->username }}</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <label for="" class="col-form-label">Name</label>
            </div>
            <div class="col">
              <span class="form-control text-center">{{ $user->name}}</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-3">
              <label for="" class="col-form-label">Email</label>
            </div>
            <div class="col">
              <span class="form-control text-center">{{ $user->email }}</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-3" style="padding-right:0;">
              <label for="" class="col-form-label">Store Name</label>
            </div>
            <div class="col">
              @if ($store == null)
                <p class="form-control text-center">dont have yet</p>
              @else
                <a class="col btn btn-outline-link" href="/admin/store/{{ $store->id }}">{{ $store->name }}</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
