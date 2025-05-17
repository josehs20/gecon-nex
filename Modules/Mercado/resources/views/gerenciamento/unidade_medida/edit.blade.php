@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.unidade_medida.index'),'titulo' => 'Unidade de medidas'], ['titulo' => 'Editar unidade de medida']]])

@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Edição de unidade de medida</h3>
            <p class="lead">Nesta tela você pode editar uma nova unidade medida.</p>
        </div>
    </div>

    <form action="{{ route('cadastro.unidade_medida.update', ['id' => $unidadeMedida->id]) }}" method="POST">
        @method('PUT')
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
        @include('mercado::gerenciamento.unidade_medida.inc.form')
    </form>

    @endsection
