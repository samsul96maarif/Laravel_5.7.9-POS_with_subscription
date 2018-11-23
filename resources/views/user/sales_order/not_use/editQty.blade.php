@extends('layouts/userMaster')

@section('title', 'Add Item')

@section('headline', 'Add Item')

@section('content')

  <script type="text/javascript">

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

      $('#tambah-item').append('<div class="row"><div class="col-md-5"><input class="form-control" type="text" name="item[]" value="" placeholder=""></div><div class="col-md-2"><input class="form-control" type="number" name="quantity[]" min="1" placeholder="Qty" value="1" min="1"></div><div class="col"><button class="btn btn-sm btn-danger" type="button" id="delete" name="delete">delete</button></div></div><br>');

      $('input[name="item[]"]:last').val($(this).text());

      $('#item_name').val('');

      $('#item_list').fadeOut();
    });

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


  <table class="table">
    <thead>
      <th scope="col">#</th>
      <th>Item</th>
      <th>Price</th>
      <th>Qty</th>
      <th>Total</th>
    </thead>
    <tbody>
      @php
        $i = 1;
      @endphp
      @foreach ($invoiceDetails as $invoiceDetail)
        @foreach ($items as $item)
          <tr>
            @if ($invoiceDetail->item_id == $item->id)
              <th scope="row">{{ $i }}</th>
              <td>{{ $item->name }}</td>
              <td>Rp.{{ $item->price }}</td>
              <td>{{ $invoiceDetail->item_quantity }}</td>
              <td>Rp.{{ $invoiceDetail->total }}</td>
            @endif
          </tr>
        @endforeach
        @php
          $i++;
        @endphp
      @endforeach
      <tr>
        <th>Total</th>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $salesOrder->total }}</td>
      </tr>
    </tbody>
  </table>

  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card">
        <div class="card-body">
          <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice" method="post" value="post">
            <input type="text" name="invoice" value="{{ $invoice->id }}" hidden>
            <input type="text" name="sales_order" value="{{ $salesOrder->id }}" hidden>
            <div class="row item">
              <div class="col">
                <label class="col-form-label" for="">Item</label>
              </div>
            </div>
            <input type="text" name="item_name" id="item_name" class="item_name form-control input-lg" value="{{ $invoiceDetail->item_name }}" placeholder="Search Item..." />
            <span id="item_list">
            </span>
            <br>
            <div class="row">
              <div class="col-md-5">
                <input class="form-control" type="text" name="item[]" value="" placeholder="">
              </div>
              <div class="col-md-2">
                <input class="form-control" type="number" name="quantity[]" min="1" placeholder="Qty" value="1" min="1"></div><div class="col">
                  <button class="btn btn-sm btn-danger" type="button" id="delete" name="delete">delete</button>
              </div>
            </div>
            <br>
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

            <br>
            <input class="btn btn-primary" type="submit" name="submit" value="add">
            {{ csrf_field() }}
          </form>
      </div>
    </div>
  </div>

@endsection
