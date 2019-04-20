@extends('layouts/'.$extend)

@section('title', 'Items')

@section('headline', 'Items')

@section('content')

  <script type="text/javascript">

  // autocomplete contact
    $(document).ready(function(){

      // autocomplete item
      $('#item_name').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
          $.ajax({
            url:"{{ route('autocomplete.fetch.item') }}",
            data:{query:query},
            delay: 250,
            success:function(data){
              $('#item_list').fadeIn();
              $('#item_list').html(data);
            }
          });
        }
      });

      $(document).on('click', '.item-list', function(){
        $('#item_name').val($(this).text());

        $('#item_list').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#item_list').fadeOut();
      });
      //end auto complete item

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
      <a href="/item/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New</a>
    </div>

    <div class="col-md-3 offset-md-5">
      <form class="" action="/item/search" method="get">
        <div class="input-group mb-3" style="margin-bottom:0!important;">
          <input autocomplete="off" type="text" name="q" id="item_name" class="form-control" aria-describedby="button-addon2" placeholder="Search Item..." />
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-primary" type="submit" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
        <span id="item_list">
        </span>
      </form>
    </div>
    {{-- row --}}
  </div>

  <br>

  <table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Name</th>
      <th scope="col">Description</th>
      <th scope="col">Price</th>
      <th scope="col">Stock</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $i = 1;
    @endphp
  @foreach ($items as $item)
    <tr>
        <th scope="row">{{ $i }}</th>
        <td>{{ $item->name }}</td>
        <td>{{ $item->description }}</td>
        <td>Rp.{{ number_format($item->price,2,",",".") }}</td>
        <td>{{ $item->stock }}</td>
        <td>
          <div class="row">
            @if ($extend == 'userMaster')
              <div class="col text-right btn-kiri">
                <a href="/item/{{ $item->id }}/edit" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
              </div>
              <div class="col text-left btn-kanan">
                <form class="" action="/item/{{ $item->id }}/delete" method="post">
                  {{ method_field('DELETE') }}
                  <button onclick="return confirm('Do You Wanna Delete {{ $item->name }}')" class="btn btn-outline-danger" type="submit" name="submit"><i class="fas fa-trash-alt"></i></button>
                  {{ csrf_field() }}
                </form>
              </div>
            @else
              <div class="col">
                <a href="/item/{{ $item->id }}/edit" class="btn btn-outline-primary"><i class="fas fa-edit"></i></a>
              </div>
            @endif
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
