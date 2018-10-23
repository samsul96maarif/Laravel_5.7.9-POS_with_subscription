@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul, this page for edit subscription</h1>

  {{-- pada <form> kita wajib menyisipkan code enctype="multipart/form-data"
untuk upload file / gambar fungsi nya agar file yang kita upload itu dikenali,
ini wajib ya jangan sampe lupa --}}
<form class="" action="/contact/{{ $contact->id }}" method="post">
  {{-- untuk mendeklarasikan bahwa ini menggunakn metode put di web.php nya Route::put --}}
  {{ method_field('PUT') }}

  {{-- kolom untuk isi tabel "name" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="name" value="{{ $contact->name }}" placeholder="name"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('name'))
    <p>{{ $errors->first('name') }}</p>
  @endif

  <input type="text" name="phone" value="{{ $contact->phone }}" placeholder="Phone"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('phone'))
    <p>{{ $errors->first('phone') }}</p>
  @endif

  {{-- kolom untuk isi tabel "price" --}}
  {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
  yang nilainya akan terhapus --}}
  <input type="text" name="company_name" value="{{ $contact->company_name }}" placeholder="Company Name"><br><br>
  {{-- untuk mengeluarkan error pada value "name" --}}
  @if($errors->has('company_name'))
    <p>{{ $errors->first('company_name') }}</p>
  @endif

  <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $contact->email }}" required placeholder="Email">

  @if ($errors->has('email'))
      <span class="invalid-feedback" role="alert">
          <strong>{{ $errors->first('email') }}</strong>
      </span>
  @endif

  <input type="submit" name="submit" value="update">
  {{ csrf_field() }}
</form>
@endsection
