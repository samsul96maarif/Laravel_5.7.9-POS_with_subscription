@extends('layouts/userMaster')

@section('title', 'Create Item')

@section('headline', 'Upload a Payment Proof')

@section('content')

  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <form class="" action="/subscription/{{ $id }}/buy/proof" method="post" value="post" enctype="multipart/form-data">
            <div class="row">
              <div class="col">
                <input class="form-control-file" type="file" name="proof" value="{{ old('proof') }}">
                @if($errors->has('proof'))
                  <p>{{ $errors->first('proof') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="upload">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
