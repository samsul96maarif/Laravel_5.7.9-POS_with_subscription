@extends('layouts/'.$extend)

@section('title', $salesOrder->order_number)

@section('headline', $salesOrder->order_number)

@section('content')
  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <h3>{{ $invoice->number }}</h3>
  <div class="row">
    <div class="col-md-6">
      @foreach ($contacts as $contact)
        @if ($contact->id == $salesOrder->contact_id)
          <h4>Customer : {{ $contact->name }}</h4>
        @endif
      @endforeach
    </div>
  </div>
  <br>
  <table class="table">
    <thead>
      <th scope="col">#</th>
      <th>Item</th>
      <th>Price</th>
      <th>Qty</th>
      <th>Amount</th>
    </thead>
    <tbody>
      @php
        $i = 1;
      @endphp
      @foreach ($invoiceDetails as $invoiceDetail)
        @foreach ($items as $item)
          <tr>
            @if ($invoiceDetail->item_id == $item->id)
              <th scope="row">{{ $i }}</th>
              <td>{{ $item->name }}</td>
              <td>Rp.{{ number_format($item->price,2,",",".") }}</td>
              <td>{{ $invoiceDetail->item_quantity }}</td>
              <td>Rp.{{ number_format($invoiceDetail->total,2,",",".") }}</td>
            @endif
          </tr>
        @endforeach
        @php
          $i++;
        @endphp
      @endforeach
      <tr>
        <th>Total</th>
        <td></td>
        <td></td>
        <td></td>
        <td>Rp.{{ number_format($salesOrder->total,2,",",".") }}</td>
      </tr>
    </tbody>
  </table>

@endsection
