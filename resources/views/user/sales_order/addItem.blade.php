@extends('layouts/userMaster')

@section('title', 'Add Item')

@section('content')
  <h1>Add item</h1>

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
          @endif
        </tr>
        @endforeach
      @endforeach
    <tr>
      <td>Total</td>
      <td>{{ $salesOrder->total }}</td>
    </tr>
  </table>
  <br>
  <br>

  <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice" method="post" value="post">
    <input type="text" name="invoice" value="{{ $invoice->id }}" hidden>
    <input type="text" name="sales_order" value="{{ $salesOrder->id }}" hidden>
    <select class="" name="item_id">
      @foreach ($items as $item)
        <option value="{{ $item->id }}">{{ $item->name }} price = {{ $item->price }}</option>
      @endforeach
    </select>
    @if($errors->has('item_id'))
      <p>{{ $errors->first('item_id') }}</p>
    @endif
    <br>
    <br>
    <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="jumlah barang" min="1">
    @if($errors->has('quantity'))
      <p>{{ $errors->first('quantity') }}</p>
    @endif
    <br>
    <br>
    <input type="submit" name="submit" value="save">
    {{ csrf_field() }}
  </form>
@endsection
