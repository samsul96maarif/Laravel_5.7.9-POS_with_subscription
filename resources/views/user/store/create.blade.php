@extends('layouts/userMaster')

@section('title', 'Create Store')

@section('headline', 'Company Profile')

@section('content')

  <form class="" action="/store" method="post" value="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-2">
          <img src="{{ asset('logo/anonim.png') }}" alt="Logo" class="img-thumbnail">
        </div>
        <div class="col-md-6 text-left">
          <p class="logo">This logo will appear on the documents such as sales order and purchase order that you created.</p>
          <p class="logo">Preferred Image Size: 240px x 240px @ 72 DPI Maximum size of 1MB. Supported types : .PNG, .JPG, .JPEG</p>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <input type="file" name="logo" value="{{ old('logo') }}"><br>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="name" value="{{ old('name') }}" placeholder="Store Name" class="form-control"><br><br>
          {{-- untuk mengeluarkan error pada value "name" --}}
          @if($errors->has('name'))
            <p>{{ $errors->first('name') }}</p>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone"><br><br>
          @if($errors->has('phone'))
            <p>{{ $errors->first('phone') }}</p>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <textarea class="form-control" name="company_address" rows="8" cols="80" placeholder="Company Address">{{ old('company_address') }}</textarea><br><br>
          @if($errors->has('company_address'))
            <p>{{ $errors->first('company_address') }}</p>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <input type="text" name="zipcode" value="{{ old('zipcode') }}" placeholder="zipcode"><br><br>
          @if($errors->has('zipcode'))
            <p>{{ $errors->first('zipcode') }}</p>
          @endif
        </div>
      </div>
      <div class="row">
        <div class="col">
          <input class="btn btn-primary" type="submit" name="submit" value="save">
        </div>
      </div>

    {{-- kolom untuk isi tabel "name" --}}
    {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
    yang nilainya akan terhapus --}}
    {{ csrf_field() }}
  </form>
@endsection
