@extends('layouts/userMaster')

@section('title', 'Contacts')

@section('headline', 'Contacts')

@section('content')

  <script type="text/javascript">

  // autocomplete contact
    $(document).ready(function(){

      $('#contact_name').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
          $.ajax({
            url:"{{ route('autocomplete.fetch') }}",
            data:{query:query},
            delay: 250,
            success:function(data){
              $('#contact_list').fadeIn();
              $('#contact_list').html(data);
            }
          });
        }
      });

      $(document).on('click', '.contact-list', function(){
        $('#contact_name').val($(this).text());
        $('#contact_list').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#contact_list').fadeOut();
      });
      // end autocomplete contact

    });
  </script>

  @if (session('alert'))
    <div class="alert alert-success">
        {{ session('alert') }}
    </div>
  @endif

  <div class="row">
    <div class="col-md-4">
      <form class="" action="/contact/create" method="get">
        <input class="btn btn-primary" type="submit" name="submit" value="add contact">
      </form>
    </div>

    <div class="col-md-4 offset-md-4">
      <form class="" action="/contact/search" method="get">
        <div class="input-group mb-3" style="margin-bottom:0!important;">
          <span>
            <input type="text" name="q" id="contact_name" class="form-control" aria-describedby="button-addon2" autocomplete="off" placeholder="Search Contact..." />
          </span>
          <div class="input-group-append">
            <input id="button-addon2" class="btn btn-primary" type="submit" name="submit" value="Search">
          </div>
        </div>
        <span id="contact_list">
        </span>
      </form>
      {{-- col-md 4 offset 4 --}}
    </div>
    {{-- row --}}
  </div>

  <br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Phone</th>
      <th scope="col">Company Name</th>
      <th scope="col">Email</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
    @foreach ($contacts as $contact)
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $contact->name }}</td>
        <td>{{ $contact->phone }}</td>
        <td>{{ $contact->company_name }}</td>
        <td>{{ $contact->email }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <form class="" action="/contact/{{ $contact->id }}/edit" method="edit">
                  <input class="btn btn-outline-primary" type="submit" name="submit" value="edit">
              </form>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/contact/{{ $contact->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                  <input onclick="return confirm('Do you wanna Delete {{ $contact->name }}')" class="btn btn-outline-danger" type="submit" name="submit" value="delete">
                {{ csrf_field() }}
              </form>
            </div>
          </div>
        </td>
        @php
          $i++;
        @endphp
      </tr>
    @endforeach
  </tbody>
</table>

@endsection
