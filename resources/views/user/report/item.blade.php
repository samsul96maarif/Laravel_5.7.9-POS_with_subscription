@extends('layouts/userMaster')

@section('title', 'Report')

@section('content')
  <h1>Report By Item</h1>

  <table>
    <th>No</th>
    <th>Item Name</th>
    <th>Amount</th>
    @php
      $i = 1;
      $total = 0;
    @endphp
  @foreach ($items as $item)
    <tr>
        <td>{{ $i }}</td>
        <td>{{ $item->name }}</td>
        <td>{{ $item->total }}</td>
        @php
          $total = $item->total + $total;
          $i++;
        @endphp
    </tr>
  @endforeach
  <tr>
    <td>Total</td>
    <td>{{ $total }}</td>
  </tr>
  </table>
  <form class="" action="/sales_order/create" method="get">
    <input type="submit" name="submit" value="tambah sales order">
  </form>

@endsection
