@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul</h1>

  <table>
    <th>No</th>
    <th>Date</th>
    <th>Order#</th>
    <th>Customer</th>
    <th>Total</th>
    @php
      $i = 1;
    @endphp
  @foreach ($salesOrders as $salesOrder)
    <tr>
        <td>{{ $i }}</td>
        <td>{{ $salesOrder->created_at }}</td>
        <td>{{ $salesOrder->order_number }}</td>
        @foreach ($invoices as $invoice)
          @if ($invoice->sales_order_id == $salesOrder->id)
            @foreach ($invoiceDetails as $invoiceDetail)
              @if ($invoiceDetail->invoice_id == $invoice->id)
                @foreach ($contacts as $contact)
                  @if ($invoiceDetail->contact_id == $contact->id)
                    <td>{{ $contact->name }}</td>
                  @endif
                @endforeach
              @endif
            @endforeach
          @endif
        @endforeach
        <td>{{ $salesOrder->total }}</td>
        {{-- <td>
          <form class="" action="/item/{{ $item->id }}/edit" method="edit">
            <input type="submit" name="submit" value="edit">
          </form>
          <form class="" action="/item/{{ $item->id }}/delete" method="post">
            {{ method_field('DELETE') }}
            <input type="submit" name="submit" value="delete">
            {{ csrf_field() }}
          </form>
        </td> --}}
        @php
          $i++;
        @endphp
    </tr>
  @endforeach
  </table>
  <form class="" action="/sales_order/create" method="get">
    <input type="submit" name="submit" value="tambah sales order">
  </form>

@endsection
