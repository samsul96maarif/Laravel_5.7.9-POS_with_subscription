@extends('layouts/userMaster')

@section('title', 'home')

@section('content')
  <h1>hei i am samsul, this page for create item</h1>

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
