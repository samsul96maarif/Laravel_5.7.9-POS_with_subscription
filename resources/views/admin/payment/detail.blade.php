@extends('layouts/adminMaster')

@section('title', 'store '.$store->name)

@section('headline', $store->name.' Detail')

@section('content')

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="my-0 font-weight-normal">{{ $store->name }}</h4>
        </div>
        <div class="card-body">

          <div class="row">
            <div class="col-md-12">
              <img src="{{ asset('proof/'.$payment->proof) }}" alt="payment proof" class="img-thumbnail">
            </div>
          </div>
          <div class="row">
            <div class="col-md-2">
              <p>Unique Code</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="{{ $payment->uniq_code }}" readonly>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Store Name</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="{{ $store->name }}" readonly>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-2">
              <p>Package Subscription</p>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="{{ $subscription->name }}" readonly>
            </div>
          </div>
          <br>
          <div class="row">
            @if ($store->status > 0)
              <div class="col text-right">
                <form class="" action="/admin/store/{{ $store->id }}/extend" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="expire_date" value="30" hidden>
                  <input class="btn btn-primary" type="submit" name="submit" value="extend period">
                  {{ csrf_field() }}
                </form>
              </div>
            @else
              <div class="col text-center">
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input class="btn btn-primary" type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
