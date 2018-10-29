@extends('layouts/userMaster')

@section('title', 'Edit Invoice')

@section('content')
  <h1>Edit Invoice</h1>

  <form class="" action="/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id }}" method="post">
    {{ method_field('PUT') }}
    <input type="text" name="salesOrder_id" value="{{ $salesOrder->id}}" hidden>
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
    <input type="number" name="quantity_old" value="{{ $invoiceDetail->item_quantity }}" hidden>
    <input type="number" name="quantity" value="{{ $invoiceDetail->item_quantity }}" min="1" placeholder="jumlah barang" min="1">
    @if($errors->has('quantity'))
      <p>{{ $errors->first('quantity') }}</p>
    @endif
    <br>
    <br>
    <input type="submit" name="submit" value="update">
    {{ csrf_field() }}
  </form>
@endsection
