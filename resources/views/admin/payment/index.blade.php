@extends('layouts/adminMaster')

@section('title', 'Payments')

@section('headline', 'payment')

@section('content')

  <div class="row">
    <div class="col-md-4">
      <a class="btn btn-outline-primary" href="/admin/payment/paid"><i class="fas fa-arrow-circle-right"></i> Just Show Who Has Proof</a>
    </div>
    <div class="col-md-4 offset-md-4">
      <form class="" action="/admin/payment/search" method="get">
        <div class="input-group mb-3">
          <input type="search" name="q" class="form-control" autocomplete="off" placeholder="Type The Last 3 digits of Amount" aria-describedby="button-addon2" value="">
          <div class="input-group-append">
            <button id="button-addon2" class="btn btn-primary" type="submit" name="submit"><i class="fas fa-search"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <br>
  @php
    $i = 1;
  @endphp
  <table class="table">
    <thead>
      <th>#</th>
      <th>Proof</th>
      <th>Unique Code</th>
      <th>Amount</th>
      <th>Company Name</th>
      <th>Package Subscription</th>
      <th>Action</th>
    </thead>
    <tbody>
      @foreach ($payments as $payment)
        <tr>
          <th scope="row">{{ $i }}</th>
          {{-- mengecek bila bukti transfer sudah ada atau belum --}}
          @if ($payment->proof != null)
            <td><b>True</b></td>
            {{-- uniq code adalah 3 digit terakhir dari transfer --}}
            <td><a class="btn" href="/admin/payment/{{ $payment->id }}">{{ $payment->uniq_code }}</a></td>
          @else
            <td>False</td>
            {{-- uniq code adalah 3 digit terakhir dari transfer --}}
            <td>{{ $payment->uniq_code }}</td>
          @endif

          <td>Rp.{{ number_format($payment->amount,2,",",".") }}</td>

          @foreach ($organizations as $organization)
            @if ($payment->organization_id == $organization->id)
              <td><a class="btn" href="/admin/organization/{{ $organization->id }}">{{ $organization->name }}</a></td>

              @foreach ($subscriptions as $subscription)
                @if ($subscription->id == $organization->subscription_id)
                  <td><a class="btn" href="/admin/subscription/{{ $organization->subscription_id }}">{{ $subscription->name }}</a></td>
                  <td>
                    {{-- unutk mengaktifkan --}}
                    @if ($organization->status == 0)
                      <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                        {{ method_field('PUT') }}
                        <input type="text" name="status" value="1" hidden>
                        <input onclick="return confirm('Do You Wanna Activate Package {{ $subscription->name }} For {{ $organization->name }}')" class="btn btn-primary" type="submit" name="submit" value="Activate">
                        {{ csrf_field() }}
                      </form>
                      {{-- unutk meng extend --}}
                    @else
                      <div class="row btn-atas">
                        <div class="col">
                          <form class="" action="/admin/organization/{{ $organization->id }}/extend" method="post">
                            {{ method_field('PUT') }}
                            {{-- rencananya expire date bisa docustome ingin
                            menambah berapa bulan --}}
                            <input type="text" name="period" value="{{ $payment->period }}" hidden>
                            <input onclick="return confirm('Do You Wanna Extend Package {{ $subscription->name }} For {{ $organization->name }}')" class="btn btn-warning" type="submit" name="submit" value="Extend Period">
                            {{ csrf_field() }}
                          </form>
                        </div>
                      </div>
                      {{-- unutk menonaktifkan --}}
                      {{-- <div class="row">
                        <div class="col">
                          <form class="" action="/admin/organization/{{ $organization->id }}" method="post">
                            {{ method_field('PUT') }}
                            <input type="text" name="status" value="0" hidden>
                            <input class="btn btn-sm btn-danger"type="submit" name="submit" value="deactivate">
                            {{ csrf_field() }}
                          </form>
                        </div>
                      </div> --}}
                    @endif
                  </td>
                @endif
                {{-- foreach subscription --}}
              @endforeach

            @endif
            {{-- foreach organizations --}}
          @endforeach
        </tr>
        @php
        $i++;
        @endphp
        {{-- foreach payments --}}
      @endforeach

    </tbody>
  </table>

@endsection
