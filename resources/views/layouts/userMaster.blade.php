<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
  </head>
  <body>
    <header>
      <nav>
        <a href="/home">home</a>
        <a href="/subscription">subscription</a>
        <a href="/sales_order">sales order</a>
        <a href="/contact">contact</a>
        {{-- <li class="nav-item dropdown"> --}}
            {{-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }} <span class="caret"></span>
            </a> --}}

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        {{-- </li> --}}
      </nav>
    </header>
    <br>
    @yield('content')
    <br>
    <footer>
      <p>&copy; MaarifCorp</p>
      {{-- <a href="https://zuragan.com/"><img src="{{ asset('img/zuragan.png')}}" alt=""></a> --}}
    </footer>
  </body>
</html>
