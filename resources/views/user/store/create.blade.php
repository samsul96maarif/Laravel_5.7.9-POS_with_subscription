@extends('layouts/userMaster')

@section('title', 'Create Store')

@section('content')
  <h1>Create Store</h1>

  <form class="" action="/store" method="post" value="post">

    {{-- kolom untuk isi tabel "name" --}}
    {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
    yang nilainya akan terhapus --}}
    <input type="text" name="name" value="{{ old('name') }}" placeholder="Business Name"><br><br>
    {{-- untuk mengeluarkan error pada value "name" --}}
    @if($errors->has('name'))
      <p>{{ $errors->first('name') }}</p>
    @endif

    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone"><br><br>
    @if($errors->has('phone'))
      <p>{{ $errors->first('phone') }}</p>
    @endif

    <textarea name="company_address" rows="8" cols="80" placeholder="Company Address">{{ old('company_address') }}</textarea><br><br>
    @if($errors->has('company_address'))
      <p>{{ $errors->first('company_address') }}</p>
    @endif

    <input type="text" name="zipcode" value="{{ old('zipcode') }}" placeholder="zipcode"><br><br>
    @if($errors->has('zipcode'))
      <p>{{ $errors->first('zipcode') }}</p>
    @endif

    <input type="submit" name="submit" value="save">
    {{ csrf_field() }}
  </form>
@endsection
