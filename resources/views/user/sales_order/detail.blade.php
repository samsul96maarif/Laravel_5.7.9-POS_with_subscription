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
    <div class="col">
      <form class="" action="/sales_order/{{ $salesOrder->id }}" method="post" value="post">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col">
                {{ method_field('PUT') }}
                <select class="form-control" name="contact_id">
                  @foreach ($contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                  @endforeach
                </select>
                @if($errors->has('contact_id'))
                  <p>{{ $errors->first('contact_id') }}</p>
                @endif
              </div>
              <div class="col">
                <input class="btn btn-outline-primary" type="submit" name="submit" value="change customer">
              </div>
              {{ csrf_field() }}
          </div>
        </div>
      </div>
    </form>
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
      <th>Action</th>
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
              <td>
                <div class="row">
                  <div class="col">
                    <form class="col text-right" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id }}/edit" method="get">
                      <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
                    </form>
                  </div>
                  <div class="col">
                    <form class="col text-left" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id }}/delete" method="post">
                      {{ method_field('DELETE') }}
                      <input class="btn btn-outline-danger" type="submit" name="submit" value="delete">
                      {{ csrf_field() }}
                    </form>
                  </div>
                </div>
              </td>
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

  <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/create" method="get">
    <input class="btn btn-primary" type="submit" name="submit" value="add item">
  </form>

@endsection
