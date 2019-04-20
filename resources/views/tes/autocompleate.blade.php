<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>autocomplete demo</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<label for="autocomplete">Select a programming language: </label>
<input id="autocomplete">

<script>
$( "#autocomplete" ).autocomplete({
  var query = $(this).val();
  if(query != '')
  {
    $.ajax({
      url:"{{ route('autocomplete.fetch.item') }}",
      data:{query:query},
      delay: 250,
      success:function(data){
        $('.item_list').fadeIn();
        $('.item_list').html(data);
      }
    });
  }
  // source: [ "c++", "java", "php", "coldfusion", "javascript", "asp", "ruby" ]
});
</script>

</body>
</html>
