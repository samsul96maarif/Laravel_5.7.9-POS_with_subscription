@extends('layouts.'.$extend)

@section('title', 'change password')

@section('headline', 'Change Password')

@section('content')
  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">

        <div class="card-body">

          <form class="form-horizontal" role="form" method="POST" action="{{ route('password.update') }}">

            {{ csrf_field() }}
            {{ method_field('put') }}

            <div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-4">
                  <label for="current_password" class="col-form-label">Current Password</label>
                </div>
                <div class="col">
                  <input id="current_password" type="password" class="form-control" name="current_password" autofocus>                  <span class="help-block">{{ $errors->first('current_password') }}</span>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-4">
                  <label for="password" class="col-form-label">New Password</label>
                </div>
                <div class="col">
                  <input id="password" type="password" class="form-control" name="password">
                  <span class="help-block">{{ $errors->first('password') }}</span>
                </div>
              </div>
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
              <div class="row">
                <div class="col-md-4">
                  <label for="password_confirmation" class="col-form-label">New Password Confirmation</label>
                </div>
                <div class="col">
                  <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
                  <span class="help-block">{{ $errors->first('password_confirmation') }}</span>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Change Password
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
