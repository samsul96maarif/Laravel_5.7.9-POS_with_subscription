@extends('layouts/adminMaster')

@section('title', 'Edit Subscription')

@section('headline', 'Edit Subscription')

@section('content')

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="col text-center">
            <h4>{{ $subscription->name }}</h4>
          </div>
        </div>
        <div class="card-body">
          <form class="" action="/admin/subscription/{{ $subscription->id }}" method="post">
            {{-- untuk mendeklarasikan bahwa ini menggunakn metode put di web.php nya Route::put --}}
            {{ method_field('PUT') }}
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Package Name</label>
              </div>
              <div class="col">
                {{-- kolom untuk isi tabel "name" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" name="name" value="{{ $subscription->name }}" placeholder="Package Name">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('name'))
                  <p>{{ $errors->first('name') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Price</label>
              </div>
              <div class="col">
                {{-- kolom untuk isi tabel "price" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="number" name="price" value="{{ $subscription->price }}" placeholder="price ex:1000">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('price'))
                  <p>{{ $errors->first('price') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <label for=""class="col-form-label">Invoices Quota</label>
              </div>
              <div class="col-md-3">
                {{-- kolom untuk isi tabel "num_invoices" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" name="num_invoices" value="{{ $subscription->num_invoices }}" placeholder="Invoices Quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_invoices'))
                  <p>{{ $errors->first('num_invoices') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Contacts Quota</label>
              </div>
              <div class="col-md-3">
                {{-- kolom untuk isi tabel "num_users" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" name="num_users" value="{{ $subscription->num_users }}" placeholder="Contacts Quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_users'))
                  <p>{{ $errors->first('num_users') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row content-center">
              <div class="col text-center">
                <input onclick="return confirm('Do You Wanna Update Package {{ $subscription->name}}')" class="btn btn-primary" type="submit" name="submit" value="Update">
              </div>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
