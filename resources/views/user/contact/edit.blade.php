@extends('layouts/userMaster')

@section('title', 'Edit Contact')

@section('headline', 'Edit ',$contact->name)

@section('content')

  @if (session()->has('success'))
    <div class="alert alert-danger">{{ session('success') }}</div>
  @endif

  @if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
  @endif

  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <form class="" action="/contact/{{ $contact->id }}" method="post">
            {{-- untuk mendeklarasikan bahwa ini menggunakn metode put di web.php nya Route::put --}}
            {{ method_field('PUT') }}

            {{-- kolom untuk isi tabel "name" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <div class="row">
              <div class="col-md-4">
                <p>Name</p>
              </div>
              <div class="col-md-8">
                <input class="form-control" type="text" name="name" value="{{ $contact->name }}" placeholder="name">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('name'))
                  <p>{{ $errors->first('name') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <p>Phone</p>
              </div>
              <div class="col-md-8">
                <input type="text" name="phone" class="form-control" value="{{ $contact->phone }}" placeholder="Phone">
                @if($errors->has('phone'))
                  <p>{{ $errors->first('phone') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <p>Company Name</p>
              </div>
              <div class="col-md-8">
                <input type="text" name="company_name" class="form-control" value="{{ $contact->company_name }}" placeholder="Company Name">
                @if($errors->has('company_name'))
                  <p>{{ $errors->first('company_name') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <p>Email</p>
              </div>
              <div class="col-md-8">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $contact->email }}" placeholder="Email">
                @if ($errors->has('email'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="update">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
