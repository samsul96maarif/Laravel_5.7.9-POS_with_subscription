@extends('layouts/userMaster')

@section('title', 'Create Invoice')

@section('headline', 'Create Invoice')

@section('content')

  <span>
    <input type="text" name="item_name" id="item_name" class="form-control input-lg" placeholder="Search Item..." />
  </span>
  <span id="item">
  </span>
  <button type="button" name="button" id="tambah">tambah item</button>
  {{-- <input type="submit" name="" id="tambah" value="tambah item"> --}}

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

      $('#tambah').click(function(){
        $('#tambah').prepend('<input type="text" name="item_name" id="item_nam" class="form-control input-lg" placeholder="Search Item..." />');
      });

      var i = 1;
      $(document).on('click', '.dropdown-item', function(){
        $('#item_name').val('');
        // $('#ite_name').val($(this).text());
        $('#ite_name').val($(this).text());

        // var txt3 = document.createElement("li");
        // txt3.innerHTML = $(this).text();

        // $("#tabel-item").append(txt3);
        $("#tabel-item").append('<input type="text" name="item[]" class="item form-control" hidden>');
        $('input[name="item[]"]:last').html($(this).text());
        i=i+1;

        $("#tabel-item").append("<tr><td class='"+ i +"'>apaa</td></tr>");

        $('.'+i).html($(this).text());
        i=i+1;

        $('#item').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#item').fadeOut();
      });

    });
  </script>

  <br>

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <form class="" action="/sales_order" method="post" value="post">
            <div class="row">
              <div class="col">
                <label class="col-form-label" for="">Item</label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <input type="text" name="item_id" value="" readonly id="ite_name">

                <table class="table">
                  <tbody id="tabel-item">

                  </tbody>
                </table>

                <select class="form-control" name="item_id">
                  @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} price = Rp.{{ $item->price }}</option>
                  @endforeach
                </select>
                @if($errors->has('item_id'))
                  <p>{{ $errors->first('item_id') }}</p>
                @endif
              </div>
              <div class="col">
                <input class="form-control col" type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Qty" min="1">
                @if($errors->has('quantity'))
                  <p>{{ $errors->first('quantity') }}</p>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col">
                <label class="col-form-label" for="">Customer</label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <select class="form-control" name="contact_id">
                  @foreach ($contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                  @endforeach
                </select>
                @if($errors->has('contact_id'))
                  <p>{{ $errors->first('contact_id') }}</p>
                @endif
              </div>
            </div>
            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="create">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
