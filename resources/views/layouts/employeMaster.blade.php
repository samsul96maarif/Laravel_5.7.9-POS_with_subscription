<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>

    <style media="screen">
      table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
      }
      th, td {
        padding: 15px;
        text-align: center;
      }
    </style>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    {{-- bosstrap --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    {{-- jquery boostrap --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>

    {{-- css custome --}}
    <link href="{{ asset('css/master.css') }}" rel="stylesheet">

    {{-- autocomplete --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    {{-- font awsome --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <title>@yield('title')</title>
  </head>
  <body>
    <div id="app">
      <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow navbar-expand-md" style="padding:10px;">
        {{-- penambahan warna custome --}}
        {{-- biru --}}
        {{-- <div class="container-fluid" style="background-color: #00c6ff!important"> --}}
          {{-- hijau --}}
        <div class="container-fluid" style="background-color: #A2C523!important">

          {{-- link ke zuragan --}}
          {{-- <a class="navbar-brand" href="https://app.zuragan.com"> --}}
            {{-- <img style="height:30px;" src="{{ asset('logo/logo_1.png') }}" alt=""> --}}
            {{-- <img style="height:30px;" src="{{ asset('logo/zuragan_logo_login.png') }}" alt=""> --}}
            {{-- Zuragan --}}
          {{-- </a> --}}

          {{-- no link --}}
          <div class="navbar-brand">
            <img style="height:30px;" src="{{ asset('logo/logo_1.png') }}" alt="">
            {{-- Zuragan --}}
          </div>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto">
          </ul>
          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
          <!-- Authentication Links -->
            @guest
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              <li class="nav-item">
                @if (Route::has('register'))
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
              </li>
                @else
                  <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="btn dropdown-toggle" style="color:white;" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                      {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  <i class="fas fa-sign-out-alt"></i>
                  {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

        {{-- <main class="py-4"> --}}
        <div class="container-fluid">
          <div class="row">
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
              <div class="sidebar-sticky">
                <ul class="nav flex-column">
                  <li class="nav-item nav-kiri">
                    <a class="nav-link" href="/sales_orders">
                      <span data-feather="home"></span>
                      <i class="fas fa-shopping-cart"></i>
                      Sales Order <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item nav-kiri">
                    <a class="nav-link" href="/contacts">
                      <span data-feather="home"></span>
                      <i class="fas fa-address-book"></i>
                      Contact <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item nav-kiri">
                    <a class="nav-link" href="/items">
                      <span data-feather="home"></span>
                      <i class="fas fa-cubes"></i>
                      Item <span class="sr-only">(current)</span>
                    </a>
                  </li>
                  <li class="nav-item nav-kiri">
                    <a class="nav-link" href="/report">
                      <span data-feather="home"></span>
                      <i class="fas fa-chart-bar"></i>
                      Report <span class="sr-only">(current)</span>
                    </a>
                  </li>
                </ul>

              </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

              <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                  @yield('headline')
                </h1>
              </div>
              <div class="container">
                @yield('content')
              </div>
            </main>
          </div>
        </div>
    </div>
    <div class="container-fluid" style="padding-bottom:30px;margin-bottom:30px;margin-top: 50px;">
    </div>
    <footer class="my-footer">
      <p style="color:white; margin-bottom:5px;">&copy; MaarifCorp</p>
      {{-- <a href="https://zuragan.com/"><img src="{{ asset('img/zuragan.png')}}" alt=""></a> --}}
    </footer>
  </body>
</html>
