@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.unidade_medida.index'),'titulo' => 'Unidade de medidas'], ['titulo' => 'Cadastrar unidade de medida']]])


@section('content')

    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastro de unidada de medida</h3>
            <p class="lead">Nesta tela você pode cadastrar uma nova unidade meidida.</p>
        </div>
    </div>

    <form action="{{ route('cadastro.unidade_medida.store') }}" method="POST">
        @csrf <!-- Adiciona um token CSRF para proteção contra ataques CSRF -->
        @include('mercado::gerenciamento.unidade_medida.inc.form')
    </form>

@endsection
