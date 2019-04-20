@extends('layouts/'.$extend)

@section('title', 'Create Contact')

@section('headline', 'Create Contact')

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <div class="card-body">
          <form class="" action="/contact" method="post" value="post">

            {{-- kolom untuk isi tabel "name" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
            {{-- untuk mengeluarkan error pada value "name" --}}
            @if($errors->has('name'))
              <p>{{ $errors->first('name') }}</p>
            @endif
            <br>

            <input class="form-control" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Phone">
            @if($errors->has('phone'))
              <p>{{ $errors->first('phone') }}</p>
            @endif
            <br>
            <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name" class="form-control">
            @if($errors->has('company_name'))
              <p>{{ $errors->first('company_name') }}</p>
            @endif
            <br>
            <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="form-control">
            @if($errors->has('email'))
              <p>{{ $errors->first('email') }}</p>
            @endif
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="save">
            {{ csrf_field() }}
          </form>

        </div>
      </div>
    </div>
  </div>
@endsection
