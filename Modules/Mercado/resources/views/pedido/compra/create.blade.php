@extends('mercado::layouts.app', [
    'trilhaPaginas' => [['rota' => route('home.index'), 'titulo' => 'Página inicial'], ['rota' => route('cadastro.compra.index'), 'titulo' => 'Compras'], ['titulo' => 'Realizar compra']],
])

@section('content')
    <div class="cabecalho">
        <div class="page-header">
            <h3>Compra</h3>
            <p class="lead">
                Esta é a etapa onde você define qual fornecedor da cotação vai ser realizado a compra dos itens pedidos.
            </p>
        </div>
    </div>
    @include('mercado::pedido.compra.inc.form')
@endsection
