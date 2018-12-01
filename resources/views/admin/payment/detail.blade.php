@extends('layouts/adminMaster')

@section('title', 'Company '.$organization->name)

@section('headline', $organization->name.' Detail')

@section('content')

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="my-0 font-weight-normal">{{ $organization->name }}</h4>
        </div>
        <div class="card-body">

          <div class="row justify-content-center">
            <div class="col">
              <img src="{{ asset('proof/'.$payment->proof) }}" alt="payment proof" class="rounded mx-auto d-block">
            </div>
          </div>
          <br>
          <div class="row justify-content-center">
            <div class="col-md-4">
              <label for="" class="col-form-label">Amount</label>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="Rp.{{ number_format($payment->amount,2,",",".") }}" readonly>
            </div>
          </div>
          <br>
          <div class="row justify-content-center btn-atas">
            <div class="col-md-4">
              <label for="" class="col-form-label">Unique Code</label>
            </div>
            <div class="col-md-6">
              <input class="form-control" type="text" name="name" value="{{ $payment->uniq_code }}" readonly>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-4">
              <label for="" class="col-form-label">Company Name</label>
            </div>
            <div class="col-md-6">
              <a onclick="return confirm('Do you wanna leave this page')" class="btn btn-lg" href="/admin/organization/{{ $organization->id }}">{{ $organization->name }}</a>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-md-4">
              <label for="" class="col-form-label">Package Subscription</label>
            </div>
            <div class="col-md-6">
              <a onclick="return confirm('Do you wanna leave this page')" class="btn btn-lg" href="/admin/subscription/{{ $subscription->id }}">{{ $subscription->name }}</a>
            </div>
          </div>
          <br>
          <div class="row">
            @if ($organization->status > 0)
              <div class="col text-center">
                <form class="" action="/admin/organization/{{ $organization->id }}/extend" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="period" value="{{ $payment->period }}" hidden>
                  <input onclick="return confirm('Do You Wanna Extend Package {{ $subscription->name }} For {{ $organization->name }}')" class="btn btn-primary" type="submit" name="submit" value="Extend Period">
                  {{ csrf_field() }}
                </form>
              </div>
            @else
              <div class="col text-center">
                <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input onclick="return confirm('Do You Wanna Activate Package {{ $subscription->name }} For {{ $organization->name }}')" class="btn btn-primary" type="submit" name="submit" value="Activate">
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
