@extends('layouts/userMaster')

@section('title', 'Create Invoice')

@section('headline', 'Create Invoice')

@section('content')

  <script type="text/javascript">

    $(document).ready(function(){
      // autocomplete contact
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
      // unutk mengambil nilai dari atuocomplete
      $(document).on('click', '.item-list', function(){
        $('#item_name').val($(this).text());
        // menambahkan tag dan isi kedalam div yang telah disediakan
        $('#tambah-item').append('<div class="row"><div class="col-md-5"><input class="form-control" type="text" name="item[]" value="" placeholder=""></div><div class="col-md-2"><input class="form-control" type="number" name="quantity[]" min="1" placeholder="Qty" value="1" min="1"></div><div class="col"><button class="btn btn-sm btn-danger" type="button" id="delete" name="delete">delete</button></div></div><br>');
        // mengambil nilai dari autocompleate kedalam tag yang baru saja dibuat
        $('input[name="item[]"]:last').val($(this).text());
        // membuat isi Search pada autocomplete kosong
        $('#item_name').val('');
        // menutup daftar list yang sesuai dengan keyword
        $('#item_list').fadeOut();
      });
      // menutup list auto complete
      $(document).on('click', 'body', function(){
        $('#item_list').fadeOut();
      });
      //end auto complete item

      // delete item list
      $(document).on('click', '#delete', function(){
        // mengahpus br
        $(this).parent().parent().next().remove();
        // menghapus div row
        $(this).parent().parent().remove();
      });

      // add new contact
      $('.hidden').hide();
      $('#add-contact').click(function(){
        $('.hidden').toggle();
      });

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
            {{-- autocomplete contaact --}}
            <div class="row">
              <div class="col">
                <span>
                  <input type="text" name="contact" id="contact_name" class="form-control" autocomplete="off" placeholder="Search Contact..." />
                </span>
                <span id="contact_list">
                </span>
                @if($errors->has('contact'))
                  <p>{{ $errors->first('contact') }}</p>
                @endif
              </div>
            </div>
            {{-- button unutk memunculkan form add contact --}}
            <button class="btn btn-sm btn-secondory" type="button" name="button" id="add-contact">add new contact</button>
            <br><br>
            {{-- form add contact --}}
            <div class="hidden">
              <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name">
              {{-- untuk mengeluarkan error pada value "name" --}}
              @if($errors->has('name'))
                <p>{{ $errors->first('name') }}</p>
              @endif
              <br>

              <input class="form-control" type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone">
              @if($errors->has('phone'))
                <p>{{ $errors->first('phone') }}</p>
              @endif
              <br>
              <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name" class="form-control">
              @if($errors->has('company_name'))
                <p>{{ $errors->first('company_name') }}</p>
              @endif
              <br>
              <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="form-control">
              @if ($errors->has('email'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('email') }}</strong>
                </span>
              @endif
              <br>
            </div>

            <div class="row item">
              <div class="col">
                <label class="col-form-label" for="">Item</label>
              </div>
            </div>
            <input type="text" name="item_name" id="item_name" class="item_name form-control input-lg" autocomplete="off" placeholder="Search Item..." />
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
            {{-- tinggal memunculkan price --}}

            {{-- tempat unutk append input item --}}
            <div class="" id="tambah-item">

            </div>

            <input class="btn btn-primary" type="submit" name="submit" value="create">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
