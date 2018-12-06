@extends('layouts/adminMaster')

@section('title', 'Create Subscription')

@section('headline', 'Create Subscription')

@section('content')

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

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  <div class="row justify-content-center">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <form class="" action="/admin/subscription" method="post" value="post">

            {{-- kolom untuk isi tabel "name" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Package Name">
            {{-- untuk mengeluarkan error pada value "name" --}}
            @if($errors->has('name'))
              <p>{{ $errors->first('name') }}</p>
            @endif
            <br>
            {{-- kolom untuk isi tabel "price" --}}
            {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
            yang nilainya akan terhapus --}}
            <input class="form-control" type="text" onkeyup="splitInDots(this)" name="price" value="{{ old('price') }}" placeholder="Price ex:1000">
            {{-- untuk mengeluarkan error pada value "name" --}}
            @if($errors->has('price'))
              <p>{{ $errors->first('price') }}</p>
            @endif
            <br>
            <div class="row">
              <div class="col-md-10">
                {{-- kolom untuk isi tabel "num_users" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" name="num_items" onkeyup="splitInDots(this)" value="{{ old('num_items') }}" placeholder="Items Quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_items'))
                  <p>{{ $errors->first('num_items') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-10">
                {{-- kolom untuk isi tabel "num_invoices" --}}
                {{-- old('nama variable') = untuk menyimpan nilai lama, jadi bila tidak valid hanya tabel yang tidak valid
                yang nilainya akan terhapus --}}
                <input class="form-control" type="text" onkeyup="splitInDots(this)" name="num_invoices" value="{{ old('num_invoices') }}" placeholder="Invoices Quota">
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
                <input class="form-control" type="text" name="num_users" onkeyup="splitInDots(this)" value="{{ old('num_users') }}" placeholder="Employees Quota">
                {{-- untuk mengeluarkan error pada value "name" --}}
                @if($errors->has('num_users'))
                  <p>{{ $errors->first('num_users') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="Save">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
