@extends('layouts/userMaster')

@section('title', 'Edit Item')

@section('headline', 'Edit '.$item->name)

@section('content')

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
                <input class="form-control" type="number" name="price" value="{{ $item->price }}" placeholder="price ex:1000" min="1">
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
                <input class="form-control"type="number" name="stock" value="{{ $item->stock }}" placeholder="stock ex:15" min="1">
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
