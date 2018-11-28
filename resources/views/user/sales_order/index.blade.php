@extends('layouts/userMaster')

@section('title', 'Sales Order')

@section('headline', 'Sales Orders')

@section('content')

  @if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
  @endif

  <div class="row">
    <div class="col-md-4">
      <a href="/sales_order/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New</a>
    </div>
    <div class="col-md-3 offset-md-5">
      <form class="" action="/sales_order/search" method="get">
        <div class="input-group mb-3">
          <input autocomplete="off" type="search" name="q" class="form-control" placeholder="Search" aria-describedby="button-addon2" value="">
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-primary" type="submit" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th>Date</th>
      <th>Order#</th>
      <th>Customer</th>
      <th>Total</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
  @foreach ($salesOrders as $salesOrder)
    <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ date('d-m-Y', strtotime($salesOrder->created_at)) }}</td>
        <td> <a class="btn" href="/sales_order/{{ $salesOrder->id }}/detail">{{ $salesOrder->order_number }}</a></td>
        @foreach ($invoices as $invoice)
          @if ($invoice->sales_order_id == $salesOrder->id)
            @foreach ($contacts as $contact)
              @if ($invoice->contact_id == $contact->id)
                <td>{{ $contact->name }}</td>
              @endif
            @endforeach
          @endif
        @endforeach
        <td>Rp.{{ number_format($salesOrder->total,2,",",".") }}</td>
        <td>
          <form class="" action="/sales_order/{{ $salesOrder->id }}/delete" method="post">
            {{ method_field('DELETE') }}
            <button onclick="return confirm('Do you Wanna Delete {{ $salesOrder->order_number}}')" type="submit" name="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
            {{-- <input onclick="return confirm('Do you Wanna Delete {{ $salesOrder->order_number}}')" type="submit" name="submit" value="delete" class="btn btn-outline-danger"> --}}
            {{ csrf_field() }}
          </form>
        </td>
        @php
          $i++;
        @endphp
    </tr>
  @endforeach
</tbody>
</table>

@endsection
