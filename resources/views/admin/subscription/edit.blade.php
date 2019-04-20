@extends('layouts/adminMaster')

@section('title', 'Edit Package '.$subscription->name)

@section('headline', 'Edit Package '.$subscription->name)

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <script type="text/javascript">
  // menambahkan titik otomatis
  // http://plnkr.co/edit/rsTCO8hmZUSEtrvpuWE6?p=preview
    function reverseNumber(input) {
     return [].map.call(input, function(x) {
        return x;
      }).reverse().join('');
    }

    function plainNumber(number) {
       return number.split('.').join('');
    }

    function splitInDots(input) {

      var value = input.value,
          plain = plainNumber(value),
          reversed = reverseNumber(plain),
          reversedWithDots = reversed.match(/.{1,3}/g).join('.'),
          normal = reverseNumber(reversedWithDots);

      console.log(plain,reversed, reversedWithDots, normal);
      input.value = normal;
    }

    function oneDot(input) {
      var value = input.value,
          value = plainNumber(value);

      if (value.length > 3) {
        value = value.substring(0, value.length - 3) + '.' + value.substring(value.length - 3, value.length);
      }
      console.log(value);
      input.value = value;
    }
    // menambahkan titik otomatis
  </script>

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
                <input class="form-control" type="text" onkeyup="splitInDots(this)" name="price" value="{{ number_format($subscription->price, 0, ",", ".") }}" placeholder="price ex:1000">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('price'))
                  <p>{{ $errors->first('price') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Items Quota</label>
              </div>
              <div class="col-md-3">
                {{-- kolom untuk isi tabel "num_users" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" name="num_items" onkeyup="splitInDots(this)" value="{{ number_format($subscription->num_items, 0, ",", ".") }}" placeholder="Items Quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_items'))
                  <p>{{ $errors->first('num_items') }}</p>
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
                <input class="form-control" type="text" onkeyup="splitInDots(this)" name="num_invoices" value="{{ number_format($subscription->num_invoices, 0, ",", ".") }}" placeholder="Invoices Quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_invoices'))
                  <p>{{ $errors->first('num_invoices') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Employees Quota</label>
              </div>
              <div class="col-md-3">
                {{-- kolom untuk isi tabel "num_users" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" onkeyup="splitInDots(this)" name="num_users" value="{{ number_format($subscription->num_users, 0, ",", ".") }}" placeholder="Contacts Quota">
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
