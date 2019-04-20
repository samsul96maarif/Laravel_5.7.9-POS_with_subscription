@extends('layouts/userMaster')

@section('title', 'Employees')

@section('headline', 'Employees')

@section('content')

  <script type="text/javascript">

  // autocomplete employe
    $(document).ready(function(){

      $('#employee_name').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
          $.ajax({
            url:"{{ route('autocomplete.fetch.employee') }}",
            data:{query:query},
            delay: 250,
            success:function(data){
              $('#employee_list').fadeIn();
              $('#employee_list').html(data);
            }
          });
        }
      });

      $(document).on('click', '.employee-list', function(){
        $('#employee_name').val($(this).text());
        $('#employee_list').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#employee_list').fadeOut();
      });
      // end autocomplete employe

    });
  </script>

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
      <a href="/employee/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New</a>
    </div>

    <div class="col-md-3 offset-md-5">
      <form class="" action="/employee/search" method="get">
        <div class="input-group mb-3" style="margin-bottom:0!important;">
          <input type="text" name="q" id="employee_name" class="form-control" aria-describedby="button-addon2" autocomplete="off" placeholder="Search Employee..." />
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-primary" type="submit" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
        <span id="employee_list">
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
    @foreach ($employees as $employee)
      <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $employee->username }}</td>
        <td>{{ $employee->name}}</td>
        <td>{{ $employee->email }}</td>
        <td>
          <div class="row">
            <div class="col text-right btn-kiri">
              <a href="/employee/{{ $employee->id }}/edit" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
            </div>
            <div class="col text-left btn-kanan">
              <form class="" action="/employee/{{ $employee->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <button onclick="return confirm('Do You Wanna Delete {{ $employee->name }}')" class="btn btn-outline-danger" type="submit" name="submit"><i class="fas fa-trash-alt"></i></button>
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
