@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.forma_pagemento.index'), 'titulo' => 'Formas de pagamento'], ['titulo' => 'Nova forma de pagamento']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastrar nova forma de pagamento</h3>
            <p class="lead">Nesta tela você pode cadastrar uma forma de pagamento.</p>
        </div>
    </div>

    <form action="{{ route('cadastro.forma_pagemento.store') }}" method="POST">
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
        @include('mercado::gerenciamento.forma_pagamento.inc.form')

    </form>
@endsection
