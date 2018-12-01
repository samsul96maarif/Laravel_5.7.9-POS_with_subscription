@extends('layouts/adminMaster')

@section('title', 'Users')

@section('headline', 'Users')

@section('content')

  <script type="text/javascript">

  // autocomplete user
    $(document).ready(function(){

      $('#user_name').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
          $.ajax({
            url:"{{ route('autocomplete.fetch.user') }}",
            data:{query:query},
            delay: 250,
            success:function(data){
              $('#user_list').fadeIn();
              $('#user_list').html(data);
            }
          });
        }
      });

      $(document).on('click', '.contact-list', function(){
        $('#user_name').val($(this).text());
        $('#user_list').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#user_list').fadeOut();
      });
      // end autocomplete user

    });
  </script>

  <div class="col-md-4 offset-md-8">
    <form class="" action="/admin/user/search" method="get">
      <div class="input-group mb-3" style="margin-bottom:0!important;">
        <span>
          <input type="text" name="q" id="user_name" class="form-control" aria-describedby="button-addon2" autocomplete="off" placeholder="Search User..." />
        </span>
        <div class="input-group-append">
          <input id="button-addon2" class="btn btn-primary" type="submit" name="submit" value="Search">
        </div>
      </div>
      <span id="user_list">
      </span>
    </form>
  </div>
<br>

@php
  $i=1;
@endphp
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Company Name</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
        <tr>
          <th scope="row">{{ $i }}</th>
          <td><a class="btn" href="/admin/user/{{ $user->id }}">{{ $user->name }}</a></td>
          <td>{{ $user->username }}</td>
          <td>{{ $user->email }}</td>
          @foreach ($organizations as $organization)
            @if ($user->id == $organization->user_id)
              <td><a class="btn" href="/admin/organization/{{ $organization->id }}">{{ $organization->name }}</a></td>
            @endif
          @endforeach
        </tr>
        @php
        $i++;
        @endphp
      @endforeach
    </tbody>
  </table>

@endsection
