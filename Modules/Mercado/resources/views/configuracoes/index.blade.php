@extends('mercado::layouts.app')
@section('content')
    <style>
        .configuracoes{
            display: flex;
            align-content: center;
            justify-content: space-between;
            width: 70vw;
            margin: auto;
        }

        .configuracoes a{
            color: #000 !important;
        }
        
        .configuracoes-conteudo{
            width: 70%;
            min-height: 85vh;
            max-height: 85vh;
            padding-top: 7px;
        }
        .configuracoes-menu{
            width: 30%;
        }
    </style>
    <div class="configuracoes">
        @include('mercado::configuracoes.inc.menu')
        <div class="configuracoes-conteudo">
            @yield('configuracoes-conteudo')
        </div>
    </div>
@endsection
