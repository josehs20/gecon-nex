<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GECON</title>

    <link rel="icon" href="{{ asset('img/logo_gecon.jpg') }}" type="image">
    @vite(['resources/js/app.js', 'resources/css/app.css'], 'build/.vite')

    <!-- Estilos -->
    {{-- <link rel="stylesheet" href="{{ asset('siedBar/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('siedBar/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('siedBar/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cssGeral.css') }}">
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('DataTables/buttons/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('Toastr/toastr.min.css') }}"> --}}

    <!-- jQuery deve ser o PRIMEIRO script JS -->

    {{-- <script src="{{ asset('siedBar/js/jquery-3.3.1.min.js') }}"></script> --}}

     {{-- <!-- Scripts dependentes de jQuery (seguem depois) -->
     <script src="{{ asset('siedBar/js/popper.min.js') }}"></script>
     <script src="{{ asset('siedBar/js/main.js') }}"></script>
     <script src="{{ asset('siedBar/js/bootstrap.min.js') }}"></script>
     <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
     <script src="{{ asset('select2/js/select2.min.js') }}"></script>
     <script src="{{ asset('maskMoney/maskMoney.min.js') }}"></script>
     <script src="{{ asset('MaskJquery/maskjquery.min.js') }}"></script>
     <script src="{{ asset('blockUI/blockUI.min.js') }}"></script>

     <script src="{{ asset('DataTables/buttons/dataTables.buttons.min.js') }}"></script>
     <script src="{{ asset('DataTables/buttons/buttons.html5.min.js') }}"></script>
     <script src="{{ asset('DataTables/buttons/buttons.print.min.js') }}"></script>
     <script src="{{ asset('DataTables/buttons/buttons.jszip.min.js') }}"></script> --}}
{{--
     <script src="{{ asset('Toastr/toastr.min.js') }}"></script>

     <!-- Seus scripts customizados SEMPRE por último -->
     <script src="{{ asset('js/gerais.js') }}"></script> --}}

</head>

<div class="loader" id="global-loader">
    <div class="loader-icon"></div>
</div>

<body>

    @if (auth()->user())
        <aside class="sidebar">
            <div class="toggle">
                <a href="#" class="burger js-menu-toggle" data-toggle="collapse" data-target="#main-navbar">
                    <span></span>
                </a>
            </div>
            <div class="side-inner">

                <div class="profile">
                    {{-- imagem da empresa designada --}}
                    <img src="{{ asset('img/logo_gecon.jpg') }}" alt="Imagem" class="" style="width: 80px">
                    {{-- <h3 class="name">{{ auth()->user()->name }}</h3> --}}
                    {{-- descricao perfil --}}
                    {{-- <span class="country">New York, USA</span>  --}}
                    <h5 style="text-align: center;"><b>{{ auth()->user()->empresa->nome_fantasia ?? '' }}</b> </h5>
                    <!-- Centraliza o texto -->

                </div>

                {{-- Estrutura de MENUS sideBar  --}}
                {{-- ------------------------------------------------------------------------------------------------------------ --}}
                @include('mercado::layouts.inc.menus-sidebar')

            </div>
        </aside>
    @endif

    {{-- Tela central para desenvolvimento --}}
    <main class="ss" style="background-color: #0a0a1ada;">

        @if (auth()->user())
            @include('mercado::layouts.inc.navbar')
            <br>
            <div>
                <div class="trilha-paginas-acessadas">
                    @if (isset($trilhaPaginas))
                        @foreach ($trilhaPaginas as $trilha)
                            @if (array_key_exists('rota', $trilha))
                                <a href="{{ $trilha['rota'] }}">{{ $trilha['titulo'] }}</a>
                                <span>&nbsp;-&nbsp;</span>
                            @else
                                <p>{{ $trilha['titulo'] }}</p>
                            @endif
                        @endforeach

                    @endif

                </div>
            </div>


            @if (auth()->user()->getUserModulo->caixa && request()->routeIs('caixa.venda'))
                <script>
                    $('.navbar-custom').addClass('d-none');
                    $('.sidebar').addClass('d-none');
                </script>
                <div class="content-caixa">
                    @yield('content')
                </div>
            @else
                <div class="row">
                    @if (session('success'))
                        <div class="alert alert-success col-12 customTime" style="text-align: center !important"
                            role="alert">
                            {!! session('success') !!}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger col-12 customTime" style="text-align: center !important"
                            role="alert">
                            {!! session('error') !!}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="alert alert-warning col-12 customTime" style="text-align: center !important"
                            role="alert">
                            {!! session('warning') !!}
                        </div>
                    @endif
                    @if (session('info'))
                        <div class="alert alert-info col-12 customTime" style="text-align: center !important"
                            role="alert">
                            {!! session('info') !!}
                        </div>
                    @endif

                    <div class="col-12">
                        @yield('content')
                    </div>
                </div>

            @endif
        @else
            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success col-12 customTime" role="alert">
                        {!! session('success') !!}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger col-12 customTime" role="alert">
                        {!! session('error') !!}
                    </div>
                @endif
                <div class="col-12">
                    @yield('content')
                </div>
            </div>
        @endif
    </main>

    {{-- <script>
         $(document).on('mousemove', function(event) {
            var screenWidth = $(window).width();
            var mouseY = event.clientY;

            if (event.clientX <= 50 && !$('body').hasClass('show-sidebar')) {
                $('.burger').click();
            }
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {
            // Esconde o alerta após 5 segundos (5000ms)
            setTimeout(function() {
                $('.alert').fadeOut(); // Usa fadeOut para esconder o alerta com animação
            }, 5000); // 5000ms = 5 segundos
        });
    </script> --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const messages = document.querySelectorAll('.customTime');
                messages.forEach(function(message) {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';

                    setTimeout(() => message.remove(), 500); // Remove o elemento após a transição
                });
            }, 10000); // 10 segundos
        });
    </script>
</body>

</html>
