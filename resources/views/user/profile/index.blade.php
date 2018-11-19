@extends('layouts/userMaster')

@section('title', 'home')

@section('headline', 'Profile')

@section('content')

  @if (session()->has('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

    <form class="" action="/profile/{{ $user->id }}" method="post" value="post" enctype="multipart/form-data">
      {{ method_field('PUT') }}
          {{-- <div class="row">
            <div class="col-md-2">
              <img src="{{ asset('logo/'.$store->logo) }}" alt="Logo" class="img-thumbnail">
            </div>

            <div class="col-md-6 text-left">
              <p class="logo">This logo will appear on the documents such as sales order and purchase order that you created.</p>
              <p class="logo">Preferred Image Size: 240px x 240px @ 72 DPI Maximum size of 1MB. Supported types : .PNG, .JPG, .JPEG</p>
            </div>
          </div> --}}

          {{-- <div class="row">
            <div class="col">
              <input type="file" name="logo" value=""><br>
            </div>
          </div>
          <br> --}}

          <div class="row">
            <div class="col-md-2">
              <p>Name</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="{{ $user->name }}" placeholder="Name">
              {{-- untuk mengeluarkan error pada value "name" --}}
              @if($errors->has('name'))
                <p>{{ $errors->first('name') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Username</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="username" value="{{ $user->username }}" placeholder="Username" readonly>
              @if($errors->has('username'))
                <p>{{ $errors->first('username') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Email</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="email" name="email" value="{{ $user->email }}" placeholder="Email">
              @if($errors->has('email'))
                <p>{{ $errors->first('email') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <input type="submit" name="submit" value="update" class="btn btn-primary">
            </div>
          </div>
          {{ csrf_field() }}
    </form>

@endsection
