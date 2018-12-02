@extends('layouts/'.$extend)

@section('title', 'Sales Order')

@section('headline', 'Sales Orders')

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
  $(document).ready(function () {
      $('#master').on('click', function(e) {
       if($(this).is(':checked',true))
       {
          $(".sub_chk").prop('checked', true);
       } else {
          $(".sub_chk").prop('checked',false);
       }

      });
  });
  </script>

  <div class="row">
    <div class="col-md-4">
      <a href="/sales_order/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> New</a>
    </div>
    <div class="col-md-3 offset-md-5">
      <form class="" action="/sales_order/search" method="get">
        <div class="input-group mb-3">
          <input autocomplete="off" type="search" name="q" class="form-control" placeholder="Search Sales Order..." aria-describedby="button-addon2" value="">
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-primary" type="submit" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <form class="" action="/sales_order/delete/selected" method="post">
    <button type="submit" name="submit" onclick="return confirm('Do you Wanna Delete')" style="margin-bottom: 10px" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Selected</button>
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th width="50px"><input type="checkbox" id="master"></th>
          <th>Date</th>
          <th>Order#</th>
          <th>Customer</th>
          <th>Total</th>
          @if ($user->role == 1)
            <th>Writer</th>
          @endif
          {{-- <th>Action</th> --}}
        </tr>
      </thead>
      <tbody>
        @php
        $i = 1;
        @endphp
        @foreach ($salesOrders as $salesOrder)
          <tr>
            <th scope="row">{{ $i }}</th>
            <td><input type="checkbox" class="sub_chk" name="pilih[]" value="{{ $salesOrder->id }}" ></td>
            <td>{{ date('d-m-Y', strtotime($salesOrder->created_at)) }}</td>
            <td> <a class="btn" href="/sales_order/{{ $salesOrder->id }}/detail">{{ $salesOrder->order_number }}</a></td>
            @foreach ($invoices as $invoice)
              @if ($invoice->sales_order_id == $salesOrder->id)
                @foreach ($contacts as $contact)
                  @if ($invoice->contact_id == $contact->id)
                    <td>{{ $contact->name }}</td>
                  @endif
                @endforeach
              @endif
            @endforeach
            <td>Rp.{{ number_format($salesOrder->total,2,",",".") }}</td>
            @if ($user->role == 1)
              <td>
                @foreach ($employes as $employe)
                  @if ($employe->id == $salesOrder->writer_id)
                    {{ $employe->username }}
                  @endif
                @endforeach
              </td>
            @endif
            {{-- delete persatuan --}}
            {{-- <td>
              <form class="" action="/sales_order/{{ $salesOrder->id }}/delete" method="post">
                {{ method_field('DELETE') }}
                <button onclick="return confirm('Do you Wanna Delete {{ $salesOrder->order_number}}')" type="submit" name="submit" class="btn btn-outline-danger"><i class="fas fa-trash-alt"></i></button>
                {{ csrf_field() }}
              </form>
            </td> --}}
            @php
              $i++;
            @endphp
          </tr>
        @endforeach
      </tbody>
    </table>
  </form>

@endsection
