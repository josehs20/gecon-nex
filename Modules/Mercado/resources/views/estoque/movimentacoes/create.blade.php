@extends('mercado::layouts.app', ['trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('estoque.movimentacao.index'), 'titulo' => 'Movimentações'], ['titulo' => 'Nova movimentação']]])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Nova movimentação</h3>
            <p class="lead">
                Nesta tela você pode realizar novas movimentações de estoque.
            </p>
        </div>
    </div>

    @include('mercado::estoque.movimentacoes.inc.form')
@endsection
