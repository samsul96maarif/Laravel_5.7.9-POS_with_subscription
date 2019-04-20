@extends('layouts/adminMaster')

@section('title', 'Company '.$organization->name)

@section('headline', $organization->name.' Detail')

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <h4 class="my-0 font-weight-normal">{{ $organization->name }}</h4>
        </div>
        <div class="card-body">

          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Owner</label>
            </div>
            <div class="col">
              <a class="col btn btn-outline-link" href="/admin/user/{{ $organization->user_id }}">{{ $user->name }}</a>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Package Subscription</label>
            </div>
            @if ($organization->subscription_id == null)
              <div class="col">
                <span class="form-control text-center">Dont Have Yet</span>
              </div>
            @else
              <div class="col">
                <a class="col btn btn-outline-link" href="/admin/subscription/{{ $subscription->id }}">{{ $subscription->name }}</a></td>
              </div>
            @endif
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Phone</label>
            </div>
            <div class="col">
              <span class="form-control text-center">{{ $organization->phone }}</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Comapany Address</label>
            </div>
            <div class="col">
              <textarea name="name" class="form-control" rows="8" cols="80">{{ $organization->company_address}}</textarea>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">ZipCode</label>
            </div>
            <div class="col">
              <span class="form-control text-center">{{ $organization->zipcode}}</span>
            </div>
          </div>
          <br>
          @if ($organization->subscription_id == null)
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Invoices / Invoices Quota</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numSalesOrders }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Employees / Employees Quota</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numUsers }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Items / Items Quota</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numItems }}</span>
              </div>
            </div>
            <br>
          @else
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Invoices / Invoices Quota</label>
              </div>
              <div class="col">
                @if ($subscription->num_invoices === null)
                  <span class="form-control text-center">{{ $numSalesOrders }}</span>
                  {{-- <span class="form-control text-center"><i class="fas fa-infinity"></i></span> --}}
                @else
                  <span class="form-control text-center">{{ $numSalesOrders }} / {{ $subscription->num_invoices }}</span>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Employees / Employees Quota</label>
              </div>
              <div class="col">
                @if ($subscription->num_users === null)
                  <span class="form-control text-center">{{ $numUsers}}</span>
                  {{-- <span class="form-control text-center"><i class="fas fa-infinity"></i></span> --}}
                @else
                  <span class="form-control text-center">{{ $numUsers }} / {{ $subscription->num_users}}</span>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Items / Items Quota</label>
              </div>
              <div class="col">
                @if ($subscription->num_items == null)
                  <span class="form-control text-center">{{ $numItems }}</span>
                  {{-- <span class="form-control text-center"><i class="fas fa-infinity"></i></span> --}}
                @else
                  <span class="form-control text-center">{{ $numItems }} / {{ $subscription->num_items }}</span>
                @endif
              </div>
            </div>
            <br>
          @endif

          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Status</label>
            </div>
            <div class="col">
              @if ($organization->status > 0)
                <span class="form-control text-center">Active</span>
              @elseif ($organization->status == 0)
                <span class="form-control text-center">Awaiting Paymnet</span>
              @else
                <span class="form-control text-center">Not Subscribe</span>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Expiry Date</label>
            </div>
            @if ($organization->expire_date == null)
              <div class="col">
                <span class="form-control text-center"></span>
              </div>
            @else
              <div class="col">
                <span class="form-control text-center">{{ date('d-m-Y', strtotime($organization->expire_date)) }}</span>
              </div>
            @endif
          </div>
          <br>
          {{-- tombol ini disembunyikan fungsi dialihkan ke page payment --}}
          {{-- <div class="row">
            @if ($organization->status > 0)
              <div class="col text-right">
                <form class="" action="/admin/organization/{{ $organization->id }}/extend" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="expire_date" value="30" hidden>
                  <input class="btn btn-primary" type="submit" name="submit" value="extend period">
                  {{ csrf_field() }}
                </form>
              </div>
              <div class="col text-left">
                <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="0" hidden>
                  <input class="btn btn-danger" type="submit" name="submit" value="deactivate">
                  {{ csrf_field() }}
                </form>
              </div>
            @elseif ($organization->status == 0)
              <div class="col text-center">
                <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input class="btn btn-primary" type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              </div>
            @else

            @endif
          </div> --}}

        </div>
      </div>
    </div>
  </div>

@endsection
