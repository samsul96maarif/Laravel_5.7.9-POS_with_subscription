@extends('layouts/userMaster')

@section('title', 'Edit Item')

@section('headline', 'Edit '.$item->name)

@section('content')

  <form class="" action="/item/{{ $item->id }}" method="post">
    {{-- untuk mendeklarasikan bahwa ini menggunakn metode put di web.php nya Route::put --}}
    {{ method_field('PUT') }}

    {{-- kolom untuk isi tabel "name" --}}
    {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
    yang nilainya akan terhapus --}}
    <input type="text" name="name" value="{{ $item->name }}" placeholder="name"><br><br>
    {{-- untuk mengeluarkan error pada value "name" --}}
    @if($errors->has('name'))
      <p>{{ $errors->first('name') }}</p>
    @endif

    <textarea name="description" rows="8" cols="80" placeholder="description">{{ $item->description }}</textarea>
    <br><br>
    @if($errors->has('description'))
      <p>{{ $errors->first('description') }}</p>
    @endif

    <input type="number" name="price" value="{{ $item->price }}" placeholder="price ex:1000" min="1"><br><br>
    @if($errors->has('price'))
      <p>{{ $errors->first('price') }}</p>
    @endif

    <input type="number" name="stock" value="{{ $item->stock }}" placeholder="stock ex:15" min="1"><br><br>
    @if($errors->has('stock'))
      <p>{{ $errors->first('stock') }}</p>
    @endif

    <input type="submit" name="submit" value="update">
    {{ csrf_field() }}
  </form>
@endsection
