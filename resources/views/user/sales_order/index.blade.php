@extends('layouts/userMaster')

@section('title', 'Sales Order')

@section('headline', 'Sales Orders')

@section('content')

  <div class="col-md-4 offset-md-8">
    <form class="" action="/sales_order/search" method="get">
      <div class="input-group mb-3">
        <input type="search" name="q" class="form-control" placeholder="Search" aria-describedby="button-addon2" value="">
        <div class="input-group-append">
          <input id="button-addon2" class="btn btn-primary" type="submit" name="submit" value="Search">
        </div>
        {{-- {{ csrf_field() }} --}}
      </div>
    </form>
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
        <td>{{ $salesOrder->created_at }}</td>
        <td> <a href="/sales_order/{{ $salesOrder->id }}">{{ $salesOrder->order_number }}</a></td>
        @foreach ($invoices as $invoice)
          @if ($invoice->sales_order_id == $salesOrder->id)
            @foreach ($contacts as $contact)
              @if ($invoice->contact_id == $contact->id)
                <td>{{ $contact->name }}</td>
              @endif
            @endforeach
          @endif
        @endforeach
        <td>Rp.{{ $salesOrder->total }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <form class="" action="/sales_order/{{ $salesOrder->id }}" method="get">
                <input type="submit" name="submit" value="edit" class="btn btn-outline-primary">
              </form>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/sales_order/{{ $salesOrder->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <input type="submit" name="submit" value="delete" class="btn btn-outline-danger">
                {{ csrf_field() }}
              </form>
            </div>
          </div>
        </td>
        @php
          $i++;
        @endphp
    </tr>
  @endforeach
</tbody>
</table>

  </table>
  <form class="" action="/sales_order/create" method="get">
    <input type="submit" name="submit" value="add sales order" class="btn btn-primary">
  </form>

@endsection
