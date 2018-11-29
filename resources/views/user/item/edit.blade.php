@extends('layouts/userMaster')

@section('title', 'Edit Item')

@section('headline', 'Edit '.$item->name)

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

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <form class="" action="/item/{{ $item->id }}" method="post" enctype="multipart/form-data">
            {{-- untuk mendeklarasikan bahwa ini menggunakn metode put di web.php nya Route::put --}}
            {{ method_field('PUT') }}
            <div class="row">
              @foreach ($itemMedias as $itemMedia)
                <div class="col-md-4">
                  <img src="{{ asset('img/'.$itemMedia->image) }}" alt="Image" class="img-thumbnail">
                </div>
              @endforeach
            </div>
            <div class="row">
              <div class="col">
                <input class="form-control-file" type="file" name="image" value="{{ old('image') }}">
                <label style="color:rgb(73, 80, 87);font-size:16px;" for="">Image</label>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3">
                <p>Item Name</p>
              </div>
              <div class="col">
                <input class="form-control" type="text" name="name" value="{{ $item->name }}" placeholder="name">
                @if($errors->has('name'))
                  <p>{{ $errors->first('name') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3">
                <p>Description</p>
              </div>
              <div class="col">
                <textarea class="form-control" name="description" rows="8" cols="80" placeholder="description">{{ $item->description }}</textarea>
                @if($errors->has('description'))
                  <p>{{ $errors->first('description') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3">
                <p>Price</p>
              </div>
              <div class="col-md-6">
                <input class="form-control" type="text" onkeyup="splitInDots(this)" name="price" value="{{ number_format($item->price, 0, ",", ".") }}" placeholder="price ex:1000" min="1">
                @if($errors->has('price'))
                  <p>{{ $errors->first('price') }}</p>
                @endif
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-3">
                <p>Stock</p>
              </div>
              <div class="col-md-3">
                <input class="form-control"type="text" onkeyup="splitInDots(this)" name="stock" value="{{ number_format($item->stock, 0, ",", ".") }}" placeholder="stock ex:15" min="1">
                @if($errors->has('stock'))
                  <p>{{ $errors->first('stock') }}</p>
                @endif
              </div>
            </div>
            <br>
            <br>
            <div class="row">
              <div class="col">
                <input class="btn btn-primary" type="submit" name="submit" value="update">
              </div>
            </div>
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
