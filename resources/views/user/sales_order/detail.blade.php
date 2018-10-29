@extends('layouts/userMaster')

@section('title', '{{ $salesOrder->order_number }}')

@section('content')
  <h2>{{ $salesOrder->order_number }}</h2>
  <h3>{{ $invoice->number }}</h3>

  <table>
    <th>Item</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Action</th>
      @foreach ($invoiceDetails as $invoiceDetail)
        @foreach ($items as $item)
          <tr>
          @if ($invoiceDetail->item_id == $item->id)
            <td>{{ $item->name }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $invoiceDetail->item_quantity }}</td>
            <td>{{ $invoiceDetail->total }}</td>
            <td>
              <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id }}/edit" method="get">
                <input type="submit" name="submit" value="edit">
              </form>
              <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <input type="submit" name="submit" value="delete">
                {{ csrf_field() }}
              </form>
            </td>
          @endif
        </tr>
        @endforeach
      @endforeach
    <tr>
      <td>Total</td>
      <td>{{ $salesOrder->total }}</td>
    </tr>
  </table>
  <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/create" method="get">
    <input type="submit" name="submit" value="tambah item">
  </form>

@endsection
