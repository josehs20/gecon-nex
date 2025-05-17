@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.forma_pagemento.index'), 'titulo' => 'Formas de pagamento'], ['titulo' => 'Visualizar forma de pagamento']]])

@section('content')
    <div class="cabecalho">
        {{-- <div class="page-header">
            <h3>Editar forma de pagamento</h3>
            <p class="lead">Nesta tela você pode editar uma forma de pagamento.</p>
        </div> --}}
        <div class="page-header">
            <h3>Visualizar forma de pagamento</h3>
            <p class="lead">Nesta tela você pode visualizar uma forma de pagamento por completo.</p>
        </div>
    </div>


    <form action="{{ route('cadastro.forma_pagemento.update', ['id' => $pagamento->id]) }}" method="POST">
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
        @method('PUT')
        @include('mercado::gerenciamento.forma_pagamento.inc.form')

    </form>
@endsection
