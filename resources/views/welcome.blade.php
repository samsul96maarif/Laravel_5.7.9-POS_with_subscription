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

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                /* color: #636b6f; */
                color: black;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                font-size: 14px;
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
            .container {
              max-width: 960px;
            }

            .pricing-header {
              max-width: 700px;
            }

            .card-deck .card {
              min-width: 165px;
              }

              h1{
                font-size: 32px;
              }
        </style>
    </head>
    <body>
      <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal">Zuragan</h5>
        <nav class="my-2 my-md-0 mr-md-3">
          @if (Route::has('login'))
              <div class="top-right links">
                  @auth
                      <a class="p-2 text-dark" href="{{ url('/admin') }}">Home</a>
                  @else
                      <a class="p-2 text-dark" href="{{ route('login') }}">Login</a>
                      <a class="p-2 text-dark" href="{{ route('register') }}">Register</a>
                  @endauth
              </div>
          @endif
        </nav>
      </div>

      <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Subscription</h1>
      </div>

      <div class="container">
        <div class="card-deck mb-3 text-center">
          @foreach ($subscriptions as $subscription)
            <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h4 class="my-0 font-weight-normal">{{ $subscription->name }}</h4>
              </div>
              <div class="card-body">
                <h1 class="card-title pricing-card-title">Rp.{{ $subscription->price }} <small class="text-muted">/ month</small></h1>
                <ul class="list-unstyled mt-3 mb-4">
                  <li>free space for items</li>
                  <li>store {{ $subscription->num_invoices }} invoice</li>
                  <li>store {{ $subscription->num_users }} contact</li>
                </ul>
                <form  action="/subscription/{{ $subscription->id }}/pilih" method="get">
                  <input class="btn btn-lg btn-block btn-outline-primary" type="submit" name="submit" value="beli">
                </form>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </body>
</html>
