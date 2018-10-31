@extends('layouts/userMaster')

@section('title', 'Create Contact')

@section('headline', 'Create Contact')

@section('content')

  <form class="" action="/contact" method="post" value="post">

    {{-- kolom untuk isi tabel "name" --}}
    {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
    yang nilainya akan terhapus --}}
    <input type="text" name="name" value="{{ old('name') }}" placeholder="Name"><br><br>
    {{-- untuk mengeluarkan error pada value "name" --}}
    @if($errors->has('name'))
      <p>{{ $errors->first('name') }}</p>
    @endif

    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone"><br><br>
    @if($errors->has('phone'))
      <p>{{ $errors->first('phone') }}</p>
    @endif

    <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name"><br><br>
    @if($errors->has('company_name'))
      <p>{{ $errors->first('company_name') }}</p>
    @endif

    <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="Email">

    @if ($errors->has('email'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif

    <input type="submit" name="submit" value="save">
    {{ csrf_field() }}
  </form>
@endsection
