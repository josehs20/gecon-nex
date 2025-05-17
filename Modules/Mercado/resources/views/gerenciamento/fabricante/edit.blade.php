@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicia'], ['rota' => route('cadastro.fabricante.index'), 'titulo' => 'Listagem de fabricantes'], ['titulo' => 'Editar fabricante']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Editar dados de fabricante</h3>
            <p class="lead">Nesta tela você pode editar os dados de um fabricante.</p>
        </div>
    </div>

    <form id="fabricante" data-identifier="form-update" action="{{ route('cadastro.fabricante.update', ['fabricante_id' => $fabricante->id, 'endereco_id' => $endereco->id ?? 0]) }}" method="POST">
        @csrf
        @include('mercado::gerenciamento.fabricante.inc.form')
    </form>

@endsection
