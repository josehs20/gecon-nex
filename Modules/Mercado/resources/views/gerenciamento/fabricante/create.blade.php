@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.fabricante.index'), 'titulo' => 'Listagem de fabricantes'], ['titulo' => 'Cadastrar fabricante']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Cadastrar fabricante</h3>
            <p class="lead">Nesta tela você pode cadastrar um fabricante.</p>
        </div>
    </div>

    <form id="fabricante" data-identifier="form-store" action="{{ route('cadastro.fabricante.store') }}" method="POST">
        @csrf
        @include('mercado::gerenciamento.fabricante.inc.form')
    </form>

@endsection
