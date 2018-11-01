@extends('layouts/userMaster')

@section('title', 'Create Invoice')

@section('headline', 'Create Invoice')

@section('content')

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <form class="" action="/sales_order" method="post" value="post">
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
            <div class="row">
              <div class="col">
                <label class="col-form-label" for="">Customer</label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <select class="form-control" name="contact_id">
                  @foreach ($contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                  @endforeach
                </select>
                @if($errors->has('contact_id'))
                  <p>{{ $errors->first('contact_id') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="create">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
