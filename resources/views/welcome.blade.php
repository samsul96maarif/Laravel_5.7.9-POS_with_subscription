<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        {{-- boostrap --}}
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        {{-- jquery boostrap --}}
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

        {{-- css custome --}}
        <link href="{{ asset('css/master.css') }}" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                /* background-color: #fff; */
                /* background-color: #0077ff; */

                color: white;
                /* color: black; */
                /* font-family: 'Nunito', sans-serif; */
                font-weight: 200;

                /* height: 100vh; */
                height: 100%;
                margin: 0;
                font-size: 14px;

                /* warna forest */
            }

            body {
              background-image: linear-gradient(#A2C523, #486B00);
              /* background-repeat: no-repeat; */
              background-attachment: fixed;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            @media (min-width: 768px) {
              html {
                font-size: 16px;
              }
            }
            /* .container {
              max-width: 960px;
            } */

            /* .pricing-header {
              max-width: 700px;
            } */

            .card-deck .card {
              /* min-width: 165px; */
              color: black;
              }

              /* .card-deck{
                background-color: white;
              } */

              /* .card-deck .card {
                min-width: 220px;
                max-width: 440px;
                } */

                /* .card-title .pricing-card-title{
                  font-size: 15px;
                } */

                .card-header {
                  /* background-color:#00c6ff; */
                  background-color: #B0BDC5;
                  color: white;
                }

              /* h1{
                font-size: 32px;
              } */
        </style>

    </head>
    <body>
      <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm" style="background-color: #A2C523!important; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border-bottom: 0!important;">
        <h5 class="my-0 mr-md-auto font-weight-normal">Zuragan</h5>
        <nav class="my-2 my-md-0 mr-md-3">
          @if (Route::has('login'))
              <div class="top-right links">
                  @auth
                      <a class="p-2 text-light" href="{{ url('/admin') }}">Home</a>
                  @else
                      <a class="p-2 text-light" href="{{ route('login') }}">Login</a>
                      <a class="p-2 text-light" href="{{ route('register') }}">Register</a>
                  @endauth
              </div>
          @endif
        </nav>
      </div>

      <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Start Your Busniess From Here</h1>
      </div>

      <div class="container">
        <div class="card-deck mb-3 text-center justify-content-center">
          @foreach ($subscriptions as $subscription)
            <div class="card mb-4 shadow-sm rounded">
              <div class="card-header">
                <h4 class="my-0 font-weight-normal">{{ $subscription->name }}</h4>
              </div>
              <div class="card-body">
                {{-- https://www.w3schools.com/php/func_string_number_format.asp --}}
                <h3 class="card-title pricing-card-title">Rp.{{ number_format($subscription->price, 2, ",", ".") }} <small class="text-muted">/ month</small></h3>
                <ul class="list-unstyled mt-3 mb-4">
                  @if ($subscription->num_items == 0)
                    <li>Unlimited Space For Items</li>
                  @else
                    <li>Store Up to {{ number_format($subscription->num_items, 0, ",", ".") }} Items</li>
                  @endif

                  @if ($subscription->num_invoices == 0)
                    <li>Unlimited Space For Invoice</li>
                  @else
                    <li>Store Up to {{ number_format($subscription->num_invoices, 0, ",", ".") }} Invoice</li>
                  @endif

                  @if ($subscription->num_users == 0)
                    <li>Unlimited Users</li>
                  @else
                    <li>{{ number_format($subscription->num_users, 0, ",", ".") }} Users</li>
                  @endif
                </ul>
                <a class="btn btn-lg btn-block btn-primary" href="/subscription/{{ $subscription->id }}/detail">Buy</a>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </body>
</html>
