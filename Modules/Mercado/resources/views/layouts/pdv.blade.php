<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GECON</title>

    <link rel="icon" href="{{ asset('img/logo_gecon.jpg') }}" type="image">
    @vite(['resources/js/app.js', 'resources/css/caixa.css', 'Modules/Mercado/resources/assets/js/views/pdv/caixa.js'], 'build/.vite')

</head>

<div class="loader" id="global-loader">
    <div class="loader-icon"></div>
</div>

<body>

    {{-- Tela central para desenvolvimento --}}

    @if (auth()->user())

        @yield('content')
    @endif
</body>

</html>
