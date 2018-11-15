@extends('layouts/userMaster')

@section('title', 'Create Invoice')

@section('headline', 'Create Invoice')

@section('content')

  <span class="dropdown-menu">
  </span>
  <span>
    <input type="text" name="item_name" id="item_name" class="form-control input-lg" placeholder="Search Item..." />
  </span>
  <span id="item">
  </span>

  <script type="text/javascript">
    $(document).ready(function(){

      $('#item_name').keyup(function(){
        var query = $(this).val();
        if(query != '')
        {
          $.ajax({
            url:"{{ route('autocomplete.fetch') }}",
            data:{query:query},
            delay: 250,
            success:function(data){
              $('#item').fadeIn();
              $('#item').html(data);
            }
          });
        }
      });

      $(document).on('click', '.dropdown-item', function(){
        $('#item_name').val($(this).text());
        $('#item').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#item').fadeOut();
      });

    });
  </script>

@endsection
