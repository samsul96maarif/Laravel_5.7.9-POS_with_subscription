@extends('layouts/adminMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul, this page for edit subscription</h1>

  {{-- pada <form> kita wajib menyisipkan code enctype="multipart/form-data"
untuk upload file / gambar fungsi nya agar file yang kita upload itu dikenali,
ini wajib ya jangan sampe lupa --}}
<form class="" action="/admin/subscription/{{ $subscription->id }}" method="post">
  {{-- untuk mendeklarasikan bahwa ini menggunakn metode put di web.php nya Route::put --}}
  {{ method_field('PUT') }}

  {{-- kolom untuk isi tabel "name" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="name" value="{{ $subscription->name }}" placeholder="name"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('name'))
    <p>{{ $errors->first('name') }}</p>
  @endif

  {{-- kolom untuk isi tabel "price" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="price" value="{{ $subscription->price }}" placeholder="price ex:1000"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('price'))
    <p>{{ $errors->first('price') }}</p>
  @endif

  {{-- kolom untuk isi tabel "num_invoices" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="num_invoices" value="{{ $subscription->num_invoices }}" placeholder="jumlah invoice"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('num_invoices'))
    <p>{{ $errors->first('num_invoices') }}</p>
  @endif

  {{-- kolom untuk isi tabel "num_users" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="num_users" value="{{ $subscription->num_users }}" placeholder="jumlah user"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('num_users'))
    <p>{{ $errors->first('num_users') }}</p>
  @endif

  <input type="submit" name="submit" value="update">
  {{ csrf_field() }}
</form>
@endsection
