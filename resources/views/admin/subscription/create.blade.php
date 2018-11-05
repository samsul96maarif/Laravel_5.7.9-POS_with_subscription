@extends('layouts/adminMaster')

@section('title', 'Create Subscription')

@section('headline', 'Create Subscription')

@section('content')

  <div class="row justify-content-center">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <form class="" action="/admin/subscription" method="post" value="post">

            {{-- kolom untuk isi tabel "name" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Package Name Packet">
            {{-- untuk mengeluarkan error pada value "name" --}}
            @if($errors->has('name'))
              <p>{{ $errors->first('name') }}</p>
            @endif
            <br>
            {{-- kolom untuk isi tabel "price" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <input class="form-control" type="number" name="price" value="{{ old('price') }}" placeholder="Price ex:1000">
            {{-- untuk mengeluarkan error pada value "name" --}}
            @if($errors->has('price'))
              <p>{{ $errors->first('price') }}</p>
            @endif
            <br>
            <div class="row">
              <div class="col-md-10">
                {{-- kolom untuk isi tabel "num_invoices" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="number" name="num_invoices" value="{{ old('num_invoices') }}" placeholder="Invoices quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_invoices'))
                  <p>{{ $errors->first('num_invoices') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-10">
                {{-- kolom untuk isi tabel "num_users" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="number" name="num_users" value="{{ old('num_users') }}" placeholder="Contacts quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_users'))
                  <p>{{ $errors->first('num_users') }}</p>
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
