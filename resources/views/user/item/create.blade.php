@extends('layouts/userMaster')

@section('title', 'Create Item')

@section('headline', 'Create Item')

@section('content')

  @if (session()->has('success'))
    <div class="alert alert-info">{{ session('success') }}</div>
  @endif

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form class="" action="/item" method="post" value="post" enctype="multipart/form-data">

            {{-- kolom untuk isi tabel "name" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <div class="row">
              <div class="col">
                <input class="form-control-file" type="file" name="image" value="{{ old('logo') }}">
                <label style="color:rgb(73, 80, 87);font-size:16px;" for="">Image</label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('name'))
                  <p>{{ $errors->first('name') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col">
                <textarea class="form-control" name="description" rows="8" cols="80" placeholder="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                  <p>{{ $errors->first('description') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-2 btn-kiri">
                <input class="form-control" type="text" name="" value="Rp." readonly>
              </div>
              {{-- <label for="price" class=" col-form-label text-md-right">Rp.</label> --}}
              <div class="col-md-6 btn-kanan">
                <input class="form-control" type="number" name="price" value="{{ old('price') }}" placeholder="price ex:1000" min="1">
                @if($errors->has('price'))
                  <p>{{ $errors->first('price') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <input class="form-control" type="number" name="stock" value="{{ old('stock') }}" placeholder="stock ex:15" min="1">
                @if($errors->has('stock'))
                  <p>{{ $errors->first('stock') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="save">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
