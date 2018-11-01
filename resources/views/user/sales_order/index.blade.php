@extends('layouts/userMaster')

@section('title', 'Sales Order')

@section('headline', 'Sales Orders')

@section('content')

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
          <form class="" action="/sales_order/{{ $salesOrder->id }}/edit" method="edit">
            <input type="submit" name="submit" value="edit">
          </form>
          <form class="" action="/sales_order/{{ $salesOrder->id }}/delete" method="post">
            {{ method_field('DELETE') }}
            <input type="submit" name="submit" value="delete">
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

  </table>
  <form class="" action="/sales_order/create" method="get">
    <input type="submit" name="submit" value="tambah sales order">
  </form>

@endsection
