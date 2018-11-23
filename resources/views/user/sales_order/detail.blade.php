@extends('layouts/userMaster')

@section('title', $salesOrder->order_number)

@section('headline', $salesOrder->order_number)

@section('content')
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
      <th>Total</th>
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
              <td>Rp.{{ $item->price }}</td>
              <td>{{ $invoiceDetail->item_quantity }}</td>
              <td>Rp.{{ $invoiceDetail->total }}</td>
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
        <td>{{ $salesOrder->total }}</td>
      </tr>
    </tbody>
  </table>

@endsection
