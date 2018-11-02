@extends('layouts/adminMaster')

@section('title', 'store '.$store->name)

@section('headline', $store->name.' Detail')

@section('content')

  <div class="col justify-content-center">
    <div class="card">
      <div class="card-header text-center">
        <h4 class="my-0 font-weight-normal">{{ $store->name }}</h4>
      </div>
      <div class="card-body">

        <div class="row">
          <div class="col">
            <label for="" class="col-form-label">Owner</label>
          </div>
          <div class="col">
            <td><a href="/admin/user/{{ $store->user_id }}">{{ $user->name }}</a></td>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <label for="" class="col-form-label">Subscription</label>
          </div>
          @if ($store->subscription_id == null)
            <div class="col">
              <td>Dont have yet</td>
            </div>
          @endif
          <div class="col">
            <td><a href="/admin/subscription/{{ $subscription->id }}">{{ $subscription->name }}</a></td>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <label for="" class="col-form-label">Phone</label>
          </div>
          <div class="col">
            <td>{{ $store->phone }}</td>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <label for="" class="col-form-label">Comapany Address</label>
          </div>
          <div class="col">
            <td>{{ $store->company_address}}</td>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <label for="" class="col-form-label">Zipcode</label>
          </div>
          <div class="col">
            <td>{{ $store->zipcode}}</td>
          </div>
        </div>
        @if ($store->subscription_id == null)

        @else
          @if ($store->status == 0)
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Num Invoices</label>
              </div>
              <div class="col">
                <td></td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Num Customers</label>
              </div>
              <div class="col">
                <td></td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Num Items</label>
              </div>
              <div class="col">
                <td></td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Status</label>
              </div>
              <div class="col">
                <td>awaiting paymnet</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Expiry Date</label>
              </div>
              <div class="col">
                <td>{{ $store->expire_date }}</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Action</label>
              </div>
              <div class="col">
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              </div>
            </div>
          @else
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Num Invoices</label>
              </div>
              <div class="col">
                <td>{{ $subscription->num_invoices }} / {{ $numSalesOrders }}</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Num Customers</label>
              </div>
              <div class="col">
                <td>{{ $subscription->num_users}} / {{ $numContacts}}</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Num Items</label>
              </div>
              <div class="col">
                <td>{{ $numItems }}</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Status</label>
              </div>
              <div class="col">
                <td>active</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Expiry Date</label>
              </div>
              <div class="col">
                <td>{{ $store->expire_date }}</td>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label for="" class="col-form-label">Action</label>
              </div>
              <div class="col">
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              </div>
            </div>
          @endif

        @endif

      </div>
    </div>
  </div>

  <table>
    <th>Owner</th>
    <th>Subscription</th>
    <th>Name</th>
    <th>Phone</th>
    <th>Company Address</th>
    <th>Zipcode</th>
    <th>Num Invoice</th>
    <th>Num Customer</th>
    <th>Num Items</th>
    <th>Status</th>
    <th>Expiry Date</th>
    <th>Action</th>
      <tr>
        @foreach ($users as $user)
          @if ($store->user_id == $user->id)
            <td><a href="/admin/user/{{ $store->user_id }}">{{ $user->name }}</a></td>
          @endif
        @endforeach
        @foreach ($subscriptions as $subscription)
          @if ($store->subscription_id == $subscription->id)
            <td><a href="/admin/subscription/{{ $store->subscription_id }}">{{ $subscription->name }}</a></td>
            @break($store->subscription_id == $subscription->id)
          @endif
        @endforeach
        @if ($store->subscription_id == null)
          <td>Dont have yet</td>
        @endif
        <td>{{ $store->name }}</td>
        <td>{{ $store->phone }}</td>
        <td>{{ $store->company_address}}</td>
        <td>{{ $store->zipcode}}</td>
        @if ($store->subscription_id > 0)
          @if ($store->status == 0)
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>awaiting paymnet</td>
          @else
            <td>{{ $subscription->num_invoices }} / {{ $numSalesOrders }}</td>
            <td>{{ $subscription->num_users}} / {{ $numContacts}}</td>
            <td>{{ $numItems }}</td>
            <td>active</td>
          @endif
          <td>{{ $store->expire_date }}</td>
          <td>
              @if ($store->status == 0)
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="1" hidden>
                  <input type="submit" name="submit" value="activate">
                  {{ csrf_field() }}
                </form>
              @else
                <form class="" action="/admin/store/{{ $store->id }}" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="status" value="0" hidden>
                  <input type="submit" name="submit" value="deactivate">
                  {{ csrf_field() }}
                </form>
                <form class="" action="/admin/store/{{ $store->id }}/extend" method="post">
                  {{ method_field('PUT') }}
                  <input type="text" name="expire_date" value="30" hidden>
                  <input type="submit" name="submit" value="extend period">
                  {{ csrf_field() }}
                </form>
              @endif
          </td>
        @else
          <td>not subscribe</td>
          <td></td>
          <td></td>
        @endif
      </tr>
  </table>

@endsection
