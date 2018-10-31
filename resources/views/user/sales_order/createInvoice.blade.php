@extends('layouts/userMaster')

@section('title', 'Create Invoice')

@section('headline', 'Create Invoice')

@section('content')

  <form class="" action="/sales_order" method="post" value="post">
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
    <select class="" name="contact_id">
      @foreach ($contacts as $contact)
        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
      @endforeach
    </select>
    @if($errors->has('contact_id'))
      <p>{{ $errors->first('contact_id') }}</p>
    @endif
    <br>
    <br>
    <input type="submit" name="submit" value="save">
    {{ csrf_field() }}
  </form>
@endsection
