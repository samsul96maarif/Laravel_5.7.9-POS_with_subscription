@extends('layouts/userMaster')

@section('title', 'Edit Sales Order')

@section('content')
  <h1>Edit Sales Order</h1>

  <form class="" action="/invoice/{{ $invoice->id }}" method="post" value="post">
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
