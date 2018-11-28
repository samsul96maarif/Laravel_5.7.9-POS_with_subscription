@extends('layouts/userMaster')

@section('title', 'home')

@section('headline', 'Company Profile')

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

    <form class="" action="/store/{{ $store->id }}" method="post" value="post" enctype="multipart/form-data">
      {{ method_field('PUT') }}
          <div class="row">
            <div class="col-md-2">
              <img src="{{ asset('logo/'.$store->logo) }}" alt="Logo" class="img-thumbnail">
            </div>
            <div class="col-md-6 text-left">
              <p class="logo">This logo will appear on the documents such as sales order and purchase order that you created.</p>
              <p class="logo">Preferred Image Size: 240px x 240px @ 72 DPI Maximum size of 1MB. Supported types : .PNG, .JPG, .JPEG</p>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <input type="file" name="logo" value="">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Company Name</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="{{ $store->name }}" placeholder="Company Name">
              {{-- untuk mengeluarkan error pada value "name" --}}
              @if($errors->has('name'))
                <p>{{ $errors->first('name') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Package Subscription</p>
            </div>
            <div class="col-md-6">
              @if ($store->subscription_id == null)
                <input class="form-control" type="text" name="status" value="dont have" readonly>
              @else
                @if ($store->status == 1)
                  <input class="form-control" type="text" name="status" value="{{ $subscription->name }}" readonly>
                @else
                  <input class="form-control" type="text" name="status" value="Awaiting Payment For '{{ $subscription->name }}'" readonly>
                @endif
              @endif
            </div>
          </div>
          <br>
          @if ($store->subscription_id != null)
            <div class="row">
              <div class="col-md-2">
                <p>Expire Date</p>
              </div>
              <div class="col-md-6">
                <input class="form-control" type="text" name="expire_date" value="{{ date('d-m-Y', strtotime($store->expire_date)) }}" readonly>
              </div>
            </div>
            <br>
          @endif
          <div class="row">
            <div class="col-md-2">
              <p>Phone</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="tel" name="phone" value="{{ $store->phone }}" placeholder="Phone">
              @if($errors->has('phone'))
                <p>{{ $errors->first('phone') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Address</p>
            </div>
            <div class="col-md-6">
              <textarea class="form-control" name="company_address" rows="8" cols="80" placeholder="Company Address">{{ $store->company_address }}</textarea>
              @if($errors->has('company_address'))
                <p>{{ $errors->first('company_address') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>ZipCode</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="number" name="zipcode" value="{{ $store->zipcode }}" placeholder="zipcode">
              @if($errors->has('zipcode'))
                <p>{{ $errors->first('zipcode') }}</p>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col">
              <input type="submit" name="submit" value="Update" class="btn btn-primary">
            </div>
          </div>
          {{ csrf_field() }}
    </form>
    {{-- unutk delere --}}
      {{-- </form><br>
      <form class="" action="/store/{{ $store->id }}/delete" method="post">
        {{ method_field('DELETE') }}
        <input type="submit" name="submit" value="delete">
        {{ csrf_field() }}
      </form> --}}
@endsection
