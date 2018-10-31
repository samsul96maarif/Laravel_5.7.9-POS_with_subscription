@extends('layouts/userMaster')

@section('title', 'Report')

@section('headline', 'Report By Customers')

@section('content')

  <table>
    <th>No</th>
    <th>Customer</th>
    <th>Amount</th>
    @php
      $i = 1;
      $total = 0;
    @endphp
  @foreach ($salesOrders as $salesOrder)
    <tr>
        <td>{{ $i }}</td>
        @foreach ($invoices as $invoice)
          @if ($invoice->sales_order_id == $salesOrder->id)
            @foreach ($contacts as $contact)
              @if ($invoice->contact_id == $contact->id)
                <td>{{ $contact->name }}</td>
              @endif
            @endforeach
          @endif
        @endforeach
        <td>{{ $salesOrder->total }}</td>
        @php
          $total = $salesOrder->total + $total;
          $i++;
        @endphp
    </tr>
  @endforeach
  <tr>
    <td>Total</td>
    <td>{{ $total }}</td>
  </tr>
  </table>
  <form class="" action="/sales_order/create" method="get">
    <input type="submit" name="submit" value="tambah sales order">
  </form>

@endsection
