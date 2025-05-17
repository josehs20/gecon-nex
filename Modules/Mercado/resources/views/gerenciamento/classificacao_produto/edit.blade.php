@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.classificacao_produto.index'), 'titulo' => 'Classificação de produto'], ['titulo' => 'Editar classificação de produto']]])

@section('content')
    >
    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar classificação de produto</h3>
            <p class="lead">Nesta tela você pode editar uma classificação de produto.</p>
        </div>
    </div>

    <form action="{{ route('cadastro.classificacao_produto.update', ['id' => $classificacao->id]) }}" method="POST">
        @method('PUT')
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->

        @include('mercado::gerenciamento.classificacao_produto.inc.form')
    </form>
@endsection
