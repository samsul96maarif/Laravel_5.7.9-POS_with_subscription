@extends('layouts/userMaster')

@section('title', 'Create Invoice')

@section('headline', 'Create Invoice')

@section('content')

  {{-- <span>
    <input type="text" name="item" id="item_name" class="item_name form-control input-lg" placeholder="Search Item..." />
  </span>
  <span id="item_list">
  </span>
  <br> --}}

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

        $('#tambah-item').append('<div class="row"><div class="col-md-8"><input class="form-control" type="text" name="item[]" value="" placeholder=""></div><div class="col"><input class="form-control col" type="number" name="quantity[]" min="1" placeholder="Qty" value="1" min="1"></div></div><br>');

        $('input[name="item[]"]:last').val($(this).text());

        $('#item_name').val('');

        $('#item_list').fadeOut();
      });

      $(document).on('click', 'body', function(){
        $('#item_list').fadeOut();
      });
      //end auto complete item

    });
  </script>

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <form class="" action="/sales_order" method="post" value="post">
            <div class="row">
              <div class="col">
                <label class="col-form-label" for="">Customer</label>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <span>
                  <input type="text" name="contact" id="contact_name" class="form-control" placeholder="Search Contact..." />
                </span>
                <span id="contact_list">
                </span>
                @if($errors->has('contact'))
                  <p>{{ $errors->first('contact') }}</p>
                @endif
              </div>
            </div>

            <button type="button" name="button" id="add-contact">add new contact</button>

            <div class="row item">
              <div class="col">
                <label class="col-form-label" for="">Item</label>
              </div>
            </div>
            <input type="text" name="item_name" id="item_name" class="item_name form-control input-lg" placeholder="Search Item..." />
            <span id="item_list">
            </span>
            {{-- error item --}}
            @if($errors->has('item'))
              <p>{{ $errors->first('item') }}</p>
            @endif
            {{-- error qty --}}
            @if($errors->has('quantity'))
              <p>{{ $errors->first('quantity') }}</p>
            @endif

            <br>

            {{-- tempat unutk append input item --}}
            <div class="" id="tambah-item">

            </div>

            {{-- <div class="row">
              <div class="col-md-8"> --}}

                {{-- <input class="form-control" type="text" name="item[]" value="" placeholder=""> --}}

                {{-- <select class="form-control" name="item_id">
                  @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} price = Rp.{{ $item->price }}</option>
                  @endforeach
                </select>
                @if($errors->has('item_id'))
                  <p>{{ $errors->first('item_id') }}</p>
                @endif --}}
              {{-- </div>
              <div class="col">
                <input class="form-control col" type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="Qty" min="1">
                @if($errors->has('quantity'))
                  <p>{{ $errors->first('quantity') }}</p>
                @endif
              </div>
            </div>
            <br> --}}

            <input class="btn btn-primary" type="submit" name="submit" value="create">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
