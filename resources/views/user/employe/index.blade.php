@extends('layouts/userMaster')

@section('title', 'Employes')

@section('headline', 'Employes')

@section('content')

  {{-- <script type="text/javascript">

  // autocomplete employe
    $(document).ready(function(){

      $('#employe_name').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
          $.ajax({
            url:"{{ route('autocomplete.fetch.employe') }}",
            data:{query:query},
            delay: 250,
            success:function(data){
              $('#employe_list').fadeIn();
              $('#employe_list').html(data);
            }
          });
        }
      });

      $(document).on('click', '.employe-list', function(){
        $('#employe_name').val($(this).text());
        $('#employe_list').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#employe_list').fadeOut();
      });
      // end autocomplete employe

    });
  </script> --}}

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="row">
    <div class="col-md-4">
      <a href="/employe/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New</a>
    </div>

    <div class="col-md-3 offset-md-5">
      <form class="" action="/employe/search" method="get">
        <div class="input-group mb-3" style="margin-bottom:0!important;">
          <input type="text" name="q" id="employe_name" class="form-control" aria-describedby="button-addon2" autocomplete="off" placeholder="Search Employe..." />
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-primary" type="submit" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
        <span id="employe_list">
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
      <th scope="col">Username</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
    @foreach ($employes as $employe)
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $employe->username }}</td>
        <td>{{ $employe->name}}</td>
        <td>{{ $employe->email }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <a href="/employe/{{ $employe->id }}/edit" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/employe/{{ $employe->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <button onclick="return confirm('Do You Wanna Delete {{ $employe->name }}')" class="btn btn-outline-danger" type="submit" name="submit"><i class="fas fa-trash-alt"></i></button>
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
