@extends('layouts/userMaster')

@section('title', 'Add Item')

@section('headline', 'Add Item')

@section('content')

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

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice" method="post" value="post">
            <input type="text" name="invoice" value="{{ $invoice->id }}" hidden>
            <input type="text" name="sales_order" value="{{ $salesOrder->id }}" hidden>
            <div class="row">
              <div class="col">
                <label class="col-form-label" for="">Item</label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <select class="form-control" name="item_id">
                  @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} price = Rp.{{ $item->price }}</option>
                  @endforeach
                </select>
                @if($errors->has('item_id'))
                  <p>{{ $errors->first('item_id') }}</p>
                @endif
              </div>
              <div class="col">
                <input class="form-control col" type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Qty" min="1">
                @if($errors->has('quantity'))
                  <p>{{ $errors->first('quantity') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="add">
            {{ csrf_field() }}
          </form>
      </div>
    </div>
  </div>

@endsection
