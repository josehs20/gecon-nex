@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.classificacao_produto.index'), 'titulo' => 'Classificação de produto'], ['titulo' => 'Cadastro de classificações de produtos']]])

@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastro de classificação de produto</h3>
            <p class="lead">Nesta tela você pode cadastrar uma classificação de produto.</p>
        </div>
    </div>

    <form action="{{ route('cadastro.classificacao_produto.store') }}" method="POST">
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->

        @include('mercado::gerenciamento.classificacao_produto.inc.form')
    </form>
@endsection
