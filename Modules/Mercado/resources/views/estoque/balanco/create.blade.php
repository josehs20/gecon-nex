@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('estoque.balanco.index'), 'titulo' => 'Balanços'], ['titulo' => 'Novo balanço']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Novo Balanço</h3>
            <p class="lead">
                Nesta tela você pode realizar o balanço dos itens.
            </p>
        </div>
    </div>

    @include('mercado::estoque.balanco.inc.form')

@endsection
