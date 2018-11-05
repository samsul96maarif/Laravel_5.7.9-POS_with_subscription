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
            <div class="col-md-4">
              <label for="" class="col-form-label">Owner</label>
            </div>
            <div class="col">
              <a class="col btn btn-outline-link" href="/admin/user/{{ $store->user_id }}">{{ $user->name }}</a>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Subscription</label>
            </div>
            @if ($store->subscription_id == null)
              <div class="col">
                <span class="form-control text-center">Dont have yet</span>
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
              <span class="form-control text-center">{{ $store->phone }}</span>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Comapany Address</label>
            </div>
            <div class="col">
              <textarea name="name" class="form-control" rows="8" cols="80">{{ $store->company_address}}</textarea>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Zipcode</label>
            </div>
            <div class="col">
              <span class="form-control text-center">{{ $store->zipcode}}</span>
            </div>
          </div>
          <br>
          @if ($store->subscription_id == null)
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Invoices quota / Invoices</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numSalesOrders }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Contacts quota / Contacts</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numContacts }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Items</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numItems }}</span>
              </div>
            </div>
            <br>
          @else
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Invoices quota / Invoices</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $subscription->num_invoices }} / {{ $numSalesOrders }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Contacts quota / Contacts</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $subscription->num_users}} / {{ $numContacts }}</span>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="" class="col-form-label">Items</label>
              </div>
              <div class="col">
                <span class="form-control text-center">{{ $numItems }}</span>
              </div>
            </div>
            <br>
          @endif

          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Status</label>
            </div>
            <div class="col">
              @if ($store->status > 0)
                <span class="form-control text-center">active</span>
              @elseif ($store->status == 0)
                <span class="form-control text-center">awaiting paymnet</span>
              @else
                <span class="form-control text-center">not subscribe</span>
              @endif
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-4">
              <label for="" class="col-form-label">Expiry Date</label>
            </div>
            @if ($store->expire_date == null)
              <div class="col">
                <span class="form-control text-center"></span>
              </div>
            @else
              <div class="col">
                <span class="form-control text-center">{{ $store->expire_date }}</span>
              </div>
            @endif
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
              <div class="col text-left">
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="0" hidden>
                  <input class="btn btn-danger" type="submit" name="submit" value="deactivate">
                  {{ csrf_field() }}
                </form>
              </div>
            @elseif ($store->status == 0)
              <div class="col text-center">
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input class="btn btn-primary" type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              </div>
            @else

            @endif
          </div>

        </div>
      </div>
    </div>
  </div>

@endsection
