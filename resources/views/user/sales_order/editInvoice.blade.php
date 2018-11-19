@extends('layouts/userMaster')

@section('title', 'Edit Invoice')

@section('headline', 'Edit '.$invoice->number)
@section('content')

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <form class="" action="/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id }}" method="post">
            {{ method_field('PUT') }}
            <input type="text" name="salesOrder_id" value="{{ $salesOrder->id}}" hidden>
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
                <input type="number" name="quantity_old" value="{{ $invoiceDetail->item_quantity }}" hidden>
                <input class="form-control col" type="number" name="quantity" value="{{ $invoiceDetail->item_quantity }}" placeholder="Qty" min="1">
                @if($errors->has('quantity'))
                  <p>{{ $errors->first('quantity') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="update">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
