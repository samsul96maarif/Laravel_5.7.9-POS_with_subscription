@extends('layouts/userMaster')

@section('title', $salesOrder->order_number)

@section('headline', $salesOrder->order_number)

@section('content')

  @if (session('alert'))
    <div class="alert alert-danger">
        {{ session('alert') }}
    </div>
  @endif

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

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

      // add new contact
      $('.hidden').hide();
      $('#change-contact').click(function(){
        $('.hidden').toggle();
      });

      // add new item
      $('.hidden-add-item').hide();
      $('#add-item').click(function(){
        $('.hidden-add-item').toggle();
      });

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

        $('#tambah-item').append('<div class="row"><div class="col-md-5"><input class="form-control" type="text" name="item[]" value="" placeholder=""></div><div class="col-md-2"><input class="form-control" type="number" name="quantity[]" min="1" placeholder="Qty" value="1" min="1"></div><div class="col"><button class="btn btn-sm btn-danger" type="button" id="delete" name="delete"><i class="fas fa-trash-alt"></i></button></div></div><br>');

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

    });
  </script>

  <h3>{{ $invoice->number }}</h3>
  <div class="row">
    <div class="col-md-6">
      {{-- memunculkan nama customer --}}
      @foreach ($contacts as $contact)
        @if ($contact->id == $salesOrder->contact_id)
          <h4>Customer : {{ $contact->name }}</h4>
        @endif
      @endforeach
    </div>

    <div class="col">
      <form class="" action="/sales_order/{{ $salesOrder->id }}" method="post" value="post">
        {{ method_field('PUT') }}
      <div class="card">
        <div class="card-body">

          <div class="row btn-atas">
            <div class="col">
              {{-- button unutk memunculkan form change contact --}}
              <button type="button" name="button" class="btn btn-block btn-primary" id="change-contact">Edit Customer</button>
            </div>
          </div>
          {{-- form change contact --}}
          <div class="row hidden">
              <div class="col">
                <span>
                  <input type="text" name="contact" id="contact_name" autocomplete="off" class="form-control" placeholder="Search Contact..." />
                </span>
                <span id="contact_list">
                </span>
                @if($errors->has('contact'))
                  <p>{{ $errors->first('contact') }}</p>
                @endif
              </div>
              <div class="col">
                <input class="btn btn-outline-primary" type="submit" name="submit" value="Change Customer">
              </div>
              {{ csrf_field() }}
              {{-- row hidden --}}
            </div>
            {{-- div class="card-body" --}}
          </div>
          {{-- div class="card" --}}
        </div>
      </form>
    </div>
    {{-- row --}}
  </div>

  <br>
  <table class="table">
    <thead>
      <th scope="col">#</th>
      <th>Item</th>
      <th>Price</th>
      <th>Qty</th>
      <th>Amount</th>
      <th>Action</th>
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
              <td>Rp.{{ number_format($item->price,2,",",".") }}</td>
              <td>{{ $invoiceDetail->item_quantity }}</td>
              <td>Rp.{{ number_format($invoiceDetail->total,2,",",".") }}</td>
              <td>
                <div class="row">
                  <div class="col text-right btn-kiri">
                    {{-- tombol untuk mengurangi qty --}}
                    <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id}}/decrease" method="post">
                      {{ method_field('PUT') }}
                      <input type="submit" name="submit" value="-" class="btn btn-outline-warning">
                      {{ csrf_field() }}
                    </form>

                  </div>
                  <div class="col-md-2" style="padding:0;">
                    {{-- tombol unutk menambah qty --}}
                    <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id}}/increase" method="post">
                      {{ method_field('PUT') }}
                      <input type="submit" name="submit" value="+" class="btn btn-outline-primary">
                      {{ csrf_field() }}
                    </form>

                  </div>
                  <div class="col text-left btn-kanan">
                    {{-- button unutk menghapus item/invoiceDetail dari invoice --}}
                    <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/invoice_detail/{{ $invoiceDetail->id}}/delete" method="post">
                      {{ method_field('DELETE') }}
                      <button onclick="return confirm('Do You Wanna Delete {{ $item->name }} From Invoice?')" class="btn btn-outline-danger" type="submit" name="submit"><i class="fas fa-trash-alt"></i></button>
                      {{-- <input onclick="return confirm('Do you wanna delete {{ $item->name }} from invoice?')" class="btn btn-outline-danger" type="submit" name="submit" value="delete"> --}}
                      {{ csrf_field() }}
                    </form>

                  </div>
                  {{-- row --}}
                </div>
              </td>
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
        <td>Rp.{{ number_format($salesOrder->total,2,",",".") }}</td>
      </tr>
    </tbody>
  </table>
{{-- button unutk meminculkan form add item --}}
  <button type="button" id="add-item" name="button" class="btn btn-outline-primary">Add Item</button>
{{-- form add item pada sales order --}}
  <div class="hidden-add-item">
    <div class="row justify-content-center">
      <div class="col-md-7">
        <div class="card">
          <div class="card-body">
            <form class="" action="/sales_order/{{ $salesOrder->id }}/invoice/{{ $invoice->id }}/add_item" method="post">
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
              {{-- tinggal memunculkan price --}}

              {{-- tempat unutk append input item --}}
              <div class="" id="tambah-item">

              </div>
              <input class="btn btn-primary" type="submit" name="submit" value="Add">
              {{ csrf_field() }}
            </form>
            {{-- div class="card-body" --}}
          </div>
          {{-- div class="card" --}}
        </div>
        {{-- div class="col-md-7" --}}
      </div>
      {{-- div class="row justify-content-center" --}}
    </div>
    <br>
    {{-- div class="hidden-add-item" --}}
  </div>

  <div class="row">
    {{-- tombol unutk mengarahkan ke sales order dan berarti sudah membayar --}}
    <div class="col text-right btn-kiri">
      <a class="btn btn-primary" href="/sales_order">pay</a>
    </div>
    {{-- tombol unutk cancel/ hapus sales order --}}
    <div class="col text-left btn-kanan">
      <form class="" action="/sales_order/{{ $salesOrder->id }}/delete" method="post">
        {{ method_field('DELETE') }}
        <input onclick="return confirm('Do you Wanna Cancel {{ $salesOrder->order_number}}')" type="submit" name="submit" value="cancel" class="btn btn-danger">
        {{ csrf_field() }}
      </form>
    </div>
    {{-- row --}}
  </div>

@endsection
