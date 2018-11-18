@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                @if (Auth::user()->role == 1)
                  <p>welcome sam</p>
                @else
                  <p>welcome</p>
                @endif

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>

                <div class="card-body">
                  Hello {{ auth::user()->name }} <br>
                  Email anda : {{ Auth::user()->email }} <br>
                  Anda login menggunakan username : {{ Auth::user()->username }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
