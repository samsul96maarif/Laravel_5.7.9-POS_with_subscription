@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul, this page for company profile</h1>

  {{-- pada <form> kita wajib menyisipkan code enctype="multipart/form-data"
untuk upload file / gambar fungsi nya agar file yang kita upload itu dikenali,
ini wajib ya jangan sampe lupa --}}
<form class="" action="/store" method="post" value="post">

  {{-- kolom untuk isi tabel "name" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="name" value="{{ old('name') }}" placeholder="Business Name"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('name'))
    <p>{{ $errors->first('name') }}</p>
  @endif

  {{-- kolom untuk isi tabel "num_invoices" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('phone'))
    <p>{{ $errors->first('phone') }}</p>
  @endif

  {{-- kolom untuk isi tabel "price" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <textarea name="company_address" rows="8" cols="80" placeholder="Company Address">{{ old('company_address') }}</textarea><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('company_address'))
    <p>{{ $errors->first('company_address') }}</p>
  @endif

  {{-- kolom untuk isi tabel "num_users" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="zipcode" value="{{ old('zipcode') }}" placeholder="zipcode"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('zipcode'))
    <p>{{ $errors->first('zipcode') }}</p>
  @endif

  <input type="submit" name="submit" value="save">
  {{ csrf_field() }}
</form>
@endsection
