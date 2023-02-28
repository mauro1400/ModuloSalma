<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Latest compiled and minified CSS -->
   
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <!-- Styles -->
    <!-- JavaScript Bundle with Popper -->
    <script src="https://kit.fontawesome.com/a04a6f7513.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li><a class="nav-link" href="{{ url('/reporte/reporteCertificadoOrigen') }}">
                                <h6><b>|</b> Reporte Certificados de Origen</h6>
                            </a></li>
                        <li><a class="nav-link" href="{{ url('/reporte/ReporteArticulos') }}">
                                <h6><b>|</b> Reporte de Articulo</h6>
                            </a></li>
                        <li><a class="nav-link" href="{{ url('/reporte/reportePartidas') }}">
                                <h6><b>|</b> Reporte de Partida</h6>
                            </a></li>
                        <li><a class="nav-link" href="{{ url('/SolicitudArticulo/solicitud-articulo') }}">
                                <h6><b>|</b> Solicitud de Articulo</h6>
                            </a></li>
                        <li><a class="nav-link" href="{{ url('/formulario') }}">
                                <h6><b>|</b> Formulario de Inexistencia</h6>
                            </a></li>
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (!Auth::check())
                            <li><a class="nav-link" href="{{ url('/login') }}"></a></li>
                            <li><a class="nav-link" href="{{ url('/register') }}"></a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
        @yield('reporte')
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    @stack('scripts')
</body>

</html>
<style>
    body {
        font-size: 12px;
    }
</style>
