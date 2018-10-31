@extends('layouts/userMaster')

@section('title', 'Edit Sales Order')

@section('headline', 'Edit '.$salesOrder->order_number)

@section('content')

  <form class="" action="/sales_order/{{ $salesOrder->id }}" method="post" value="post">
    {{ method_field('PUT') }}
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
    <input type="submit" name="submit" value="update">
    {{ csrf_field() }}
  </form>
@endsection
