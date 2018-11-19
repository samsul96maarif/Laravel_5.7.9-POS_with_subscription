function samAutocomplete() {
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

  $(document).on('click', '.dropdown-item', function(){
    $('#contact_name').val($(this).text());
    $('#contact_list').fadeOut();
  });

  $(document).on('click', 'body', function(){
    $('#contact_list').fadeOut();
  });
}

function functionName() {
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

  $(document).on('click', '.dropdown-item', function(){
    $('#item_name').val($(this).text());

    $("div.item").append('<input type="text" name="item[]" class="item form-control" hidden>');
    $('input[name="item[]"]:last').html($(this).text());

    $('#item_list').fadeOut();
  });

  $(document).on('click', 'body', function(){
    $('#item_list').fadeOut();
  });
}
